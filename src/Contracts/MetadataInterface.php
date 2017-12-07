<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  MetaDataInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * Contract for meta data objects.
 */
interface MetadataInterface {
    public const META_TYPE_USER    = "user";
    public const META_TYPE_COMMENT = "comment";
    public const META_TYPE_POST    = "post";
    public const META_TYPE_TERM    = "term";

    /**
     * Get the key of the meta object.
     *
     * @return string
     */
    public function getKey() : string;

    /**
     * Get the value of the meta object.
     *
     * @return string
     */
    public function getValue() : string;

    /**
     * Get the ID of the meta object.
     *
     * @return int
     */
    public function getId() : int;

    /**
     * Get the type of metadata.
     *
     * @return string
     */
    public function getMetaType() : string;

    /**
     * Check if the metadata is dirty or not.
     * If its dirty it has changed since last save to database.
     *
     * @return bool
     */
    public function isDirty() : bool;

    /**
     * @internal
     * @param bool $state
     */
    public function setDirtyState(bool $state);

}
