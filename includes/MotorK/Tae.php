<?php

namespace MotorK;

/**
 * Class Tae
 *
 * @package MotorK
 */
class Tae
{
    /**
     * class instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Number of annual installments
     *
     * @var $numberOfInstallments
     */
    private $numberOfInstallments;

    /**
     * Nominal annual rate
     *
     * @var $tan
     */
    private $interestRate;

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
     * @return Tae
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
        $this->interestRate = (float) $args[0] / 100;
        $this->numberOfInstallments = $args[1];
    }


    /**
     * Calculate TAE
     *
     * @return number
     */
    public function calculate()
    {
        return (float) (
            pow(
                1 + (($this->interestRate) / $this->numberOfInstallments),
                $this->numberOfInstallments
            ) - 1
            ) * 100;
    }

    /**
     * Return TAE as string
     *
     * @return string
     */
    public function __toString()
    {
        return round($this->calculate(), 3) . ' %';
    }
}
