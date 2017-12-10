<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\ActionInterface;
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
    //       the API easier to use for people used to WP.

    /** @var EventHandlerInterface */
    private $eventHandler;

    public function __construct(EventHandlerInterface $eventHandler) {
        $this->eventHandler = $eventHandler;
    }

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
    public function on(string $tag, $action, int $priority = 10, int $maxArgCount = -1): int {
        if (($action instanceof EventListenerInterface) === false) {
            if (is_callable($action) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        "The action passed to the %s was neither a callback nor did it implement the %s.",
                        'ActionService',
                        EventListenerInterface::class
                    )
                );
            }

            $action = new EventListener($action);
        }

        return $this->eventHandler->listen(
            $tag,
            EventHandlerInterface::EVENT_TYPE_ACTION,
            $action,
            $priority,
            $maxArgCount
        );
    }

    /**
     * Remove a action from a given tag.
     *
     * @param int    $handle Handle of the action to be removed.
     * @param string $tag    Tag to remove the action from.
     *
     * @return bool Result.
     */
    public function off(int $handle, string $tag): bool {
        return $this->eventHandler->removeListener(
            $handle,
            $tag,
            EventHandlerInterface::EVENT_TYPE_ACTION
        );
    }

    /**
     * Invokes all actions of given a given tag.
     *
     * @param string $tag      Tag to invoke.
     * @param mixed  $args,... Optional arguments to pass to action.
     *
     * @return bool Result.
     */
    public function fire(string $tag, ...$args): bool {
        return $this->eventHandler->fire($tag, EventHandlerInterface::EVENT_TYPE_ACTION, ...$args);
    }

}
