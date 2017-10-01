<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\ActionServiceInterface;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
use Jitesoft\wOOPress\EventListener;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use Mockery;

class ActionServiceTest extends AbstractTestCase {

    public function testOnWithCallback() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->with(
                'test_event',
                EventHandlerInterface::EVENT_TYPE_ACTION,
                anInstanceOf(EventListenerInterface::class),
                5,
                11
            )
            ->andReturn(5)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);

        $this->assertEquals(5, $actionService->on("test_event", function(){}, 5, 11));
    }

    public function testOnWithListener() {
        $listener = new EventListener(function() {});

        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->with(
                'test_event',
                EventHandlerInterface::EVENT_TYPE_ACTION,
                identicalTo($listener),
                3,
                73
            )
            ->andReturn(3)
            ->getMock();


        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);

        $this->assertEquals(3, $actionService->on("test_event", $listener, 3, 73));
    }

    public function testOnWithInvalidType() {
        $this->expectException(InvalidArgumentException::class);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);
        $actionService->on('test_event', 'hi', 3, 3);
    }

    public function testOff() {

        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->with(
                'test_event',
                EventHandlerInterface::EVENT_TYPE_ACTION,
                anInstanceOf(EventListenerInterface::class),
                5,
                11
            )
            ->andReturn(5)
            ->shouldReceive('removeListener')
            ->with(
                5,
                'test_event',
                EventHandlerInterface::EVENT_TYPE_ACTION
            )
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);
        $actionService->on("test_event", function(){}, 5, 11);
        $this->assertTrue($actionService->off(5,'test_event'));
    }

    public function testFire() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->andReturn(1)
            ->shouldReceive('fire')
            ->with('test_event', EventHandlerInterface::EVENT_TYPE_ACTION, 'a', 'b', 'c')
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);

        $actionService->on('test_event', function(){}, 0, 1);
        $actionService->fire('test_event', 'a', 'b', 'c');
    }
}
