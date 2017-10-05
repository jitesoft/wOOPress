<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  MetaDataServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use Jitesoft\Utilities\DataStructures\Lists\IndexedListInterface;

/**
 * Contract for metadata services.
 *
 * Metadata services are intended to handle adding, removing fetching and deleting metadata.
 */
interface MetadataServiceInterface {

    /**
     * Delete a metadata object from the database.
     *
     * @param MetadataInterface $metadata Object to delete.
     *
     * @return bool Result.
     */
    public function deleteMetadata(MetadataInterface $metadata) : bool;

    /**
     * Update a metadata object (save to database).
     *
     * @param MetadataInterface $metadata The metadata object to update.
     *
     * @return MetadataInterface
     */
    public function updateMetadata(MetadataInterface $metadata) : MetadataInterface;

    /**
     * Add a metadata object to the database.
     *
     * @param MetadataInterface $metadata Metadata to add to the database.
     * @param bool              $unique   If it is supposed to be unique or not. If unique, no more objects will be
     *                                    added to the given metadata key.
     * @return bool Result.
     */
    public function addMetadata(MetadataInterface $metadata, bool $unique = false) : bool;


    /**
     * Get meta data from the database.
     *
     * @param string      $type   The type of metadata (@see MetadataInterface constants).
     * @param int         $id     Meta id.
     * @param null|string $key    The metadata key (if omitted, all of the type with given id will be fetched).
     * @param bool        $single If only the first item found should be returned.
     *
     * @return IndexedListInterface|MetadataInterface[]|MetadataInterface
     */
    public function getMetadata(string $type, int $id, ?string $key = null, bool $single = false);

}
