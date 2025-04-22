# Dealing with Basic arrays

`FastFrame\Utility\Set` provides methods for interacting with a Set.

This implementation uses `hash('md5', serialize($element))` as a key.

### add($element)

Adds an element to the set

```php
$set->add($something);
```

### clear

Clears all elements from the set.

```php
$set->clear();
```

### contains

Whether the set contains the element

```php
$set->contains($something);
```

### count

Number of elements in the set

```php
$set->count();
```

### discard($element)

Removes an element from the set.

```php
$set->discard($something);
```

### isEmpty()

Whether the set is empty

```php
$set->isEmpty();
```

### remove($element)

Removes an element from the set. Throws `KeyError` if `$element` is not in the set.

```php
$set->remove($something);
```

### values()

Returns the list of values in the set as an array

```php
$set->values();
```