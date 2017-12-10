<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  UserMetadata.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Metadata;

use Jitesoft\wOOPress\Metadata;

/**
 * Class UserMetadata
 *
 * User specific metadata implementation.
 */
class UserMetadata extends Metadata {

    /**
     * UserMetadata constructor.
     *
     * @param int $id
     * @param string $key
     * @param mixed $value
     */
    public function __construct(int $id, string $key, $value) {
        parent::__construct($id, $key, $value, self::META_TYPE_USER);
    }

}
