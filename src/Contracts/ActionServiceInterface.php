<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use InvalidArgumentException;

/**
 * Interface for action services.
 * Action services are intended to work with the WordPress Actions.
 */
interface ActionServiceInterface {

    /**
     * Add an action to a given action.
     * The actions invoke method (or in case of a callable, the function itself) will be called when the
     * event is fired. The passed values will be the name of the action and any arguments passed from the
     * event itself.
     *
     * @param string                   $tag         Name of the tag to listen to.
     * @param callable|ActionInterface $action      Action as callable or ActionInterface.
     * @param int                      $priority    Priority of the attached action.
     * @param int                      $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the action. Can later be used in the `off` method to remove given action.
     *
     * @throws InvalidArgumentException
     */
    public function on(string $tag, $action, int $priority = 10, int $maxArgCount = -1) : int;

    /**
     * Remove a action from a given tag.
     *
     * @param int    $handle Handle of the action to be removed.
     * @param string $tag    Tag to remove the action from.
     *
     * @return bool Result.
     */
    public function off(int $handle, string $tag) : bool;

    /**
     * Invokes all actions of given a given tag.
     *
     * @param string $tag      Tag to invoke.
     * @param mixed  $args,... Optional arguments to pass to action.
     *
     * @return bool Result.
     */
    public function fire(string $tag, ...$args) : bool;

}
