<?php
namespace Scheb\tests;

use Scheb\ImplementationIterator;

class ImplementationIteratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $instanceOf
     *
     * @return ImplementationIterator
     */
    public function createIterator($instanceOf)
    {
        return new ImplementationIterator(__DIR__ . DIRECTORY_SEPARATOR . 'Fixtures', 'Scheb\\Tests\\Fixtures', $instanceOf);
    }

    /**
     * Return array of elements from iterator
     *
     * @param \Iterator $iterator
     *
     * @return array
     */
    private function getArray(\Iterator $iterator)
    {
        $elements = array();
        foreach ($iterator as $element) {
            $elements[] = $element;
        }

        return $elements;
    }

    public function testInterfaceReturnsImplementations()
    {
        $iterator = $this->createIterator('Scheb\\Tests\\Fixtures\\AInterface');
        $this->assertImplementations($iterator);
    }

    public function testAbstractClassReturnsImplementations()
    {
        $iterator = $this->createIterator('Scheb\\Tests\\Fixtures\\AbstractAImplementation');
        $this->assertImplementations($iterator);
    }

    public function testClassReturnsExtensions()
    {
        $iterator = $this->createIterator('Scheb\\Tests\\Fixtures\\AImplementation');
        $array = $this->getArray($iterator);
        $this->assertContains('Scheb\\Tests\\Fixtures\\AImplementationExtension', $array);
    }

    private function assertImplementations($iterator)
    {
        $array = $this->getArray($iterator);
        $this->assertContains('Scheb\\Tests\\Fixtures\\AImplementation', $array);
        $this->assertContains('Scheb\\Tests\\Fixtures\\AImplementationExtension', $array);
        $this->assertContains('Scheb\\Tests\\Fixtures\\SubNamespace\\AImplementation', $array);
        $this->assertContains('Scheb\\Tests\\Fixtures\\SubNamespace\\SubSubNamespace\\AImplementation', $array);
    }
}
