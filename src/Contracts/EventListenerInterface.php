<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  EventListenerInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * Interface for event listeners.
 */
interface EventListenerInterface {

    /**
     * Callback function which will be called when the event that the listener subscribes to is fired.
     *
     * @param string $event    Name of the event which invoked the listener.
     * @param string $type     Type of event fired.
     * @param mixed  $args ,.. Argument list.
     * @return
     */
    public function invoke(string $event, string $type, ...$args);

}
