<?php

namespace MotorK;

/**
 * Class Rate
 *
 * @package MotorK
 */
class Rate
{
    /**
     * class instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Capital provided
     *
     * @var $principalAmount
     */
    private $principalAmount;

    /**
     * Nominal annual rate
     *
     * @var $tan
     */
    private $interestRate;

    /**
     * Number of annual installments
     *
     * @var $numberOfInstallments
     */
    private $numberOfInstallments;

    /**
     * Number of years
     *
     * @var $numberOfYears
     */
    private $numberOfYears;

    /**
     * The constructor is private to prevent initiation with outer code.
     */
    protected function __construct(){}

    /**
     * Declared as private to prevent cloning of an instance of the class
     * via the clone operator.
     */
    private function __clone(){}

    /**
     * Declared as private to prevent unserializing of an instance of the class
     * via the global function unserialize().
     */
    private function __wakeup(){}

    /**
     * Initializes singleton class with arguments
     *
     * @return Rate
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        self::$instance->applyArguments(func_get_args());

        return self::$instance;
    }

    /**
     * Apply arguments list to current instance
     *
     * @param array $args
     */
    private function applyArguments($args)
    {
        $this->principalAmount = $args[0];
        $this->interestRate = (float) $args[1] / 100;
        $this->numberOfInstallments = $args[2];
        $this->numberOfYears = $args[3];
    }

    /**
     * Calculate effective installment amount
     *
     * @return number
     */
    public function calculate()
    {
        return (float) (
            $this->principalAmount * (($this->interestRate) / $this->numberOfInstallments))
            / (1 - pow(
                1 + ($this->interestRate / $this->numberOfInstallments),
                -($this->numberOfYears * $this->numberOfInstallments)
            ));
    }

    /**
     * Return effective installment amount as string
     *
     * @return string
     */
    public function __toString()
    {
        return round($this->calculate(), 2) . ' €';
    }
}
