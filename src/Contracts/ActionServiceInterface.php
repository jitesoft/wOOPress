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
     * Add a listener to a given action.
     * The listeners invoke method (or in case of a callable, the function itself) will be called when the
     * event is fired. The passed values will be the name of the action and any arguments passed from the
     * event itself.
     *
     * @param string                          $action   Action to listen to.
     * @param EventListenerInterface|callable $listener Listener as callable or object to use as listener.
     *
     * @return int Handle for the listener. Can later be used in the `off` method to remove given listener.
     */
    public function on(string $action, $listener) : int;

    /**
     * Remove a listener from a given event.
     *
     * @param string                     $action   Action to remove the listener from.
     * @param EventListenerInterface|int $listener Listener - or handle to listener - to be removed.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function off(string $action, $listener) : bool;

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
