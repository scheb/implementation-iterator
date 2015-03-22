scheb/implementation-iterator
=============================

This library creates an iterator for a PSR-4 compliant folder structure and searches it
for all implementations/extensions of an interface/class. Abstract implementations and the
class itself will be ignored.

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
