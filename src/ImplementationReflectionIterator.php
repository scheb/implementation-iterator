<?php
namespace Scheb;

class ImplementationReflectionIterator implements \Iterator
{

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $classOrInterface;

    /**
     * @var boolean
     */
    private $isInterface;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * Create an iterator on a directory to search for classes implementing an interface or being implementation of a class
     *
     * @param string $directory
     * @param string $namespace
     * @param string $classOrInterface
     */
    public function __construct($directory, $namespace, $classOrInterface)
    {
        $this->directory = realpath($directory);
        $this->classOrInterface = $classOrInterface;
        $reflection = new \ReflectionClass($classOrInterface);
        $this->isInterface = $reflection->isInterface();
        $this->namespace = $namespace;
        $self = $this;

        // Recursive iterator on directory
        $directoryIterator = new \RecursiveDirectoryIterator($this->directory);

        // Filter PHP files
        $regexIterator = new \RecursiveCallbackFilterIterator($directoryIterator, function ($current, $key, \RecursiveDirectoryIterator $iterator) {
            return $iterator->hasChildren() || substr($iterator->getFilename(), -4) === '.php';
        });

        // Flatten recursive iterator
        $flatIterator = new \RecursiveIteratorIterator($regexIterator);

        // Create reflection from it
        $reflectionMapper = new MapIterator($flatIterator, function (\SplFileInfo $file) use ($self) {
            return $self->createReflection($file);
        });

        // Filter implementations
        $reflectionFilter = new \CallbackFilterIterator($reflectionMapper, function ($reflection) use ($self) {
            if ($reflection instanceof \ReflectionClass) {
                return $self->isImplementation($reflection);
            }

            return false;
        });

        $this->iterator = $reflectionFilter;
    }

    /**
     * Create reflection from file path
     *
     * @param \SplFileInfo $file
     *
     * @return null|\ReflectionClass
     */
    public function createReflection(\SplFileInfo $file)
    {
        $filePath = substr($file->getPathname(), strlen($this->directory), -4);
        $className = $this->namespace . str_replace('/', '\\', $filePath);
        try {
            return new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
        }

        return null;
    }

    /**
     * Check if reflection implements/extends the class
     *
     * @return bool
     */
    public function isImplementation(\ReflectionClass $reflection)
    {
        // We don't want abstract classes or interfaces
        if ($reflection->isAbstract() || $reflection->isInterface()) {
            return false;
        }

        if ($this->isInterface && !$reflection->implementsInterface($this->classOrInterface)) {
            return false; // Not implements the interface we're looking for
        } elseif (!$reflection->isSubclassOf($this->classOrInterface)) {
            return false; // Not is or extends the class we're looking for
        }

        return true;
    }

    public function current()
    {
        return $this->iterator->current();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }
}
