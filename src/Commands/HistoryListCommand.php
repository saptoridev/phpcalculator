<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryListCommand extends Command
{
     /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    public function __construct(CommandHistoryManagerInterface $logmanager)
    {
        $this->logmanager = $logmanager ;

        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {commands?* : Filter the history by commands} {--D|driver=database : Driver for storage connection}',
            $commandVerb
        );
        $this->description = "Show calculator history";

        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'history:list';
    }

    public function handle(): void
    {        
        $commands = $this->argument('commands');

        if(is_array($commands)){
            foreach($commands as $key=>$val){
                $commands[$key]=strtolower($val);
            }
        }else if(is_string($commands)){
            $commands = strtolower($commands);
        }

        $drivername = $this->option('driver');
        if(!$drivername){
            $drivername='database';
        }
        

        $histories = [];
        if(sizeof($commands)>0){
            $histories = $this->logmanager->findByCommand($drivername,$commands);
        }else{
            $histories = $this->logmanager->findAll($drivername);
        }

        if(sizeof($histories)>0){
            $headers = ['No', 'Command','Description','Result','Output','Time'];
            $no=0;
            $newhistories=array();
            foreach($histories as $row){
                $newrow = array();
                $newrow['no']=++$no;
                $newrow['command']=ucfirst($row['command']);
                $newrow['description']=$row['description'];
                $newrow['result']= $row['result'];
                $newrow['output']= $row['output'];
                $newrow['time'] = $row['created_at'];
                $newhistories[$no-1]=$newrow;

            }
            $this->table($headers, $newhistories);
        }else{
            $this->info("History is empty.");
        }


    }

}