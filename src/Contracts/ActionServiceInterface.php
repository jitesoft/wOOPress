<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * Interface for action services.
 * An action service is intended to wrap the WordPress action calls.
 */
interface ActionServiceInterface {

    /**
     * @param EventHandlerInterface $eventHandler
     */
    public function __construct(EventHandlerInterface $eventHandler);

    /**
     * Add a listener to a given action.
     * The listeners invoke method (or in case of a callable, the function itself) will be called when the
     * event is fired. The passed values will be the name of the action and any arguments passed from the
     * event itself.
     *
     * @param string   $action      Action to listen to.
     * @param callable $listener    Listener as callable.
     * @param int      $priority    Priority of the attached listener.
     * @param int      $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the listener. Can later be used in the `off` method to remove given listener.
     */
    public function on(string $action, callable $listener, int $priority = 10, int $maxArgCount = -1) : int;

    /**
     * Remove a listener from a given action.
     *
     * @param string $action Action to remove the listener from.
     * @param int    $handle Handle of the listener to be removed.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function off(string $action, int $handle) : bool;

    /**
     * Invokes an action of given name.
     *
     * @param string $action    Action to invoke.
     * @param mixed  $args,...  Optional arguments to pass to listener.
     *
     * @return bool Result. If true, the invocation succeeded, if false, it did not.
     */
    public function fire(string $action, ...$args) : bool;
}
