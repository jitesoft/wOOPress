<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventHandlerInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * @internal Used by the wOOPress API.
 */
interface EventHandlerInterface {

    /**
     * Add a listener to a given event.
     *
     * @param string                 $action      Action to listen to.
     * @param EventListenerInterface $listener    Listener as ListenerInterface.
     * @param int                    $priority    Priority of the attached listener.
     * @param int                    $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the listener. Can later be used in the `off` method to remove given listener.
     */
    public function on(
        string $action,
        EventListenerInterface $listener,
        int $priority = 10,
        int $maxArgCount = -1
    ) : int;

    /**
     * Remove a listener from a given event.
     *
     * @param string $action Action to remove the listener from.
     * @param int    $handle Handle for the listener to be removed.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function off(string $action, int $handle) : bool;

    /**
     * Invokes an event of given name.
     *
     * @param string $action    Action to invoke.
     * @param mixed  $args,...  Optional arguments to pass to listener.
     *
     * @return bool Result. If true, the invocation succeeded, if false, it did not.
     */
    public function fire(string $action, ...$args) : bool;

}
