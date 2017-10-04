<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TransientServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use DateTime;

/**
 * Contract for transient services.
 *
 * The implementee is intended to take care of adding, removing and fetching transient objects.
 */
interface TransientServiceInterface {

    /**
     * Get the transient object.
     *
     * In case the transient have been removed, the $defaultValue parameter will be set as value of the result object.
     * If the $defaultValue parameter is set to null, null will be returned, that is, no object at all.
     *
     * @param string $transient    Transient object name as string.
     * @param mixed  $defaultValue Default value to set to the result objects value or null if null is to be returned.
     *
     * @return TransientInterface|null
     */
    public function get(string $transient, $defaultValue = null) : ?TransientInterface;

    /**
     * Set a transient object.
     *
     * If the object (or name) exists, the existing one will be overwritten.
     * If the object (or name) does not exist, a new one will be created and returned.
     *
     * @param string|TransientInterface $transient Object to set/create as TransientInterface or as the name of the
     *                                             Transient.
     * @param mixed                     $value     Value to set
     *                                             (will be ignored if TransientInterface is passed as first arg).
     * @param null|DateTime             $maxDate   Maximum lifetime. Set it to null to set eternal max lifetime.
     * @return TransientInterface
     */
    public function set($transient, $value, ?DateTime $maxDate = null) : TransientInterface;

    /**
     * Removes a transient object from the database.
     *
     * @param string|TransientInterface $transient Object to remove as TransientInterface or as
     *                                             the name of the transient.
     * @return bool True if removed, false if not removed or the object does not exist.
     */
    public function remove($transient) : bool;
}
