<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventHandlerTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Jitesoft\Exceptions\LogicExceptions\InvalidOperationException;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\EventListener;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use phpmock\MockBuilder;
use PHPUnit\Framework\AssertionFailedError;

class EventHandlerTest extends AbstractTestCase {
    /** @var EventHandlerInterface */
    private $handler;

    private $namespace;

    protected function setUp() {
        parent::setUp();

        $this->handler   = DependencyContainer::get(EventHandlerInterface::class);
        $this->namespace = dirname(get_class($this->handler));
    }

    public function testClearTag() {
        $this->handler->listen("test", "test", new EventListener());
        $reflection = new \ReflectionClass(get_class($this->handler));
        $prop       = $reflection->getProperty("listeners");
        $prop->setAccessible(true);
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);

        $this->handler->listen("test2", "test", new EventListener());
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);
        $this->assertCount(1, $prop->getValue($this->handler)["test2"]);

        $this->assertTrue($this->handler->clear("test"));
        $this->assertCount(0, $prop->getValue($this->handler)["test"]);
        $this->assertCount(1, $prop->getValue($this->handler)["test2"]);
    }

    public function testClearAll() {
        $this->handler->listen("test", "test", new EventListener());
        $reflection = new \ReflectionClass(get_class($this->handler));
        $prop       = $reflection->getProperty("listeners");
        $prop->setAccessible(true);
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);

        $this->handler->listen("test2", "test", new EventListener());
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);
        $this->assertCount(1, $prop->getValue($this->handler)["test2"]);

        $this->assertTrue($this->handler->clear("*"));
        $this->assertCount(0, $prop->getValue($this->handler)["test"]);
        $this->assertCount(0, $prop->getValue($this->handler)["test2"]);
    }


    public function testListenFilter() {
        $builder = new MockBuilder();
        $mock    = $builder->setNamespace($this->namespace)
            ->setName('add_filter')
            ->setFunction(function($tag, $action) {
                $this->assertEquals("test", $tag);
                $this->assertTrue(is_callable($action));
            })->build();

        $mock->enable();

        $this->handler->listen("test", "filter", new EventListener());
        $mock->disable();
    }

    public function testListenAction() {
        $builder = new MockBuilder();
        $mock    = $builder->setNamespace($this->namespace)
            ->setName('add_action')
            ->setFunction(function($tag, $action) {
                $this->assertEquals("test", $tag);
                $this->assertTrue(is_callable($action));
            })->build();

        $mock->enable();

        $this->handler->listen("test", "action", new EventListener());
        $mock->disable();
    }

    public function testListenInternal() {
        $builder = new MockBuilder();
        $mock    = $builder->setNamespace($this->namespace)
            ->setName("add_action")
            ->setName("add_filter")
            ->setFunction(function() {
                throw new \Exception("Test failed cause it called either add_action or add_filter function.");
            })
            ->build();

        $mock->enable();
        $out = $this->handler->listen("test", "test", new EventListener());
        $this->assertInternalType("int", $out);
        $mock->disable();
    }

    public function testListenInvalidOperationType() {
        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage("It is not possible to add a listener to the `*` type!");
        $this->handler->listen("aaa", "*", new EventListener());
    }

    public function testListenInvalidOperationTag() {
        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage("It is not possible to add a listener to the `*` tag!");
        $this->handler->listen("*", "aaa", new EventListener());
    }

    public function testRemoveListener() {
        $handle     = $this->handler->listen("test", "test", new EventListener());
        $reflection = new \ReflectionClass(get_class($this->handler));
        $prop       = $reflection->getProperty("listeners");
        $prop->setAccessible(true);
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);

        $this->assertTrue(
            $this->handler->removeListener($handle)
        );

        $this->assertCount(0, $prop->getValue($this->handler)["test"]);
    }

    public function testRemoveListenerWithTag() {
        $handle = $this->handler->listen("test", "test", new EventListener());
        $this->handler->listen("test2", "test", new EventListener());
        $reflection = new \ReflectionClass(get_class($this->handler));
        $prop       = $reflection->getProperty("listeners");
        $prop->setAccessible(true);
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);

        $this->assertTrue(
            $this->handler->removeListener($handle)
        );

        $this->assertCount(0, $prop->getValue($this->handler)["test"]);
        $this->assertCount(1, $prop->getValue($this->handler)["test2"]);
    }

    public function testRemoveListenerWithType() {
        $handle = $this->handler->listen("test", "test", new EventListener());
        $this->handler->listen("test2", "test2", new EventListener());
        $reflection = new \ReflectionClass(get_class($this->handler));
        $prop       = $reflection->getProperty("listeners");
        $prop->setAccessible(true);
        $this->assertCount(1, $prop->getValue($this->handler)["test"]);

        $this->assertTrue(
            $this->handler->removeListener($handle, "*", "test")
        );

        $this->assertCount(0, $prop->getValue($this->handler)["test"]);
        $this->assertCount(1, $prop->getValue($this->handler)["test2"]);
    }

    public function testRemoveListenerNoneExistingListener() {
        $this->assertFalse(
            $this->handler->removeListener(0)
        );
    }

    public function testFire() {
        $wasCalled1 = false;
        $wasCalled2 = false;
        $wasCalled3 = false;


        $this->handler->listen("test", "abc", new EventListener(function(string $tag, string $type, $arg1, $arg2) use(&$wasCalled1) {
            $this->assertEquals("test", $tag);
            $this->assertEquals("abc", $type);
            $this->assertEquals("a", $arg1);
            $this->assertEquals("b", $arg2);
            $wasCalled1 = true;
        }), 1, 2);

        $this->handler->listen("test", "deg", new EventListener(function(string $tag, string $type, $arg1, $arg2) use(&$wasCalled2) {
            static $count = 0;
            $this->assertEquals(0, $count);
            $this->assertEquals("test", $tag);
            $this->assertEquals("deg", $type);
            $this->assertEquals("a", $arg1);
            $this->assertEquals("b", $arg2);
            $wasCalled2 = true;
            $count++;
        }), 1, 2);

        $this->handler->listen("test2", "cda", new EventListener(function(string $tag, string $type, $arg1, $arg2) use(&$wasCalled3) {
            $this->assertEquals("test2", $tag);
            $this->assertEquals("cda", $type);
            $this->assertEquals(1, $arg1);
            $this->assertEquals(0.123, $arg2);
            $wasCalled3 = true;
        }), 2, 3);

        $this->assertTrue($this->handler->fire("test", "abc", "a", "b"));
        $this->assertTrue($this->handler->fire("*", "deg", "a", "b"));
        $this->assertTrue($this->handler->fire("test2", "*", 1, 0.123));

        $this->assertTrue($wasCalled1);
        $this->assertTrue($wasCalled2);
        $this->assertTrue($wasCalled3);
    }

    public function testGetListenerCountSpecificTag() {
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "efg", new EventListener());

        $this->assertEquals(2, $this->handler->getListenerCount("abc"));
        $this->assertEquals(3, $this->handler->getListenerCount("cba"));
        $this->assertEquals(0, $this->handler->getListenerCount("efg"));
    }

    public function testGetListenerCountSpecificType() {
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "efg", new EventListener());

        $this->assertEquals(2, $this->handler->getListenerCount("*", "gfe"));
        $this->assertEquals(3, $this->handler->getListenerCount("*", "efg"));
        $this->assertEquals(0, $this->handler->getListenerCount("*", "ggg"));
    }

    public function testGetListenerCountSpecificTagAndType() {
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "efg", new EventListener());

        $this->assertEquals(2, $this->handler->getListenerCount("abc", "efg"));
        $this->assertEquals(2, $this->handler->getListenerCount("cba", "gfe"));
        $this->assertEquals(1, $this->handler->getListenerCount("cba", "efg"));
        $this->assertEquals(0, $this->handler->getListenerCount("efg", "ggg"));
    }

    public function testGetListenerCountAll() {
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("abc", "efg", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "gfe", new EventListener());
        $this->handler->listen("cba", "efg", new EventListener());

        $this->assertEquals(5, $this->handler->getListenerCount("*", "*"));
    }

    public function testArgCount() {
        $this->handler->listen("tag", "test", new EventListener(function($a, $b, ...$c) {
            $this->assertCount(3, $c);
        }), 1, 3);

        $this->handler->fire("tag", "test", "a", "b", "c", "d", "e", "f", "g", "h");
    }

    public function testPriority() {
        $counter = 0;
        $this->handler->listen("tag", "test", new EventListener(function() use(&$counter) {
            $this->assertEquals(0, $counter);
            $counter++;
        }), 1 , 2);
        $this->handler->listen("tag", "test", new EventListener(function() use(&$counter) {
            $this->assertEquals(4, $counter);
            $counter++;
        }), 17 , 2);
        $this->handler->listen("tag", "test", new EventListener(function() use(&$counter) {
            $this->assertEquals(3, $counter);
            $counter++;
        }), 3 , 2);
        $this->handler->listen("tag", "test", new EventListener(function() use(&$counter) {
            $this->assertEquals(2, $counter);
            $counter++;
        }), 2 , 2);
        $this->handler->listen("tag", "test", new EventListener(function() use(&$counter) {
            $this->assertEquals(1, $counter);
            $counter++;
        }), 1 , 2);

        $this->handler->fire("tag", "test", "whatever");
        $this->assertEquals(5, $counter);
    }

}
