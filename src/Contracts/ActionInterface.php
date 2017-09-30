<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ActionInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

interface ActionInterface extends EventListenerInterface {

    // Note: The ActionInterface (and FilterInterface) is just aliases for the EventListenerInterface.
    //       They exist to keep the WordPress naming intact and to make it easier for people whom are
    //       used to WordPress to use the API.

}
