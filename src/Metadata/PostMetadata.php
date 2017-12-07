<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  PostMetadata.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Metadata;

use Jitesoft\wOOPress\Metadata;

/**
 * Class PostMetadata
 *
 * Post specific metadata implementation.
 */
class PostMetadata extends Metadata {

    /**
     * PostMetadata constructor.
     * @param int $id
     * @param string $key
     * @param string $value
     */
    public function __construct(int $id, string $key, string $value) {
        parent::__construct($id, $key, $value, self::META_TYPE_POST);
    }

}
