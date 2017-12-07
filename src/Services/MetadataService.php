<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  MetadataService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress\Services;

use Jitesoft\Utilities\DataStructures\Lists\IndexedListInterface;
use Jitesoft\wOOPress\Contracts\MetadataInterface;
use Jitesoft\wOOPress\Contracts\MetadataServiceInterface;

class MetadataService implements MetadataServiceInterface {

    /**
     * Delete a metadata object from the database.
     *
     * @param MetadataInterface $metadata Object to delete.
     *
     * @return bool Result.
     */
    public function deleteMetadata(MetadataInterface $metadata): bool {
        // TODO: Implement deleteMetadata() method.
    }

    /**
     * Delete all metadata which matches the passed key and type.
     * If value is passed, it will only delete metadata with matched value.
     *
     * @param string $key Metadata key as string.
     * @param string $type Metadata type (see MetadataInterface::META_TYPE_* constants).
     * @param string|null $value Metadata value as string, optional.
     * @return bool Result.
     */
    public function deleteAllMetadata(string $key,
                                      string $type = MetadataInterface::META_TYPE_COMMENT,
                                      ?string $value = null): bool {
        // TODO: Implement deleteAllMetadata() method.
    }

    /**
     * Update a metadata object (save to database).
     *
     * @param MetadataInterface $metadata The metadata object to update.
     *
     * @return MetadataInterface
     */
    public function updateMetadata(MetadataInterface $metadata): MetadataInterface {
        // TODO: Implement updateMetadata() method.
    }

    /**
     * Add metadata to the database.
     * If a metadata object is passed, it will be saved and returned.
     * In case a string is used instead of object, the type and key have to be set.
     *
     * @param MetadataInterface|string $metadata Metadata to add to the database.
     * @param string|null $key Metadata key as string.
     * @param string|null $type Metadata type (see MetadataInterface::META_TYPE_* constants).
     * @param bool $unique If it is supposed to be unique or not.
     *                                           If unique, no more objects will be added to the given metadata key.
     * @return MetadataInterface|null Resulting metadata object.
     */
    public function addMetadata($metadata,
                                string $key = null,
                                string $type = null,
                                bool $unique = false): ?MetadataInterface {
        // TODO: Implement addMetadata() method.
    }

    /**
     * Get meta data from the database.
     * The metadata is always returned as a list. If no values are found, its empty, if only one is found
     * it will be a list with one object.
     *
     * @param string $type The type of metadata (@see MetadataInterface constants).
     * @param int $id Meta id.
     * @param null|string $key The metadata key (if omitted, all of the type with given id will be fetched).
     * @param bool $single If only the first item found should be returned.
     *
     * @return IndexedListInterface|MetadataInterface[]
     */
    public function getMetadata(string $type, int $id, ?string $key = null, bool $single = false) {
        // TODO: Implement getMetadata() method.
    }
}
