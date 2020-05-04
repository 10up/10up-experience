<?php

namespace ZxcvbnPhp;

use ZxcvbnPhp\Matchers\Match;

class Matcher
{
    /**
     * Get matches for a password.
     *
     * @see zxcvbn/src/matching.coffee::omnimatch
     *
     * @param string $password   Password string to match
     * @param array  $userInputs Array of values related to the user (optional)
     * @code array('Alice Smith')
     * @endcode
     *
     * @return Match[] Array of Match objects.
     */
    public function getMatches($password, array $userInputs = [])
    {
        $matches = [];
        foreach ($this->getMatchers() as $matcher) {
            $matched = $matcher::match($password, $userInputs);
            if (is_array($matched) && !empty($matched)) {
                $matches = array_merge($matches, $matched);
            }
        }

        self::usortStable($matches, [$this, 'compareMatches']);
        return $matches;
    }

    /**
     * A stable implementation of usort().
     *
     * Whether or not the sort() function in JavaScript is stable or not is implementation-defined.
     * This means it's impossible for us to match all browsers exactly, but since most browsers implement sort() using
     * a stable sorting algorithm, we'll get the highest rate of accuracy by using a stable sort in our code as well.
     *
     * This function taken from https://github.com/vanderlee/PHP-stable-sort-functions
     * Copyright © 2015-2018 Martijn van der Lee (http://martijn.vanderlee.com). MIT License applies.
     *
     * @param array $array
     * @param callable $value_compare_func
     * @return bool
     */
    public static function usortStable(array &$array, $value_compare_func)
    {
        $index = 0;
        foreach ($array as &$item) {
            $item = array($index++, $item);
        }
        $result = usort($array, function ($a, $b) use ($value_compare_func) {
            $result = call_user_func($value_compare_func, $a[1], $b[1]);
            return $result == 0 ? $a[0] - $b[0] : $result;
        });
        foreach ($array as &$item) {
            $item = $item[1];
        }
        return $result;
    }

    public static function compareMatches(Match $a, Match $b)
    {
        $beginDiff = $a->begin - $b->begin;
        if ($beginDiff) {
            return $beginDiff;
        }
        return $a->end - $b->end;
    }

    /**
     * Load available Match objects to match against a password.
     *
     * @return array Array of classes implementing MatchInterface
     */
    protected function getMatchers()
    {
        // @todo change to dynamic
        return [
            Matchers\DateMatch::class,
            Matchers\DictionaryMatch::class,
            Matchers\ReverseDictionaryMatch::class,
            Matchers\L33tMatch::class,
            Matchers\RepeatMatch::class,
            Matchers\SequenceMatch::class,
            Matchers\SpatialMatch::class,
            Matchers\YearMatch::class,
        ];
    }
}
