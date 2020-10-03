<?php declare(strict_types=1);

/**
 *
 */
final class SumOfDigitsDivisibilityTransformation extends Transformation
{
    /**
     * An integer to check if the sum of input digits is divisible by.
     *
     * @var integer
     */
    private $_divisor;

    public function __construct($input, $changeMap, $separator, $divisor)
    {
        parent::__construct($input, $changeMap, $separator);

        $this->_divisor = $divisor;
        $this->output = $this->transform();
    }

    /**
     * Check if the sum of input integer's digits is divisible by `$intA`
     *
     * @return string
     */
    protected function transform(): string
    {
        $output = "";

        if (array_sum($this->inputDigitsAsArray()) % $this->_divisor === 0) {
            $output = isset($this->changeMap[$this->_divisor])
                ? $this->changeMap[$this->_divisor]
                : (string) $this->_divisor;
        }

        return $output;
    }
}
