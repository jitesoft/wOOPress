<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TransientServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Carbon\Carbon;
use Jitesoft\wOOPress\Contracts\TransientInterface;
use Jitesoft\wOOPress\Contracts\TransientServiceInterface;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use Jitesoft\wOOPress\Transient;
use phpmock\Mock;

class TransientServiceTest extends AbstractTestCase {

    /** @var string */
    private $namespace;
    /** @var TransientServiceInterface */
    private $service;

    protected function setUp() {
        parent::setUp();

        $this->service   = DependencyContainer::get(TransientServiceInterface::class);
        $this->namespace = (new \ReflectionClass($this->service))->getNamespaceName();
        Carbon::setTestNow(new Carbon("2017-09-30 13:37:00", "UTC"));
    }

    public function testGetSuccess() {
        $mock = new Mock($this->namespace, 'get_transient', function(string $name) {
            $this->assertEquals("test-get", $name);
            return "test-value";
        });

        $mock->enable();
        $out = $this->service->get("test-get");
        $mock->disable();

        $this->assertInstanceOf(TransientInterface::class, $out);
        $this->assertEquals("test-get", $out->getName());
        $this->assertEquals("test-value", $out->getValue());
        $this->assertNull($out->getMaxDate());
        $this->assertFalse($out->isDirty());
    }

    public function testGetFailDefaultValue() {
        $mock = new Mock($this->namespace, 'get_transient', function(string $name) {
            $this->assertEquals("test-get", $name);
            return false;
        });

        $mock->enable();
        $out = $this->service->get("test-get", "test-default-value");
        $mock->disable();

        $this->assertInstanceOf(TransientInterface::class, $out);
        $this->assertEquals("test-get", $out->getName());
        $this->assertEquals("test-default-value", $out->getValue());
        $this->assertNull( $out->getMaxDate());
        $this->assertTrue($out->isDirty());
    }

    public function testGetFailNullValue() {
        $mock = new Mock($this->namespace, 'get_transient', function(string $name) {
            $this->assertEquals("test-get", $name);
            return false;
        });

        $mock->enable();
        $out = $this->service->get("test-get");
        $mock->disable();

        $this->assertNull($out);
    }

    public function testSetSuccessWithString() {
        $mock = new Mock($this->namespace, 'set_transient', function(string $name, $value, int $time) {
            $this->assertEquals("test-name", $name);
            $this->assertEquals("test-value", $value);
            $this->assertEquals(300, $time);
            return true;
        });

        $mock->enable();
        $lifetime = Carbon::now()->addSeconds(300);
        $out      = $this->service->set("test-name", "test-value", $lifetime);
        $mock->disable();

        $this->assertInstanceOf(TransientInterface::class, $out);
        $this->assertEquals("test-name", $out->getName());
        $this->assertEquals("test-value", $out->getValue());
        $this->assertInstanceOf(Carbon::class, $out->getMaxDate());
        $this->assertEquals($lifetime, $out->getMaxDate());
        $this->assertFalse($out->isDirty());
    }

    public function testSetSuccessWithWithObject() {
        $mock = new Mock($this->namespace, 'set_transient', function(string $name, $value, int $time) {
            $this->assertEquals("test-set", $name);
            $this->assertEquals("test-value", $value);
            $this->assertEquals(300, $time);
            return true;
        });

        $mock->enable();
        $lifetime  = Carbon::now()->addSeconds(300);
        $transient = new Transient("test-set", "test-value", $lifetime);
        $out       = $this->service->set($transient, "nothing");
        $mock->disable();

        $this->assertInstanceOf(TransientInterface::class, $out);
        $this->assertEquals("test-set", $out->getName());
        $this->assertEquals("test-value", $out->getValue());
        $this->assertInstanceOf(Carbon::class, $out->getMaxDate());
        $this->assertEquals($lifetime, $out->getMaxDate());
        $this->assertFalse($out->isDirty());
    }

    public function testSetUpdate() {
        $mock = new Mock($this->namespace, 'set_transient', function(string $name, $value, int $time) {
            $this->assertEquals("test-set", $name);
            $this->assertEquals("test-value", $value);
            $this->assertEquals(300, $time);
            return true;
        });

        $mock->enable();
        $lifetime = Carbon::now()->addSeconds(300);
        $out      = $this->service->set("test-set", "test-value", $lifetime);
        $this->assertEquals("test-set", $out->getName());
        $this->assertEquals("test-value", $out->getValue());
        $this->assertInstanceOf(Carbon::class, $out->getMaxDate());
        $this->assertEquals($lifetime, $out->getMaxDate());
        $this->assertFalse($out->isDirty());

        $mock->disable();
    }

    public function testRemoveExists() {
        $mock = new Mock($this->namespace, 'delete_transient', function(string $name) {
            $this->assertEquals("test-delete", $name);
            return true;
        });

        $mock->enable();
        $this->assertTrue(
            $this->service->remove("test-delete")
        );
        $mock->disable();
    }

    public function testRemoveNotExists() {

        $mock = new Mock($this->namespace, 'delete_transient', function(string $name) {
            $this->assertEquals("test-delete", $name);
            return false;
        });

        $mock->enable();
        $this->assertFalse(
            $this->service->remove("test-delete")
        );
        $mock->disable();
    }

}
