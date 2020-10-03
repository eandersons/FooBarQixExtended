<?php declare(strict_types=1);

use \drupol\phpermutations\Generators\Combinations;

/**
 * A class to transform the input based on the found multipliers
 *
 * @author Edgars Andersons <Edgars@gaitenis.id.lv>
 */
final class MultipliersTransformation extends Transformation
{
    public function __construct($input, $changeMap, $separator)
    {
        parent::__construct($input, $changeMap, $separator);

        $this->output = $this->transform();
    }

    /**
     * Check if the input integer is divisible by any `$changeMap` key
     *
     * @return string
     */
    public function transform(): string
    {
        $detectionValues = array_keys($this->changeMap);
        $output = (string) $this->input;

        // Get combinations of `$changeMap` keys of length starting from count
        // of items in the array.
        for ($count = count($this->changeMap); $count > 0; $count --) {
            $combinations = new Combinations($detectionValues, $count);

            foreach ($combinations->generator() as $combination) {
                // Check if the input is divisible by the product of the
                // current combination's members.
                if ($this->input % array_product($combination) === 0) {
                    // If the input is divisible by all members of
                    // `$combination`, output is generated from the values of
                    // `$changeMap` whose keys are the same as the numbers in
                    // the current combination.
                    // Output parts are glued together using `$separator` in
                    // the order they are provided in `$changeMap`.
                    $output = implode(
                        $this->separator,
                        array_intersect_key(
                            $this->changeMap,
                            array_combine($combination, $combination)
                        )
                    );

                    // The largest possible combination of multipliers has been
                    // found, stop both loops.
                    break 2;
                }
            }
        }

        return $output;
    }
}
