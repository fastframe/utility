# Dealing with Basic arrays

`FastFrame\Utility\ArrayHelper` provides methods for interacting with basic PHP arrays.

### isAssoc

Determine if the array is associative or not:

Arrays with only integer keys are not considered associative
```php
$ary = ['a','b','c','d'];

FastFrame\Utility\ArrayHelper::isAssoc($ary);
//= false
```

Arrays with a mix of integer/string keys are considered associative
```php
$ary = ['a','b','c','key' => 'd'];

FastFrame\Utility\ArrayHelper::isAssoc($ary);
//= true
```

### keyValue

Retrieve a value from the array by key:

Non-existent keys return null
```php
$ary = [
    'ary'=>'a'
    'bry'=>1
];

FastFrame\Utility\ArrayHelper::keyValue($ary, 'cry');
//= null
```

A default value can be return instead of null
```php
$ary = [
    'ary'=>'a'
    'bry'=>1
];

FastFrame\Utility\ArrayHelper::keyValue($ary, 'cry', 'some');
//= 'some'
```

### pullPrefix

Pulls the values from the array with a key given a prefix:

```php
$ary = [
    'ff_ua_one' => 'value #1',
    'ff_ua_two' => 'value #2',
    'ff_ub_one' => 1,
    'ff_ub_two' => 2,
];

FastFrame\Utility\ArrayHelper::pullPrefix($ary, 'ff_ua_');
//= array('ff_ua_one' => 'value #1', 'ff_ua_two' => 'value #2');
```

Strip the prefix off of the key names
```php
$ary = [
    'ff_ua_one' => 'value #1',
    'ff_ua_two' => 'value #2',
    'ff_ub_one' => 1,
    'ff_ub_two' => 2,
];

FastFrame\Utility\ArrayHelper::pullPrefix($ary, 'ff_ua_', true);
//= array('one' => 'value #1', 'two' => 'value #2');
```

### pushPrefix

Pushes the values into an array with a key given a prefix:

```php
$ary = [
    'one' => 'value #1',
    'two' => 'value #2',
];

FastFrame\Utility\ArrayHelper::pushPrefix($ary, 'ff_ua_');
//= array('ff_ua_one' => 'value #1', 'ff_ua_two' => 'value #2');
```