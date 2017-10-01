<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventHandler.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use Jitesoft\Exceptions\LogicExceptions\InvalidOperationException;
use Jitesoft\wOOPress\Contracts\EventHandlerInterface;
use Jitesoft\wOOPress\Contracts\EventListenerInterface;

/**
 * Class EventHandler
 */
class EventHandler implements EventHandlerInterface {

    /** @var int */
    private $handlerId = 0;

    /** @var array */
    private $listeners = [];

    /**
     * Add a listener to a given event.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * The EventHandlerInterface::EVENT_TYPE_ALL (or `*` character) is reserved and will invoke an exception, adding a
     * event listener requires a specific type to be passed.
     *
     * As with `type`, the `tag` parameter may neither be `*`. Passing it will also invoke an exception.
     *
     * @param string $tag Type to listen to.
     * @param string $type Type of event to add.
     * @param EventListenerInterface $listener Listener as ListenerInterface.
     * @param int $priority Priority of the attached listener.
     * @param int $maxArgCount Maximum number of arguments the callback will accept.
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
    ): int {

        if ($tag === "*") {
            throw new InvalidOperationException(
               "It is not possible to add a listener to the `*` tag!"
            );
        }
        if ($type === "*") {
            throw new InvalidOperationException(
               "It is not possible to add a listener to the `*` type!"
            );
        }

        $listenerId = $this->handlerId++;
        if (!array_key_exists($tag, $this->listeners)) {
            // If the listeners array does not already have the tag, we have to add it.
            // We also have to add a hook to the underlying WP event engine.
            $this->listeners[$tag] = [];
            // If its neither action or filter, its an internal type, which will only be used through the
            // event handler itself.

            if ($type === self::EVENT_TYPE_FILTER) {
                add_filter($tag, function(...$args) use($tag) {
                    $this->fire($tag, self::EVENT_TYPE_FILTER, ...$args);
                });
            } else if ($type === self::EVENT_TYPE_ACTION) {
                add_action($tag, function(...$args) use($tag) {
                    $this->fire($tag, self::EVENT_TYPE_ACTION, ...$args);
                });
            }
        }

        // Store all listeners (depending on id and tag)
        $this->listeners[$tag][$listenerId] = [
            'listener' => $listener,
            'type'     => $type,
            'priority' => $priority,
            'max_args' => $maxArgCount
        ];

        uasort($this->listeners[$tag], function($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        return $listenerId;
    }

    /**
     * Remove a listener from a given event with a given type.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * In case the EventHandlerInterface::EVENT_TYPE_ALL (or `*`) is passed, the event handler will remove all events
     * with the given handler from all possible event types.
     * This will take more time, so its recommended to use a specific type.
     *
     * @param string $tag Tag to remove the listener from.
     * @param string $type Type of event.
     * @param int $handle Handle for the listener to be removed.
     *
     * @return bool Result. If true, removal succeeded, if false, it did not.
     */
    public function removeListener(int $handle, string $tag = "*", string $type = self::EVENT_TYPE_ALL): bool {
        $found = false;
        foreach ($this->listeners as $innerTag => &$listeners) {
            if ($tag !== "*" && $tag !== $innerTag) {
                continue;
            }
            if (array_key_exists($handle, $listeners) && $listeners[$handle]['type'] === $type || $type === "*") {
                // Remove the listener and its data.
                // As of now, we do not stop listen to the actual WordPress event,
                // if this is creating performance issues, we have to change that.
                unset($listeners[$handle]);
                $found = true;
            }
        }

        return $found;
    }

    /**
     * Invokes an event of given tag and type.
     *
     * The `type` parameter could use one of the predefined constants in the EventHandlerInterface or be a custom one.
     * In case the EventHandlerInterface::EVENT_TYPE_ALL (or `*`) is passed, the event handler will invoke all
     * events with a given tag from all types.
     *
     * @param string $tag Tag to invoke events for.
     * @param string $type Type of event.
     * @param mixed $args,... Optional arguments to pass to listener.
     *
     * @return bool Result. If true, the invocation succeeded, if false, it did not.
     */
    public function fire(string $tag, string $type, ...$args): bool {
        foreach ($this->listeners as $tagKey => $listeners) {
            if ($tag === $tagKey || $tag === "*") {
                foreach ($listeners as $handle => $listener) {
                    if ($type === "*" || $listener["type"] === $type) {
                        $args = array_splice($args, 0, $listener['max_args']);
                        $listener['listener']->invoke($tagKey, $listener['type'], ...$args);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check number of listeners attached to a given tag and/or type.
     *
     * OBSERVE: The event handler can only give information about listeners which have been added through the handler.
     *          It can NOT give information about listeners added through other systems.
     *
     * @param string $tag Tag to get event listener count from (* means ALL).
     * @param string $type Type of event to get listener count from.
     *
     * @return int Count.
     */
    public function getListenerCount($tag = "*", $type = EventHandlerInterface::EVENT_TYPE_ALL): int {
        $count = 0;

        foreach ($this->listeners as $innerTag => $listeners) {
            if ($tag === "*" || $innerTag === $tag) {
                if ($type === EventHandlerInterface::EVENT_TYPE_ALL) {
                    $count += count($listeners);
                    continue;
                }
                foreach ($listeners as $listener) {
                    if ($listener["type"] === $type) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

    /**
     * Remove all listeners of a given type and/or given tag.
     * Calling this method without parameters will remove all the events from the handler.
     *
     * @param string $tag Tag to remove listeners from.
     * @param string $type Type to remove listeners from.
     *
     * @return bool Result.
     */
    public function clear($tag = "*", $type = EventHandlerInterface::EVENT_TYPE_ALL): bool {
        foreach ($this->listeners as $innerTag => $listeners) {
            if ($tag === "*" || $innerTag === $tag) {
                if ($type === "*") {
                    unset($this->listeners[$innerTag]);
                    $this->listeners[$innerTag] = [];
                    continue;
                }

                foreach ($listeners as $key => $listener) {
                    if ($listener["type"] === $type) {
                        unset($this->listeners[$innerTag][$key]);
                        $this->listeners[$innerTag][$key] = [];
                    }
                }
            }
        }
        return true;
    }
}
