# FastFrame Utilities

Utilities used by other FastFrame components

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fastframe/utility.svg?style=flat-square)](https://packagist.org/packages/fastframe/utility)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://github.com/fastframe/utility/actions/workflows/tests.yml/badge.svg)](https://github.com/fastframe/utility/actions/workflows/tests.yml)
[![Code Climate](https://codeclimate.com/github/fastframe/utility/badges/gpa.svg)](https://codeclimate.com/github/fastframe/utility)
[![Test Coverage](https://codeclimate.com/github/fastframe/utility/badges/coverage.svg)](https://codeclimate.com/github/fastframe/utility/coverage)

## Install

Via Composer
```sh
$ composer require fastframe/utility
```

## Usage

The following classes are provided by this library:

  * [FastFrame\Utility\ArrayHelper](./docs/array/basic.md) Contains basic array handling utilities
  * [FastFrame\Utility\HistorySet](./docs/collection/history_set.md) Contains a Set implementation that tracks additions and removals
  * [FastFrame\Utility\NestedArrayHelper](./docs/array/nested.md) Contains nested array handling utilities
  * [FastFrame\Utility\PriorityList](./docs/priority_list.md) Implementation of a priority queue that doesn't remove the items on iteration
  * [FastFrame\Utility\Set](./docs/collection/set.md) Contains a Set implementation
  * [FastFrame\Utility\StringHelper](./docs/strings.md) Contains string handling utilities

For general usage instructions, please read the documentation [here](./docs/index.md).

## Quality

This package attempts to comply with [PSR-1][] and [PSR-4][]. If you notice compliance oversights, please send a 
patch via pull request.

## Support

If you believe you have found a bug, please report it using the [Github issue tracker](https://github.com/fastframe/utility/issues), or better yet, 
fork the library and submit a pull request.

## Testing

```sh
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [David Lundgren](https://github.com/dlundgren)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md