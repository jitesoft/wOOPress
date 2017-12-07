<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  CommentMetadata.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Metadata;

use Jitesoft\wOOPress\Metadata;

/**
 * Class CommentMetadata
 *
 * Comment specific metadata implementation.
 */
class CommentMetadata extends Metadata {

    /**
     * CommentMetadata constructor.
     * @param int $id
     * @param string $key
     * @param string $value
     */
    public function __construct(int $id, string $key, string $value) {
        parent::__construct($id, $key, $value, self::META_TYPE_COMMENT);
    }

}
