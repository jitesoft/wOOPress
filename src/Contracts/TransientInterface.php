<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  TransistentInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use Carbon\Carbon;

/**
 * Contract for transient objects.
 * Transients are objects stored short time in the database, much like a option but with a max lifetime.
 * The object is not guaranteed to stay in the database until the max age expire, but guaranteed to not be available
 * to fetch after the time has passed.
 * This makes hte Transient object quite unreliable, and a fallback should be used in case its gone before the
 * application is done with it.
 *
 * The transient interface inherits the option interface, so at any time, the transient object could be saved as a
 * option instead, making it a option which nullifies the max date of the object and makes it persisted until removed.
 */
interface TransientInterface extends OptionInterface {

    /**
     * Get the maximum lifetime object of the transient.
     *
     * Observe:
     * The MaxDate object does not guarantee that the object is in the database until the end of the set time, but
     * rather that it will be gone from the database (or collected next cleanup) and not possible to fetch anymore.
     * At any time, the object could be removed from the database, so a fallback is recommended.
     *
     * @return Carbon
     */
    public function getMaxDate() : Carbon;
}
