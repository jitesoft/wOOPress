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

    public function testOnSuccessWithCallback() {

        $callback = function($name) {
            $this->assertEquals("test_event", $name);
        };

        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('on')
            ->with(
                [
                    identicalTo('test_event'),
                    typeOf(EventListenerInterface::class),
                    identicalTo(5),
                    identicalTo(11)
                ]
            )
            ->andReturn(5)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $actionService ActionServiceInterface */
        $actionService = DependencyContainer::get(ActionServiceInterface::class);

        $this->assertEquals(5, $actionService->on("test_event", $callback, 5, 11));

    }

}

