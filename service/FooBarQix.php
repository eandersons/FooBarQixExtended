<?php declare(strict_types=1);
require_once 'vendor/autoload.php';

/**
 * Process an input to provide an output based on charasteristics of the input
 *
 * Input should be transformed if it has certain characteristics (for example,
 * it is divisible by or it contains specific integers etc.).
 *
 * @author Edgars Andersons <Edgars@gaitenis.id.lv>
 */
final class FooBarQix extends Transformation
{
    /**
     * Composite for transforming input based on found multipliers
     *
     * @var MultipliersTransformation
     */
    private MultipliersTransformation $_multipliersChange;

    /**
     * Composite for transforming the input based on found occurrences
     *
     * @var OccurrencesTransformation
     */
    private OccurrencesTransformation $_occurrencesChange;

    protected array $changeMap = [3 => 'Foo', 5 => 'Bar', 7 => 'Qix'];
    protected string $separator = ', ';

    /**
     * Constructor
     *
     * @param integer|string $input A positive integer (may be provided as a
     *                              string)
     */
    protected function __construct($input, $changeMap = [], $separator = null)
    {
        parent::__construct($input, $this->changeMap, $this->separator);

        $this->validateInput();

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
        );
    }
}

FooBarQix::command($argc, $argv);
