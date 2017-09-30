<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  FilterService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
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
        return $this->eventHandler->on($tag, $filter, $priority, $maxArgCount);
    }


    public function remove(string $tag, int $handle): bool {
        return $this->eventHandler->off($tag, $handle);
    }

    public function apply(string $tag, ...$args): bool {
        return $this->eventHandler->fire($tag, ...$args);
    }
}
