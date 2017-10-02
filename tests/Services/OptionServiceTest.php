<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Jitesoft\wOOPress\Contracts\OptionInterface;
use Jitesoft\wOOPress\Contracts\OptionServiceInterface;
use Jitesoft\wOOPress\Option;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use phpmock\MockBuilder;

class OptionServiceTest extends AbstractTestCase {

    /** @var OptionServiceInterface */
    private $service;

    /** @var string */
    private $namespace;

    protected function setUp() {
        parent::setUp();

        $this->service   = DependencyContainer::get(OptionServiceInterface::class);
        $this->namespace = dirname(get_class($this->service));
    }

    public function testAddSuccessWithString() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("add_option")
            ->setFunction(function($option, $value, $deprecated, $autoload) {
                $this->assertInternalType('string', $option);
                $this->assertEquals("optionname", $value);
                $this->assertEquals("abc", $value);
                $this->assertFalse($deprecated);
                $this->assertTrue($autoload);
            })
            ->build();
        $mock->enable();

        $out = $this->service->add("optionname", "abc", true);
        $mock->disable();

        $this->assertInstanceOf(OptionInterface::class, $out);
        $this->assertEquals("optionname", $out->getName());
        $this->assertEquals("abc", $out->getValue());
        $this->assertFalse($out->isDirty());
    }

    public function testAddSuccessWithOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("add_option")
            ->setFunction(function($option, $value, $deprecated, $autoload) {
                $this->assertInstanceOf(OptionInterface::class, $option);
                $this->assertEquals("optionname", $value);
                $this->assertEquals("abc", $value);
                $this->assertFalse($deprecated);
                $this->assertTrue($autoload);
            })
            ->build();
        $mock->enable();

        $option = new Option("optionname", "abc", true);
        $this->assertTrue($option->isDirty());

        $out = $this->service->add($option);
        $mock->disable();

        $this->assertInstanceOf(OptionInterface::class, $out);
        $this->assertEquals("optionname", $out->getName());
        $this->assertEquals("abc", $out->getValue());
        $this->assertFalse($out->isDirty());
    }

    public function testAddFailInvalidOptionType() {}
    public function testAddFailOutOfBounds() {}
    public function testAddFailOptionExists() {}

    public function testRemoveSuccess() {}
    public function testRemoveFailNoOption() {}
    public function testRemoveInvalidOptionType() {}

    public function testGetSuccess() {}
    public function testGetFailNoOption() {}

    public function testUpdateSuccess() {}
    public function testUpdateFailInvalidOptionType() {}
    public function testUpdateFailOutOfBounds() {}
    public function testUpdateFailNoOption() {}
}
