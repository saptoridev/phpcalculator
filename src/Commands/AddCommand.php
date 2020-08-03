<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;


class AddCommand extends Command
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
            '%s {numbers* : The numbers to be %s}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));

        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'add';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'added';
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        if(is_array($numbers)){
            foreach($numbers as $key=>$val){
                if(!is_numeric($val)){
                    $this->error(sprintf("Non-numeric value : %s",$val));
                    return;
                }
            }
        }
        
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);

        $this->comment(sprintf('%s = %s', $description, $result));

        $this->logmanager->log([
            "command" => $this->getCommandVerb(),
            "description"=>$description,
            "result" => $result
        ]);
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator(): string
    {
        return '+';
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 + $number2;
    }
}
