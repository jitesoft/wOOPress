<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Metadata.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress;

use Jitesoft\wOOPress\Contracts\MetadataInterface;

class Metadata implements MetadataInterface {

    protected $key;
    protected $value;
    protected $id;
    protected $type;
    protected $dirty;

    public function __construct(int $id,
                                 string $key,
                                 string $value,
                                 string $type = MetadataInterface::META_TYPE_COMMENT) {
        $this->dirty = true;
        $this->id    = $id;
        $this->value = $value;
        $this->type  = $type;
        $this->key   = $key;
    }

    /**
     * Get the key of the meta object.
     *
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * Get the value of the meta object.
     *
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * Get the ID of the meta object.
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get the type of metadata.
     *
     * @return string
     */
    public function getMetaType(): string {
        return $this->type;
    }

    /**
     * Check if the metadata is dirty or not.
     * If its dirty it has changed since last save to database.
     *
     * @return bool
     */
    public function isDirty(): bool {
        return $this->dirty;
    }

    /**
     * @internal
     * @param bool $state
     */
    public function setDirtyState(bool $state) {
        $this->dirty = $state;
    }
}
