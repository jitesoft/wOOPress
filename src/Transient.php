<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Transient.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress;

use Carbon\Carbon;
use DateTime;
use Jitesoft\wOOPress\Contracts\TransientInterface;

class Transient extends Option implements TransientInterface {

    /** @var Carbon|null */
    protected $maxDate;

    public function __construct($name, $value = null, ?DateTime $maxDate = null) {
        parent::__construct($name, $value);
        $this->maxDate = $maxDate ? Carbon::instance($maxDate) : null;
    }

    /**
     * Get the maximum lifetime object of the transient.
     *
     * Observe:
     * The MaxDate object does not guarantee that the object is in the database until the end of the set time, but
     * rather that it will be gone from the database (or collected next cleanup) and not possible to fetch anymore.
     * At any time, the object could be removed from the database, so a fallback is recommended.
     *
     * @return null|Carbon
     */
    public function getMaxDate(): ?Carbon {
        return $this->maxDate;
    }

}
