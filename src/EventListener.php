<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventListener.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress;

use Jitesoft\wOOPress\Contracts\EventListenerInterface;

class EventListener implements EventListenerInterface {
    /** @var callable */
    private $callback;

    public function __construct(callable $callback = null) {
        $this->callback = $callback;
    }


    /**
     * Callback function which will be called when the event that the listener subscribes to is fired.
     *
     * @param string $event   Name of the event which invoked the listener.
     * @param string $type    Type of event.
     * @param mixed $args ,.. Argument list.
     * @return
     */
    public function invoke(string $event, string $type, ...$args) {
        $cb = $this->callback;
        return $cb($event, $type, ...$args);
    }
}
