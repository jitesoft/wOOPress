<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Option.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress;

use Jitesoft\wOOPress\Contracts\OptionInterface;

class Option implements OptionInterface {

    protected $name;
    protected $value;
    protected $dirty;

    public function __construct(string $name, $value = null) {
        $this->dirty = true;
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * Get the name of the option.
     *
     * @return string Option name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get the value of the option.
     *
     * @return mixed Option value.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set the value of the option, overwriting the old option value.
     *
     * @param mixed $value New value (max 2^32 bytes).
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * If the option is dirty or not.
     * If an option is dirty, it has been changed since it was saved the last time.
     *
     * @return bool True if dirty, false if clean.
     */
    public function isDirty(): bool {
        return $this->dirty;
    }

    /**
     * Set dirty state.
     *
     * @param bool $state
     * @internal Should not be changed outside of a OptionServiceInterface implementer.
     */
    public function setDirty(bool $state) {
        $this->dirty = $state;
    }

}
