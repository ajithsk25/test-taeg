<?php

namespace MotorK;

/**
 * Class Taeg
 * 
 * @package MotorK
 */
class Taeg
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
     * Periodic payments
     *
     * @var $periodicPayment
     */
    private $periodicPayment;

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
     * @return Taeg
     */
    public static function init() : self
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
    private function applyArguments(array $args)
    {
        $this->principalAmount = $args[0];
        $this->interestRate = $args[1] / 100;
        $this->periodicPayment = $args[2];
        $this->numberOfInstallments = $args[3];
        $this->numberOfYears = $args[4];
    }

    /**
     * Reference https://stackoverflow.com/questions/9329017/calculating-apr-using-reg-z-appendix-j
     *
     * Calculate effective installment amount
     *
     * $aprGuess The guess to start estimating from, 10% is 0.1, not 0.001
     * $partial Odd days, as a fraction of a pay period.  10 days of a month is 0.33333...
     * $full Full pay periods before the first payment.  Usually 1.
     *
     * @return float
     */
    public function calculate() : float
    {
        $aprGuess = (float)($this->interestRate / 100) / $this->numberOfInstallments;
        $partial = 0;
        $full = 1;

        $tempGuess = $aprGuess;

        do {
            $aprGuess = $tempGuess;
            //Step 1
            $rate1 = $tempGuess / (100 * $this->numberOfInstallments);
            $amount1 = $this->generalEquation(
                $this->numberOfYears * $this->numberOfInstallments,
                $this->periodicPayment,
                $full,
                $partial,
                $rate1
            );
            //Step 2
            $rate2 = ($tempGuess + 0.1) / (100 * $this->numberOfInstallments);
            $amount2 = $this->generalEquation(
                $this->numberOfYears * $this->numberOfInstallments,
                $this->periodicPayment,
                $full,
                $partial,
                $rate2
            );
            //Step 3
            $tempGuess = $tempGuess + 0.1 * ($this->principalAmount - $amount1) / ($amount2 - $amount1);

        } while (abs($aprGuess * 10000 - $tempGuess * 10000) > 1);

        $interestRate = (float) round($aprGuess, 3);

        // call Tae class to find TAE
        $tae = Tae::init($interestRate, $this->numberOfInstallments);

        return $tae->calculate();
    }

    /**
     * @param int $period
     * @param float $payment
     * @param float $initialPeriods
     * @param float $fractions
     * @param float $rate
     *
     * @return float
     */
    private function generalEquation($period, $payment, $initialPeriods, $fractions, $rate)
    {
        $retVal = 0;
        for ($x = 0; $x < $period; $x++) {
            $retVal += $payment / ((1 + $fractions * $rate) * pow(1 + $rate, $initialPeriods + $x));
        }

        return $retVal;
    }

    /**
     * Return effective installment amount as string
     *
     * @return string
     */
    public function __toString() : string
    {
        return round($this->calculate(), 3) . ' %';
    }
}
