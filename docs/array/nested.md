# Dealing with Nested arrays

`FastFrame\Utility\NestedArrayHelper` provides methods for interacting with nested, or multi-dimensional, PHP arrays.

When there is a `$key` parameter to one of the functions, it may be in the following format

  * a string in dotted notation `some.where.over.the.rainbow`
  * an array

### get

Returns the value for the key.

Search by string
```php
$ary = [
    'some' => [
            'where' => [
                'over' => [
                    'the' => [
                    '   rainbow' => 'blue birds fly'
                    ]
                ]
            ]
        ]
    ];

FastFrame\Utility\NestedArrayHelper::get($ary, 'some.where.over.the.rainbow');
//= blue birds fly
```

You can also use an array
```php
$ary = [
    'some' => [
        'where' => [
            'over' => [
                'the' => [
                    'rainbow' => 'blue birds fly'
                ]
            ]
        ]
    ]
];

FastFrame\Utility\NestedArrayHelper::get($ary, ['some','where','over','the']);
//= array('rainbow' => 'blue birds fly')
```

Non-existent values return null
```php
$ary = [
    'rainbow' => [
        'over' => 'road
    ]
];

FastFrame\Utility\NestedArrayHelper::get($ary, 'some.where.over.the.rainbow');
//= null
```

An alternate value can be returned when the key doesn't exist
```php
$ary = [
    'rainbow' => [
        'over' => 'road
    ]
];

FastFrame\Utility\NestedArrayHelper::get($ary, 'some.where.over.the.rainbow', []);
//= array()
```

### set

Sets the value for the key

```php
$ary = [
    'rainbow' => [
        'over' => 'road'
    ]
];

FastFrame\Utility\NestedArrayHelper::set($ary, 'rainbow.the', []);
/*= array(
 *    'rainbow' => array(
 *        'over' => 'road',
 *        'the'  => array()
 *    )
 *  )
 */
```

### has

Returns whether or not the array has the given key.

*Note* If you plan to use the value at the given key, it is recommended to use the [NestedArray::get()](#get) method instead.

Returns true if the array has the key
```php
$ary = [
    'some' => [
        'where' => [
            'over' => [
                'the' => [
                    'rainbow' => 'blue birds fly'
                ]
            ]
        ]
    ]
];

FastFrame\Utility\NestedArrayHelper::has($ary, 'some.where.over');
//= true
```

Returns true if the array has the key
```php
$ary = [
    'some' => [
        'where' => [
            'there' => 'hey'
        ]
    ]
];

FastFrame\Utility\NestedArrayHelper::has($ary, 'some.where.over');
//= false
```

### deepMerge

Recursively merges the arrays, similar to array_merge_recursive, but tries
to maintain existing arrays.

The first array is the base for comparison on the merge.

### expand

Expands the array from dotted notation

```php
$ary = [
    'some.where.over.the.rainbow' => 'blue birds fly',
    'boom.to'                     => 'square'
];

FastFrame\Utility\NestedArrayHelper::expand($ary);
/*= array(
 *  'some' => array(
 *    'over' => array(
 *      'the' => array(
 *        'rainbow' => 'blue birds fly,
 *      )
 *    )
 *  ),
 *  'boom' => array(
 *    'to' => 'square'
 *  )
 *)
 */
```

### compress

Compresses the array into dotted notation

```php
$ary = [
    'some'  => [
        'where' => [
            'else' => 'something booms',
            'over' => [
                'the' => [
                    'wagon'   => 'wheel',
                    'rainbow' => 'blue birds fly'
                ]
            ]
        ]
    ],
    'boom' => [
        'to' => 'square'
    ]
];

FastFrame\Utility\NestedArrayHelper::expand($ary);
/*= array(
 *  'some.where.over.the.rainbow' => 'blue birds fly',
 *  'some.where.over.the.wagon'   => 'wheel',
 *  'some.where.else'             => 'something booms',
 *  'boom.to'                     => 'square'
 *)
 */
```