# Function Collection
> Small functional multi-purpose collection

[![Build Status](https://travis-ci.org/troublete/functional-collection.svg?branch=master)](https://travis-ci.org/troublete/functional-collection)

### Usage

```
<?php
use function TryPhp\collection;
$collection = collection([1, 2, 3]);

// or

use TryPhp\FuntionalCollection;
$collection = new FunctionalCollection([1, 2, 3]);
```

### API

#### `of(...$values)`

Setup new collection with provided values. **immutable**

#### `map(callable $mapFunc)`

Method which will be applied to each element in the value set of the collection. **mutable**

#### `filter(callable $predicate)`

Method which will create a collection copy and filter values by function (returning `false` filters value out). **immutable**

#### `generator(): \Generator`

Returns `Generator` over value set.

#### `extract(): array`

Will return value set.

#### `extend(callable $func)`

Method will call the provided `Closure` and inject set values. Returned values will be put into immutable `FunctionalCollection` and returned. **immutable**

#### `concat(FunctionaCollection $collection)`

Method will combine to value sets of two collection and return a new one with set values. **immutable**

#### `chain(callable $map)`

Method which will apply `$map` to every element in the value collection and will reduce the result and add it to the overall value set and create a immutable new collection with it. **immutable**

#### `reduce(callable $func = null)`

Method to reduce the value set (value by value) according to the result of calling `$func` and return the created accumulated value. If `$func` is `null` the raw value is used and added to the accumulator.

### License

GPL-2.0 © 2018 Willi Eßer
