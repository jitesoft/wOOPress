<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  FilterService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
use Jitesoft\wOOPress\Contracts\FilterInterface;
use Jitesoft\wOOPress\Contracts\FilterServiceInterface;
use Jitesoft\wOOPress\EventListener;

/**
 * Service to apply, add and remove filters to various tags.
 */
class FilterService implements FilterServiceInterface {

    // Note: This service is more of a wrapper around the EventHandler to keep the WordPress naming standard
    //       intact. Actions and Filters uses the same event system, but they got different wrappers to make
    //       the API easier to use for people used to WP.

    /** @var EventHandlerInterface */
    private $eventHandler;

    public function __construct(EventHandlerInterface $eventHandler) {
        $this->eventHandler = $eventHandler;
    }

    /**
     * Add a filter to a given tag.
     * The invoke method of the filter (or in case of a callable, the function itself) will be called when the
     * filter is applied. The passed values will be the name of the filter and any arguments passed by the
     * callee of the apply method.
     *
     * @param string                   $tag         Name of the tag to apply the filter to.
     * @param FilterInterface|callable $filter      Filter as callable or FilterInterface implementation.
     * @param int                      $priority    Priority of the attached filter.
     * @param int                      $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the filter. Can later be used in the `remove` method to remove the filter.
     */
    public function add(string $tag, $filter, int $priority = 10, int $maxArgCount = -1): int {
        if (($filter instanceof EventListenerInterface) === false) {
            if (is_callable($filter) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        "The filter passed to the %s was neither a callback nor did it implement %s.",
                        "FilterService",
                        EventListenerInterface::class
                    )
                );
            }
            $filter = new EventListener($filter);
        }
        return $this->eventHandler->listen(
            $tag,
            EventHandlerInterface::EVENT_TYPE_FILTER,
            $filter,
            $priority,
            $maxArgCount
        );
    }

    /**
     * Remove a filter from a given action.
     *
     * @param int     $handle Handle of the filter to remove.
     * @param string  $tag    Tag to remove the filter from.
     *
     * @return bool Result.
     */
    public function remove(int $handle, string $tag): bool {
        return $this->eventHandler->removeListener($handle, $tag, EventHandlerInterface::EVENT_TYPE_FILTER);
    }

    /**
     * Invokes all filters of a given tag.
     *
     * @param string $tag      Tag to invoke.
     * @param mixed  $args,... Arguments to pass to the filter.
     *
     * @return bool Result.
     */
    public function apply(string $tag, ...$args): bool {
        return $this->eventHandler->fire($tag, EventHandlerInterface::EVENT_TYPE_FILTER, ...$args);
    }
}
