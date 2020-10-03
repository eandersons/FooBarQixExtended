<?php declare(strict_types=1);
require_once 'vendor/autoload.php';

/**
 * Interface for transformation classes
 *
 * @author Edgars Andersons <Edgars@gaitenis.id.lv>
 */
abstract class Transformation
{
    /**
     * An array that defines what transformations should be made based on input
     *
     * Array's keys are integers that are used to determine characteristics (
     * for instance, multipliers or occurrences) of the input and values are
     * strings that should be added to the output if the input has features
     * based on the key integer.
     * Items in the array must be provided in the order their value should be
     * appended to the output if the input is divisible by them.
     *
     * @var array
     */
    protected array $changeMap;

    /**
     * The input value that should be a positive integer
     *
     * The input integer may be provided as a string as well.
     *
     * @var integer|string
     */
    protected $input;

    /**
     * A string that shows if the input has certain characteristics
     *
     * @var string
     */
    protected $output;

    /**
     * A string that is used sto join together output parts
     *
     * @var string
     */
    protected string $separator;

    /**
     * Constructor
     *
     * @param integer|string $input     The input integer
     * @param array          $changeMap An array of transformation values
     * @param string         $separator A string to join output parts
     */
    protected function __construct($input, array $changeMap, $separator)
    {
        $this->changeMap = $changeMap;
        $this->input = $input;
        $this->separator = $separator;
    }

    /**
     * Make necessary transformations to the input
     *
     * @return string Transformed input
     */
    abstract protected function transform(): string;

    /**
     * Get an array whose values are digits of the input integer
     *
     * @return array
     */
    protected function inputDigitsAsArray(): array
    {
        return array_map('intval', str_split((string) $this->input));
    }

    /**
     * Validate the input
     *
     * A valid input is positive integer (may be provided as a string as well).
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function validateInput(): void
    {
        $validatedInput = filter_var(
            $this->input,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($validatedInput === false) {
            throw new InvalidArgumentException(
                sprintf("\"%s\" is not a positive integer!", $this->input)
            );
        }

        $this->input = $validatedInput;
    }

    /**
     * String representation of an object
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->output;
    }

    /**
     * Process the input value that is provided from the command line
     *
     * @param integer $argc The number of arguments passed to the script
     *                      {@link
     *                      https://www.php.net/manual/en/reserved.variables.argc.php
     *                      `$argc`}
     * @param array   $argv An array of arguments passed to the script
     *                      {@link
     *                      https://www.php.net/manual/en/reserved.variables.argv.php
     *                      `$argv`}
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function command($argc, $argv)
    {
        if ($argc) {
            echo (
                $argc < 2
                    ? "Please provide one argument that is a positive integer!"
                    : static::process($argv[1])
            ) . PHP_EOL;
        }
    }

    /**
     * Detect if the input has certain characteristics and transform it
     *
     * Transform input if it is divisible by keys of `$changeMap`;
     * output for the each of multipliers are value of `$strA`, `$strB` and
     * `$strC` respectively in ascending order.
     * Then the input should be checked if the provided integer contains any
     * key of $changeMap. For each occurence the corresponding value is added
     * to the output in the occurring order.
     * Output the provided integer as a string if it neither is divisible by
     * nor it contains keys of `$changeMap`.
     *
     * @param integer|string $input A positive integer (may be provided as a
     *                              string)
     *
     * @return string
     */
    public static function process($input): string
    {
        return (string) new static($input);
    }
}
