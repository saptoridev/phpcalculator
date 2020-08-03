<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryClearCommand extends Command
{
     /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    protected $logmanager;

    public function __construct(CommandHistoryManagerInterface $logmanager)
    {
        $this->logmanager = $logmanager ;

        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s',
            $commandVerb
        );
        $this->description = "Clear saved history";

        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'history:clear';
    }

    public function handle(): void
    {
        $this->logmanager->clearAll();
        $this->info("History cleared!");
    }

}