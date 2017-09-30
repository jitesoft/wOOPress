<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\ActionServiceInterface;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface as IListener;
use Jitesoft\wOOPress\EventListener;

/**
 * Service so invoke, subscribe and un-subscribe to/from various Actions.
 */
class ActionService implements ActionServiceInterface {

    // Note: This service is more of a wrapper around the EventHandler to keep the WordPress naming standard
    //       intact. Actions and Filters uses the same event system, but they got different wrappers to make
    //       the API easier to use for people used to it WP.

    private $eventHandler;

    public function __construct(EventHandlerInterface $eventHandler) {
        $this->eventHandler = $eventHandler;
    }

    public function on(string $action, $listener, int $priority = 10, int $maxArgCount = -1): int {
        if (($listener instanceof IListener) === false) {
            if (is_callable($listener) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        "The listener passed to the %s was neither a callback nor did it implement the %s.",
                        'ActionService',
                        IListener::class
                    )
                );
            }
            $listener = new EventListener($listener);
        }
        return $this->eventHandler->on($action, $listener, $priority, $maxArgCount);
    }

    public function off(string $action, int $listener): bool {
        return $this->eventHandler->off($action, $listener);
    }

    public function fire(string $action, ...$args): bool {
        return $this->eventHandler->fire($action, ...$args);
    }
}
