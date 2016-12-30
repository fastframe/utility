# PriorityList

`FastFrame\Utility\PriorityList` provides an implementation of a List that maintains the items in priority.

It implements `\Iterator` and `\Countable` interfaces

## Constants

The following constants are defined and re-used from \SplPriorityQueue;

- `EXTR_PRIORITY` Returns the priority of the current item
- `EXTR_DATA` Returns the data of hte current item (default)
- `EXTR_BOTH` Returns an array `['priority' => {priority}, 'data' => {data}]`

### Inserting values

```php
$pl = new PriorityList();
$pl->insert(1, 0);
$pl->insert(2, -2);
$pl->insert(3, 1);

$vs = [];
foreach ($pl as $v) { $vs[] = $v; }
print join(",", $vs);
//= 3,1,2
```

### Removing Values

```php
$pl = new PriorityList();
$pl->insert(1, 0);
$pl->insert(2, -2);
$pl->insert(3, 1);

$pl->remove(3); //= true
$pl->insert(4, 100);

$pl->remove(4, 50); //= false (no priority 50)

$vs = [];
foreach ($pl as $v) { $vs[] = $v; }
print join(",", $vs);
//= 4,1,2
```
