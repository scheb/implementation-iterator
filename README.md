scheb/implementation-iterator
=============================

This library creates an iterator for a PSR-4 compliant folder structure and searches it
for all implementations/extensions of an interface/class. Abstract implementations and the
class itself will be ignored.

[![Build Status](https://travis-ci.org/scheb/implementation-iterator.svg?branch=master)](https://travis-ci.org/scheb/implementation-iterator)
[![HHVM Status](http://hhvm.h4cc.de/badge/scheb/implementation-iterator.png)](http://hhvm.h4cc.de/package/scheb/implementation-iterator)
[![Coverage Status](https://coveralls.io/repos/scheb/implementation-iterator/badge.png?branch=master)](https://coveralls.io/r/scheb/implementation-iterator?branch=master)
[![Latest Stable Version](https://poser.pugx.org/scheb/implementation-iterator/v/stable.svg)](https://packagist.org/packages/scheb/implementation-iterator)
[![License](https://poser.pugx.org/scheb/implementation-iterator/license.svg)](https://packagist.org/packages/scheb/implementation-iterator)

Usage
-----

```php
$iterator = new \Scheb\ImplementationIterator("src/", "Root\Namespace", "Some\Class\Or\Interface");
foreach ($iterator as $className) {
    echo $className . "\n";
}
```

Will return something like:

```
Root\Namespace\Foo\Implementation
Root\Namespace\Bar\OtherImplementation
...
```

Alternatively you can also have an iterator, which returns ReflectionClass instances instead of
the class name:

```php
$iterator = new \Scheb\ImplementationReflectionIterator("src/", "Root\Namespace", "Some\Class\Or\Interface");
foreach ($iterator as $reflection) {
    echo $reflection->getName() . "\n";
}
```

License
-------
This library is available under the [MIT license](LICENSE).
