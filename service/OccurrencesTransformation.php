<?php declare(strict_types=1);

use \drupol\phpermutations\Generators\Combinations;

/**
 * A class to transform the input based on the found multipliers
 *
 * @author Edgars Andersons <Edgars@gaitenis.id.lv>
 */
final class OccurrencesTransformation extends Transformation
{
    public function __construct($input, $changeMap, $separator)
    {
        parent::__construct($input, $changeMap, $separator);

        $this->output = $this->transform();
    }

    /**
     * Check if the input has occurences of `$changeMap` keys
     *
     * For each occurence the corresponding `$changeMap` value is added to the
     * ouput in the occurring order.
     *
     * @return void
     */
    public function transform(): string
    {
        $output = "";

        foreach ($this->inputDigitsAsArray() as $digit) {
            if (array_key_exists($digit, $this->changeMap)) {
                $output .= ($output ? $this->separator : "")
                    . $this->changeMap[$digit];
            }
        }

        return $output;
    }
}
