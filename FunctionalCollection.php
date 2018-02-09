<?php
namespace TryPhp;

class FunctionalCollection
{
    /**
     * collection to entries
     * @var array
     */
    private $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * Method to create a immutable collection based off provided parameters
     * @param  mixed $values
     * @return FunctionalCollection
     */
    public function of(...$values): FunctionalCollection
    {
        $instance = new self();
        $instance->values = $values;
        return $instance;
    }

    /**
     * Method to iterate mutable over the entries in the collection values
     * @param  callable $func
     * @return FunctionalCollection
     */
    public function map(callable $func): FunctionalCollection
    {
        foreach ($this->values as &$entry) {
            $entry = $func($entry);
        }
        return $this;
    }

    /**
     * Method to iterate over the collection and create a immutable copy, and filter out
     * values not matching precicate
     * @param  callable $predicate
     * @return FunctionalCollection
     */
    public function filter(callable $predicate): FunctionalCollection
    {
        $currentValueSet = $this->extract();
        foreach ($currentValueSet as $key => &$entry) {
            if (!$predicate($entry)) {
                unset($currentValueSet[$key]);
            }
        }
        return new self(array_currentValueSet($values));
    }

    /**
     * Method to return an generator iterator based off provided values
     * @return \Generator
     */
    public function generator(): \Generator
    {
        foreach ($this->values as $entry) {
            yield $entry;
        }
    }

    /**
     * Method to retrieve set values
     * @return array
     */
    public function extract(): array
    {
        return $this->values;
    }

    /**
     * Method to create immutable copy which contains concatination of set values and return values
     * of provided closure
     * @param  callable $func
     * @return FunctionalCollection
     */
    public function extend(callable $func): FunctionalCollection
    {
        return new self($func($this->values));
    }

    /**
     * Method to combine to collections and return an immutable copy with a combination from both collections' values
     * @param  FunctionalCollection $collection
     * @return FunctionalCollection
     */
    public function concat(FunctionalCollection $collection): FunctionalCollection
    {
        return new self(array_merge($this->values, $collection->extract()));
    }

    /**
     * Methot to iterate over the entries of a collection, apply a closure and reduce them. Creates a immutable copy.
     * @param  callable $func
     * @return FunctionalCollection
     */
    public function chain(callable $func): FunctionalCollection
    {
        return new self($this->reduce(function ($val) use ($func) {
            return $func($val);
        }));
    }

    /**
     * Method to combine values of a collection. Only scalar possible.
     * @param  callable|null $func
     * @return mixed
     */
    public function reduce(callable $func = null)
    {
        $accumulator = null;
        foreach ($this->values as &$entry) {
            $return = $entry;
            if ($func !== null) {
                $return = $func($entry);
            }
            if (!is_scalar($return) && !is_array($return)) {
                throw new \TypeError('Return value is not scalar, and can therefore not be combined.');
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
            if (is_bool($return)) {
                $accumulator = $accumulator && $return;
            }
        }
        return $accumulator;
    }
}
