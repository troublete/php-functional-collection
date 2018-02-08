<?php
namespace TryPhp;

/**
 * Function that returns a functional collection object
 * @param  array  $values
 * @return class@anonymouse
 */
function collection(array $values = []) {
    return new class($values) {
        public $values;

        public function __construct(array $values = []) {
            $this->values = $values;
        }

        /**
         * Method to create a immutable collection based off provided parameters
         * @param  mixed $values
         * @return object
         */
        public function of(...$values) {
            $instance = new self();
            $instance->values = $values;
            return $instance;
        }

        /**
         * Method to iterate mutable over the entries in the collection values
         * @param  callable $func
         * @return $this
         */
        public function map(callable $func) {
            foreach ($this->values as &$entry) {
                $entry = $func($entry);
            }
            return $this;
        }

        /**
         * Method to iterate over the collection and create a immutable copy, and filter out
         * values not matching precicate
         * @param  callable $predicate
         * @return object
         */
        public function filter(callable $predicate) {
            $clone = clone $this;
            foreach ($clone->values as &$entry) {
                if (!$predicate($entry)) {
                    unset($entry);
                }
            }
            return $clone;
        }

        /**
         * Method to return an generator iterator based off provided values
         * @return \Generator
         */
        public function generator(): \Generator {
            foreach ($this->values as $entry) {
                yield $entry;
            }
        }

        /**
         * Method to retrieve set values
         * @return [type] [description]
         */
        public function extract(): array {
            return $this->values;
        }

        /**
         * Method to create immutable copy which contains concatination of set values and return values
         * of provided closure
         * @param  callable $func
         * @return object
         */
        public function extend(callable $func) {
            return new self($func($this->values));
        }

        /**
         * Method to combine to collections and return an immutable copy with a combination from both collections' values
         * @param  object $collection
         * @return object
         */
        public function concat($collection) {
            return new self(array_merge($this->values, $collection->extract()));
        }

        /**
         * Methot to iterate over the entries of a collection, apply a closure and reduce them. Creates a immutable copy.
         * @param  callable $func
         * @return object
         */
        public function chain(callable $func) {
            return new self($this->reduce(function ($val) use ($func) {
                return $func($val);
            }));
        }

        /**
         * Method to combine values of a collection. Only scalar possible.
         * @param  callable|null $func
         * @return [type]              [description]
         */
        public function reduce(callable $func = null) {
            $accumulator = null;
            foreach ($this->values as &$entry) {
                $return = $entry;
                if ($func !== null) {
                    $return = $func($entry);
                }
                if (!is_scalar($return)) {
                    throw new TypeError('Return value is not scalar, and can therefore not be combined.');
                }
                if (is_string($return)) {
                    $accumulator .= $return;
                    continue;
                }
                if (is_numeric($return)) {
                    $accumulator += $return;
                    continue;
                }
                if (is_array($return)) {
                    $accumulator = array_merge([], (array) $accumulator, $return);
                }
            }
            return $accumulator;
        }
    };
}
