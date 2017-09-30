<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\ActionServiceInterface;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;
use Jitesoft\wOOPress\EventListener;

/**
 * Service to invoke, subscribe and un-subscribe actions to various tags.
 */
class ActionService implements ActionServiceInterface {

    // Note: This service is more of a wrapper around the EventHandler to keep the WordPress naming standard
    //       intact. Actions and Filters uses the same event system, but they got different wrappers to make
    //       the API easier to use for people used to it WP.

    private $eventHandler;

    public function __construct(EventHandlerInterface $eventHandler) {
        $this->eventHandler = $eventHandler;
    }

    public function on(string $tag, $action, int $priority = 10, int $maxArgCount = -1): int {
        if (($action instanceof EventListenerInterface) === false) {
            if (is_callable($action) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        "The listener passed to the %s was neither a callback nor did it implement the %s.",
                        'ActionService',
                        EventListenerInterface::class
                    )
                );
            }
            $action = new EventListener($action);
        }
        return $this->eventHandler->on($tag, $action, $priority, $maxArgCount);
    }

    public function off(string $tag, int $listener): bool {
        return $this->eventHandler->off($tag, $listener);
    }

    public function fire(string $tag, ...$args): bool {
        return $this->eventHandler->fire($tag, ...$args);
    }
}
