<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventHandlerInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use Jitesoft\Exceptions\LogicExceptions\InvalidOperationException;

/**
 * @internal Used by the wOOPress API.
 */
interface EventHandlerInterface {
    public const EVENT_TYPE_ACTION = "action";
    public const EVENT_TYPE_FILTER = "filter";
    public const EVENT_TYPE_ALL    = "*";

    /**
     * Add a listener to a given event.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * The EventHandlerInterface::EVENT_TYPE_ALL (or `*` character) is reserved and will invoke an exception, adding a
     * event listener requires a specific type to be passed.
     *
     * @param string                 $tag         Type to listen to.
     * @param string                 $type        Type of event to add.
     * @param EventListenerInterface $listener    Listener as ListenerInterface.
     * @param int                    $priority    Priority of the attached listener.
     * @param int                    $maxArgCount Maximum number of arguments the callback will accept.
     *
     * @return int Handle for the listener. Can later be used in the `off` method to remove given listener.
     * @throws InvalidOperationException
     */
    public function listen(
        string $tag,
        string $type,
        EventListenerInterface $listener,
        int $priority = 10,
        int $maxArgCount = -1
    ) : int;

    /**
     * Remove a listener from a given event with a given type.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * In case the EventHandlerInterface::EVENT_TYPE_ALL (or `*`) is passed, the event handler will remove all events
     * with the given handler from all possible event types.
     * This will take more time, so its recommended to use a specific type.
     *
     * @param int    $handle Handle for the listener to be removed.
     * @param string $tag    Tag to remove the listener from.
     * @param string $type   Type of event.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function removeListener(
        int $handle,
        string $tag = "*",
        string $type = EventHandlerInterface::EVENT_TYPE_ALL
    ) : bool;

    /**
     * Invokes an event of given tag and type.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * In case the EventHandlerInterface::EVENT_TYPE_ALL (or `*`) is passed, the event handler will invoke all
     * events with a given tag from all types.
     *
     * @param string $tag       Tag to invoke events for.
     * @param string $type      Type of event.
     * @param mixed  $args,...  Optional arguments to pass to listener.
     *
     * @return bool Result. If true, the invocation succeeded, if false, it did not.
     */
    public function fire(string $tag, string $type, ...$args) : bool;

    /**
     * Check number of listeners attached to a given tag and/or type.
     *
     * OBSERVE: The event handler can only give information about listeners which have been added through the handler.
     *          It can NOT give information about listeners added through other systems.
     *
     * @param string $tag  Tag to get event listener count from (* means ALL).
     * @param string $type Type to get event listener count from.
     *
     * @return int Count.
     */
    public function getListenerCount($tag = "*", $type = EventHandlerInterface::EVENT_TYPE_ALL) : int;

    /**
     * Remove all listeners to a given tag.
     * Calling this method without parameters will remove all the events from the handler.
     *
     * @param string $tag  Tag to remove listeners from.
     *
     * @return bool Result.
     */
    public function clear($tag = "*") : bool;

}
