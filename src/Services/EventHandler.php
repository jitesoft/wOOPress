<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventHandler.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress\Services;

use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;

class EventHandler implements EventHandlerInterface {

    private static $handlerId = 0;

    /**
     * Add a listener to a given event.
     *
     * @param string $action Action to listen to.
     * @param EventListenerInterface $listener Listener as ListenerInterface.
     * @param int $priority Priority of the attached listener.
     * @param int $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the listener. Can later be used in the `off` method to remove given listener.
     */
    public function on(
        string $action,
        EventListenerInterface $listener,
        int $priority = 10,
        int $maxArgCount = -1
    ): int {

        $id = self::$handlerId++;


        return $id;

        // TODO: Implement on() method.
    }

    /**
     * Remove a listener from a given event.
     *
     * @param string $action Action to remove the listener from.
     * @param int $handle Handle for listener to be removed.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function off(string $action, int $handle): bool {
        // TODO: Implement off() method.
    }

    /**
     * Invokes an event of given name.
     *
     * @param string $action Action to invoke.
     * @param mixed $args,... Optional arguments to pass to listener.
     *
     * @return bool Result. If true, the invocation succeeded, if false, it did not.
     */
    public function fire(string $action, ...$args): bool {
        // TODO: Implement fire() method.
    }
}
