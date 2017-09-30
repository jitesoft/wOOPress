<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Jitesoft\wOOPress\Contracts\ActionServiceInterface;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use Mockery;

class ActionServiceTest extends AbstractTestCase {

    public function testOn() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('on')
            ->with(
                'test_event',
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

    public function testOff() {

        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('on')
            ->with(
                'test_event',
                anInstanceOf(EventListenerInterface::class),
                5,
                11
            )
            ->andReturn(5)
            ->shouldReceive('off')
            ->with(
                'test_event',
                5
            )
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);
        $actionService->on("test_event", function(){}, 5, 11);
        $this->assertTrue($actionService->off('test_event', 5));
    }

    public function testFire() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('on')
            ->andReturn(1)
            ->shouldReceive('fire')
            ->with('test_event', 'a', 'b', 'c')
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);

        $actionService->on('test_event', function(){}, 0, 1);
        $actionService->fire('test_event', 'a', 'b', 'c');
    }

}

