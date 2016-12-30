# Dealing with Strings

`FastFrame\Utility\StringHelper` provides methods for interacting strings.

### contains($needles, $haystack)

Returns True if any of the needles are in the haystack, False otherwise.

The `$needle` argument may be a string or an array of strings to check.

```php

StringHelper::contains('super', 'whacko'); //= false
StringHelper::contains('world', 'hello world!'); //= true

StringHelper::contains(['super','world'], 'hello world!'); //= true
```

### firstPosition($needles, $haystack)

Returns True if any of the needles are in the haystack, False otherwise.

The `$needle` argument may be a string or an array of strings to check.

```php

StringHelper::firstPosition('super', 'whacko'); //= null
StringHelper::firstPosition('world', 'hello world!'); //= 6 

StringHelper::firstPosition(['super','world'], 'hello world!'); //= 6
```