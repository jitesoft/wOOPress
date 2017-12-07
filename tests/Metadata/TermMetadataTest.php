<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  CommentMetadataTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Metadata;

use Jitesoft\wOOPress\Contracts\MetadataInterface;
use Jitesoft\wOOPress\Metadata\TermMetadata;
use Jitesoft\wOOPress\Tests\AbstractTestCase;

class TermMetadataTest extends AbstractTestCase {

    /** @var MetadataInterface */
    protected $meta;

    protected function setUp() {
        parent::setUp();

        $this->meta = new TermMetadata(10, "abc123", "testvalue");
    }

    public function testType() {
        $this->assertEquals(MetadataInterface::META_TYPE_TERM, $this->meta->getMetaType());
    }

    public function testId() {
        $this->assertEquals(10, $this->meta->getId());
    }

    public function testKey() {
        $this->assertEquals("abc123", $this->meta->getKey());}

    public function testValue() {
        $this->assertEquals("testvalue", $this->meta->getValue());
    }

    public function testDirty() {
        $this->assertEquals(true, $this->meta->isDirty());
        $this->meta->setDirtyState(false);
        $this->assertEquals(false, $this->meta->isDirty());
    }

}
