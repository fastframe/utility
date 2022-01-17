# Dealing with Basic arrays

`FastFrame\Utility\ArrayHelper` provides methods for interacting with basic PHP arrays.

### indexPull

Returns a list of key-values from a list of arrays

`ArrayHelper::indexPull($arys, ['id', 'name']);`

Written similarly in PHP
```php
$values = [];
foreach ($keys as $key) {
    foreach ($arys as $idx => $ary) {
        if (array_key_exists($key, $ary)) {
            $values[$idx][$key] = $ary[$key];
        }    
    }
}
```

### isAssoc

**NOTE** This will treat non-sequential integer arrays as non-associative. Use `isHash` if you need to test for
non-sequential integers.

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

### isHash

Determine if the array is a hash or not:

Arrays with only integer keys are not considered a hash
```php
$ary = ['a','b','c','d'];

ArrayHelper::isHash($ary);
//= false
```

Arrays with a mix of integer/string keys are considered a hash
```php
$ary = ['a','b','c','key' => 'd'];

ArrayHelper::isHash($ary);
//= true
```

Arrays with non-sequential integers are considered a hash
```php
$ary = [1 => 'a', 3 => 'b'];

ArrayHelper::isHash($ary);
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
    'ary' => 'a',
    'bry' => 1
];

FastFrame\Utility\ArrayHelper::keyValue($ary, 'cry', 'some');
//= 'some'
```

### methodPull

Calls a method on a list of objects.

`ArrayHelper::methodPull($objects, 'id');`

Written similarly in PHP
```php
$values = [];
foreach ($objects as $key => $object) {
    $values[$key] = $object->id();
}
```

#### Key method

This third argument allows the key to be set by a method call

`ArrayHelper::methodPull($objects, 'name', 'id');`

Written similarly in PHP
```php
$values = [];
foreach ($objects as $key => $object) {
    $values[$object->id()] = $object->name();
}
```

#### Preserving objects

You can pass in `null` as the second argument to have the object returned

`ArrayHelper::methodPull($objects, null, 'id');`


### pluck

Plucks the requested key from the arrays into an array

`ArrayHelper::pluck($ary,'name',);`

### propertyPull

Returns the property from a list of objects

`ArrayHelper::propertyPull($objects, 'id');`

Written similarly in PHP
```php
$values = [];
foreach ($objects as $key => $object) {
    $values[$key] = $object->id;
}
```

#### Key property

This third argument allows the key to be set by using a nother property

`ArrayHelper::propertyPull($objects, 'name', 'id');`

Written similarly in PHP
```php
$values = [];
foreach ($objects as $key => $object) {
    $values[$object->id] = $object->name;
}
```

#### Preserving objects

You can pass in `null` as the second argument to have the object returned.

`ArrayHelper::propertyPull($objects, null, 'id');`

### pullKeys

Pulls the keys from the array

```php
$ary = [
    'ff_ua_one' => 'value #1',
    'ff_ua_two' => 'value #2',
    'ff_ub_one' => 1,
    'ff_ub_two' => 2,
];

ArrayHelper::pullKeys($ary, 'ff_ua_');
//= array('ff_ua_one' => 'value #1', 'ff_ua_two' => 'value #2');
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

### resolveSplat

Returns an array representing the splat, or just the first argument if only one entry in the splat.

```php
	function splatted(...$args) {
		return ArrayHelper::resolveSplat($args);
	}
	 
	splatted('test', 'test', 'test'); //= ['test', 'test', 'test']
	splatted(['test','test','test']); //= ['test', 'test', 'test']
```

### toComment

Formats the array into `key=value;` pairings

```php
$ary = [
    'one' => 'value #1',
    'two' => 'value #2',
];

FastFrame\Utility\ArrayHelper::toComment($ary);
//= 'one=value #1; two=value #2'
```

Using a different separator between keys & values

```php
ArrayHelper::toComment($ary, ': ');
//= 'one: value#1; two: value #2'
```

Using a different separator between key/value pairs
```php
ArrayHelper::toComment($ary, '=', '|');
//= 'one=value#1|two=value #2'
```