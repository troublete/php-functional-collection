<?php
namespace TryPhp;

/**
 * Function that returns a functional collection object
 * @param  array  $values
 * @return FunctionalCollection
 */
function collection(array $values = []): FunctionalCollection {
    return new FunctionalCollection($values);
}
