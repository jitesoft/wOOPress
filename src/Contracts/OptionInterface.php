<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * Contract for Option implementations.
 */
interface OptionInterface {

    /**
     * Get the name of the option.
     *
     * @return string Option name.
     */
    public function getName() : string;

    /**
     * Get the value of the option.
     *
     * @return mixed Option value.
     */
    public function getValue();

    /**
     * Set the value of the option, overwriting the old option value.
     *
     * @param mixed $value New value (max 2^32 bytes).
     */
    public function setValue($value);

    /**
     * If the option is dirty or not.
     * If an option is dirty, it has been changed since it was saved the last time.
     *
     * @return bool True if dirty, false if clean.
     */
    public function isDirty() : bool;

}
