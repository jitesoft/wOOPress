<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  FilterServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
use Jitesoft\wOOPress\Contracts\FilterServiceInterface;
use Jitesoft\wOOPress\EventListener;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use Mockery;

class FilterServiceTest extends AbstractTestCase {

    public function testAddWithCallback() {
        $mock = Mockery::mock(EventHandlerInterface::class)
        ->shouldReceive('listen')
        ->with(
            'test_filter',
            EventHandlerInterface::EVENT_TYPE_FILTER,
            anInstanceOf(EventListenerInterface::class),
            5,
            11
        )
        ->andReturn(5)
        ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $filterService FilterServiceInterface */
        $filterService = DependencyContainer::get(FilterServiceInterface::class);
        $this->assertEquals(5, $filterService->add('test_filter', function() {}, 5, 11));
    }

    public function testAddWithListener() {
        $listener = new EventListener(function() {});

        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->with(
                'test_filter',
                EventHandlerInterface::EVENT_TYPE_FILTER,
                identicalTo($listener),
                5,
                11
            )
            ->andReturn(3)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $filterService FilterServiceInterface */
        $filterService = DependencyContainer::get(FilterServiceInterface::class);
        $this->assertEquals(3, $filterService->add('test_filter', $listener, 5, 11));

    }

    public function testAddWithInvalidType() {
        $this->expectException(InvalidArgumentException::class);
        /** @var $filterService FilterServiceInterface */
        $filterService = DependencyContainer::get(FilterServiceInterface::class);
        $filterService->add('test_filter', 'hi', 5, 11);
    }

    public function testRemove() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->with(
                'test_filter',
                EventHandlerInterface::EVENT_TYPE_FILTER,
                anInstanceOf(EventListenerInterface::class),
                5,
                11
            )
            ->andReturn(5)
            ->shouldReceive('removeListener')
            ->with(
                5,
                'test_filter',
                EventHandlerInterface::EVENT_TYPE_FILTER
            )
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);

        /** @var $filterService FilterServiceInterface */
        $filterService = DependencyContainer::get(FilterServiceInterface::class);
        $filterService->add('test_filter', function() {}, 5, 11);
        $this->assertTrue($filterService->remove(5,"test_filter"));
    }

    public function testApply() {
        $mock = Mockery::mock(EventHandlerInterface::class)
            ->shouldReceive('listen')
            ->andReturn(1)
            ->shouldReceive('fire')
            ->with('test_filter', EventHandlerInterface::EVENT_TYPE_FILTER,'a', 'b', 'c')
            ->andReturn(true)
            ->getMock();

        DependencyContainer::set(EventHandlerInterface::class, $mock, true);
        /** @var $filterService FilterServiceInterface */
        $filterService = DependencyContainer::get(FilterServiceInterface::class);
        $filterService->apply('test_filter', 'a', 'b', 'c');
    }

}
