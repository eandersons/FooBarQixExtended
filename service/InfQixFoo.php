<?php declare(strict_types=1);
require_once 'vendor/autoload.php';

/**
 * Process an input to provide an output based on characteristics of the input
 *
 * @author Edgars Andersons <Edgars@gaitenis.id.lv>
 */
final class InfQixFoo extends Transformation
{
    /**
     * Composite for getting divisibility of sum of all input digits
     *
     * @var SumOfDigitsDivisibilityTransformation
     */
    private SumOfdigitsDivisibilityTransformation $_divisibilityChange;

    /**
     * An integer to check if the sum of input digits is divisible by.
     *
     * @var integer
     */
    private $_divisor = 8;

    protected array $changeMap = [8 => 'Inf', 7 => 'Qix', 3 => 'Foo'];
    protected string $separator = '; ';

    protected function __construct($input, $changeMap = [], $separator = null)
    {
        parent::__construct($input, $this->changeMap, $this->separator);

        // Initialise composite properties
        $this->_multipliersChange = new MultipliersTransformation(
            $this->input,
            $this->changeMap,
            $this->separator
        );
        $this->_occurrencesChange = new OccurrencesTransformation(
            $this->input,
            $this->changeMap,
            $this->separator
        );
        $this->_divisibilityChange = new SumOfDigitsDivisibilityTransformation(
            $this->input,
            $this->changeMap,
            $this->separator,
            $this->_divisor
        );
        $this->output = $this->transform();
    }

    protected function transform(): string
    {
        return implode(
            $this->separator,
            array_filter(
                [$this->_multipliersChange, $this->_occurrencesChange],
                'strlen'  // To remove empty strings from the array
                // https://www.php.net/manual/en/function.array-filter.php#111091
            )
        ) . $this->_divisibilityChange;
    }
}

InfQixFoo::command($argc, $argv);
