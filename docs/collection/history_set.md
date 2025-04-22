# Dealing with sets that need history tracking

`FastFrame\Utility\HistorySet` implements a Set that tracks added and removed elements.

This is a port of the Python [history-set](https://pypi.org/project/history-set/).

### clear

This method will clear the data, and mark each element as removed.

```php
$set = new Set([1, 2, 3]);
$set->clear();
$set->removed(); // [1, 2, 3]
```

### `eidetic` mode

Elements added and removed will not be tracked in history.

```php
$set = new Set([1, 2, 3]);
$set->add(4);
$set->remove(4);
$set->added(); // []
$set->removed(); // []
```

If you want to track this you will need to supply `eidetic: true` when creating the `HistorySet`.

```php
$set = new Set([1, 2, 3], eidetic: true);
$set->add(4);
$set->remove(4);
$set->added(); // [4]
$set->removed(); // [4]
```

### Reset tracking

`reset()` clears the entire history, if you need to reset just the added or removed history, you will
need to supply `added: true` or `removed: true`, to reset that specific history.

```php
$set = new Set([1, 2, 3]);
$set->add(4);
$set->added(); // [4]
$set->remove(2);
$set->removed(); // [2]
$set->reset(added: true);
$set->added(); // []
```