<?php
require_once __DIR__ . '/vendor/autoload.php';
use function TryPhp\collection;
use function Tapping\{test, todo};

test('Setting some scalar values with `of` should create a immutable collection with said values.', function ($t) {
    $collection = collection()->of(1, '2', true);
    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == [1,2,1], true);
});

test('Running `map` should mutable change the values of the collection.', function ($t) {
    $collection = collection([1,2,3])->map(function ($val) {
        return $val ** 2;
    });

    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == [1,4,9], true);
});

test('Running `filter` should create an immutable copy and filter out values.', function ($t) {
    $collection = collection([1,2,3]);
    $filteredCollection = $collection->filter(function ($val) {
        return $val % 2;
    });

    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == [1,2,3], true);
    $t->is($values->getValue($filteredCollection) == [1,3], true);
});

test('Running `extract` should return set values.', function ($t) {
    $collection = collection([1,2,3]);

    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == $collection->extract(), true);
});

test('Running `concat` should concatinate two values.', function ($t) {
    $collection = collection([1,2,3])->concat(collection([4,5]));

    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == [1,2,3,4,5], true);
});

test('Running `chain` should chain elements in reduced form.', function ($t) {
    $collection = collection(['unicorn,otter','dog,cat'])->chain(function ($val) {
        return explode(',', $val);
    });

    $reflectionClass = new ReflectionClass(get_class($collection));
    $values = $reflectionClass->getProperty('values');
    $values->setAccessible(true);

    $t->is($values->getValue($collection) == ['unicorn', 'otter', 'dog', 'cat'], true);
});
