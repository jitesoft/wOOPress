<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  FilterInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

interface FilterInterface extends EventListenerInterface {

    // Note: The FilterInterface (and ActionInterface) is just aliases for the EventListenerInterface.
    //       They exist to keep the WordPress naming intact and to make it easier for people whom are
    //       used to WordPress to use the API.

}
