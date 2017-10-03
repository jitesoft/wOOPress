<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Exception;
use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\OptionInterface;
use Jitesoft\wOOPress\Contracts\OptionServiceInterface;
use Jitesoft\wOOPress\Option;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use OutOfBoundsException;
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
                $this->assertEquals("optionname", $option);
                $this->assertEquals("abc", $value);
                $this->assertEquals("", $deprecated);
                $this->assertEquals("yes", $autoload);
                return true;
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
                $this->assertInternalType('string', $option);
                $this->assertEquals("optionname", $option);
                $this->assertEquals("abc", $value);
                $this->assertEquals("", $deprecated);
                $this->assertEquals("yes", $autoload);
                return true;
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

    public function testAddFailInvalidOptionType() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument $option has to be either a string or derive from OptionInterface.');
        $this->service->add(false);
    }

    public function testAddFailOutOfBounds() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("strlen")
            ->setFunction(function($val) {
                $this->assertEquals("whatever", $val);
                return 2**32 + 1;
            })
            ->build();
        $mock->enable();

        try {
            $this->service->add("test", "whatever");
        } catch (Exception $e) {
            $this->assertInstanceOf(OutOfBoundsException::class, $e);
            $this->assertEquals("Invalid value size, maximum size is 2^32 bytes.", $e->getMessage());
        }
        $mock->disable();
    }

    public function testAddFailOptionExists() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("add_option")
            ->setFunction(function() {
                return false;
            })
            ->build();
        $mock->enable();

        $this->assertNull($this->service->add("option", "abc"));
        $mock->disable();
    }

    public function testRemoveSuccessWithString() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("delete_option")
            ->setFunction(function($optionName) {
                $this->assertEquals("option", $optionName);
                return true;
            })
            ->build();
        $mock->enable();
        $this->assertTrue($this->service->remove("option"));
        $mock->disable();
    }

    public function testRemoveSuccessWithOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("delete_option")
            ->setFunction(function($optionName) {
                $this->assertEquals("option", $optionName);
                return true;
            })
            ->build();
        $mock->enable();
        $this->assertTrue($this->service->remove(new Option("option", "abc")));
        $mock->disable();
    }

    public function testRemoveFailNoOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("delete_option")
            ->setFunction(function($optionName) {
                $this->assertEquals("option", $optionName);
                return false; // Pretend option exists and delete_option returns false!
            })
            ->build();
        $mock->enable();
        $this->assertFalse($this->service->remove("option"));
        $mock->disable();

    }

    public function testRemoveInvalidOptionType() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument $option has to be either a string or derive from OptionInterface.');
        $this->service->remove(true);
    }

    public function testGetSuccess() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("get_option")
            ->setFunction(function($optionName) {
                $this->assertEquals("option", $optionName);
                // Pretend it exists and the value of the option is "success"
                return "success";
            })
            ->build();
        $mock->enable();
        $out = $this->service->get("option");
        $mock->disable();

        $this->assertInstanceOf(OptionInterface::class, $out);

        $this->assertFalse($out->isDirty());
        $this->assertEquals("option", $out->getName());
        $this->assertEquals("success", $out->getValue());
    }

    public function testGetFailNoOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("get_option")
            ->setFunction(function($optionName, $default) {
                $this->assertEquals("option", $optionName);
                return $default;
            })
            ->build();
        $mock->enable();
        $this->assertNull($this->service->get("option"));
        $mock->disable();
    }

    public function testUpdateSuccessWithString() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("update_option")
            ->setFunction(function($optionName, $value) {
                $this->assertEquals("option", $optionName);
                $this->assertEquals("value!", $value);
                return true;
            })
            ->build();
        $mock->enable();
        $out = $this->service->update("option", "value!");
        $this->assertFalse($out->isDirty());
        $this->assertEquals("option", $out->getName());
        $this->assertEquals("value!", $out->getValue());
        $mock->disable();
    }

    public function testUpdateSuccessWithOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("update_option")
            ->setFunction(function($optionName, $value) {
                $this->assertEquals("option", $optionName);
                $this->assertEquals("value!", $value);
                return true;
            })
            ->build();
        $mock->enable();
        $out = $this->service->update(new Option("option", "value!"));
        $this->assertFalse($out->isDirty());
        $this->assertEquals("option", $out->getName());
        $this->assertEquals("value!", $out->getValue());
        $mock->disable();
    }


    public function testUpdateFailInvalidOptionType() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument $option has to be either a string or derive from OptionInterface.');
        $this->service->update(true);
    }

    public function testUpdateFailOutOfBounds() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("strlen")
            ->setFunction(function($value) {
                $this->assertEquals("whatever", $value);
                return ((2**32) + 5);
            })
            ->build();
        $mock->enable();

        try {
            $this->service->update("test", "whatever");
        } catch (Exception $e) {
            $this->assertInstanceOf(OutOfBoundsException::class, $e);
            $this->assertEquals("Invalid value size, maximum size is 2^32 bytes.", $e->getMessage());
        }
        $mock->disable();
    }

    public function testUpdateFailNoOption() {
        $mock = (new MockBuilder())
            ->setNamespace($this->namespace)
            ->setName("update_option")
            ->setFunction(function($optionName, $optionValue) {
                $this->assertEquals("test", $optionName);
                $this->assertEquals("whatever", $optionValue);
                return false;
            })
            ->build();
        $mock->enable();
        $out = $this->service->update("test", "whatever");
        $mock->disable();

        $this->assertNull($out);
    }

}
