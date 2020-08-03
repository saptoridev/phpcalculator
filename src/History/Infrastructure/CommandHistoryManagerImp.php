<?php

namespace Jakmall\Recruitment\Calculator\History\Infrastructure;

use Exception;
use Jakmall\Recruitment\Calculator\Models\History;

class CommandHistoryManagerImp implements CommandHistoryManagerInterface{

    protected $filepath = '/tmp/commandlog.csv';
    protected $delimiter = "|";

    public function findAll($drivername): array{

        if($drivername=="database"){
            return History::all('command','description','result','output','created_at')->toArray();
        }

        return $this->readFileAll();
    }

    public function findByCommand($drivername, $commands): array{
        if($drivername=="database"){
            return History::whereIn('command',$commands)
                            ->get(['command','description','result','output','created_at'])->toArray();
        }
        return $this->readFileByCommand($commands);
    }

    public function log($command): bool{

        try{
            History::create([
                'command'=>$command['command'],
                'description'=>$command['description'],
                'result'=>$command['result'],
                'output'=>sprintf('%s = %s', $command['description'], $command['result']),
            ]);

            $fp = fopen($this->filepath, 'a');
            $command['time']=date("Y-m-d H:i:s");
            fputcsv($fp, $command,$this->delimiter);
            fclose($fp);

            return true;
        }
        catch(Exception $e){
            
        }
        return false;
       
    }

    public function clearAll():bool{
        try{
            History::truncate();

            $fp = fopen($this->filepath, 'w');        
            fclose($fp);

            return true;
        }
        catch(Exception $e){
            
        }
        return false;
    }

    private function readFileAll():array {
        $retval = array();
        $row=0;
        if (($handle = fopen($this->filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
               $newrow = array();
                $newrow['command']=ucfirst($data[0]);
                $newrow['description']=$data[1];
                $newrow['result']= $data[2];
                $newrow['output']= sprintf('%s = %s', $data[1], $data[2]);
                $newrow['created_at'] = $data['3'];
                $retval[]=$newrow;

            }
            fclose($handle);
        }
        return $retval;
    }

    private function readFileByCommand($commands):array {
        $retval = array();
        $row=0;
        if (($handle = fopen($this->filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
                if(!in_array($data[0],$commands)){
                    continue;
                }
                $newrow = array();
                $newrow['command']=ucfirst($data[0]);
                $newrow['description']=$data[1];
                $newrow['result']= $data[2];
                $newrow['output']= sprintf('%s = %s', $data[1], $data[2]);
                $newrow['created_at'] = $data['3'];
                $retval[]=$newrow;

            }
            fclose($handle);
        }
        return $retval;
    }



}