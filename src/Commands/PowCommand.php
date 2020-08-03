<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class PowCommand extends Command
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
            '%s {base : The base number} {exp : The exponent number}',
            $commandVerb
        );
        $this->description = sprintf('Exponent the given number');

        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'pow';
    }
   
    public function handle(): void
    {
        $base = $this->getBaseInput();
        $exp = $this->getExpInput();
        $description = $this->generateCalculationDescription($base,$exp);
        $result = $this->calculate($base,$exp);

        $this->comment(sprintf('%s = %s', $description, $result));

        $this->logmanager->log([
            "command" => $this->getCommandVerb(),
            "description"=>$description,
            "result" => $result
        ]);
    }

    protected function getBaseInput()
    {
        return $this->argument('base');
    }

    protected function getExpInput()
    {
        return $this->argument('exp');
    }


    protected function generateCalculationDescription(int $base,int $exp): string
    {
        $operator = $this->getOperator();
        return sprintf("%s %s %s",$base,$operator,$exp);
    }

    protected function getOperator(): string
    {
        return '^';
    }

  
    /**
     * @param int|float $base
     * @param int|float $exp
     *
     * @return int|float
     */
    protected function calculate($base, $exp)
    {
        return pow($base,$exp);
    }
}
