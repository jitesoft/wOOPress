<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  MetadataServiceTest.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests\Services;

use Jitesoft\Exceptions\Database\Entity\DuplicateEntityException;
use Jitesoft\Utilities\DataStructures\Arrays;
use Jitesoft\wOOPress\Contracts\MetadataInterface;
use Jitesoft\wOOPress\Contracts\MetadataServiceInterface;
use Jitesoft\wOOPress\Metadata;
use Jitesoft\wOOPress\Tests\AbstractTestCase;
use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use phpmock\Mock;

class MetadataServiceTest extends AbstractTestCase {

    /** @var string */
    private $namespace;

    /** @var MetadataServiceInterface */
    private $service;

    protected function setUp() {
        parent::setUp();

        $this->service   = DependencyContainer::get(MetadataServiceInterface::class);
        $this->namespace = (new \ReflectionClass($this->service))->getNamespaceName();
    }

    public function testAddMetadata() {
        $metaTest  = new TestMetadata();
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use($metaTest, &$wasCalled) {
                $wasCalled = true;
                $this->assertEquals($id, $metaTest->getId());
                $this->assertEquals($key, $metaTest->getKey());
                $this->assertEquals($type, $metaTest->getMetaType());
                $this->assertEquals($value, $metaTest->getValue());
                $this->assertFalse($unique);
                return true;
            }
        );

        $mock->enable();
        $result = $this->service->addMetadata($metaTest);
        $mock->disable();

        $this->assertTrue($wasCalled);

        $this->assertInstanceOf(MetadataInterface::class, $result);
        $this->assertEquals($metaTest->getValue(), $result->getValue());
        $this->assertEquals($metaTest->getKey(), $result->getKey());
        $this->assertEquals($metaTest->getId(), $result->getId());
        $this->assertEquals($metaTest->getMetaType(), $result->getMetaType());
        $this->assertFalse($metaTest->isDirty());
    }

    public function testAddMetadataString() {

        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use(&$wasCalled) {
                $wasCalled = true;
                $this->assertEquals($key, "key");
                $this->assertEquals($type, "type");
                $this->assertEquals($value, "MetaTest");
                $this->assertEquals(10, $id);
                $this->assertFalse($unique);
                return true;
            }
        );

        $mock->enable();
        $result = $this->service->addMetadata("MetaTest", "key", "type", 10, false);
        $mock->disable();

        $this->assertTrue($wasCalled);

        $this->assertInstanceOf(MetadataInterface::class, $result);
        $this->assertEquals("MetaTest", $result->getValue());
        $this->assertEquals("key", $result->getKey());
        $this->assertEquals("type", $result->getMetaType());
        $this->assertEquals(10, $result->getId());
        $this->assertFalse($result->isDirty());
    }

    public function testAddMetadataUnique() {
        $metaTest  = new TestMetadata();
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use($metaTest, &$wasCalled) {
                $wasCalled = true;
                $this->assertTrue($unique);
                return true;
            }
        );

        $mock->enable();
        $result = $this->service->addMetadata(new TestMetadata(), null, null, 0,true);
        $mock->disable();

        $this->assertTrue($wasCalled);
        $this->assertInstanceOf(MetadataInterface::class, $result);
    }

    public function testAddMetadataUniqueString() {
        $metaTest  = new TestMetadata();
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use($metaTest, &$wasCalled) {
                $wasCalled = true;
                $this->assertTrue($unique);
                return true;
            }
        );

        $mock->enable();
        $result = $this->service->addMetadata("a", "b", "c", 0,true);
        $mock->disable();

        $this->assertTrue($wasCalled);
        $this->assertInstanceOf(MetadataInterface::class, $result);


    }

    public function testAddMetadataUniqueFailure() {

        $metaTest  = new TestMetadata();
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use($metaTest, &$wasCalled) {
                $wasCalled = true;
                $this->assertTrue($unique);
                return false;
            }
        );

        $ex = null;
        try {
            $mock->enable();
            $this->service->addMetadata(new TestMetadata(), null, null, null,true);
            $this->assertTrue($wasCalled);
        } catch (\Exception $exception) {
            $ex = $exception;
        }
        $mock->disable();

        $this->assertNotNull($ex);
        $this->assertInstanceOf(DuplicateEntityException::class, $ex);
        $this->assertEquals('Unique metadata already exist.', $ex->getMessage());

        $this->assertTrue($wasCalled);

    }

    public function testAddMetadataUniqueFailureString() {
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'add_metadata',
            function(string $type, int $id, string $key, $value, bool $unique) use(&$wasCalled) {
                $wasCalled = true;
                $this->assertTrue($unique);
                return false;
            }
        );

        $ex = null;
        try {
            $mock->enable();
            $this->service->addMetadata("a", "b", "c", 5, true);
            $this->assertTrue($wasCalled);
        } catch (\Exception $exception) {
            $ex = $exception;
        }
        $mock->disable();

        $this->assertNotNull($ex);
        $this->assertInstanceOf(DuplicateEntityException::class, $ex);
        $this->assertEquals('Unique metadata already exist.', $ex->getMessage());

        $this->assertTrue($wasCalled);

    }

    public function testUpdateMetadata() {
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'update_metadata',
            function(string $type, int $id, string $key, $value) use(&$wasCalled) {
                $this->assertEquals('value', $value);
                $this->assertEquals('key1', $key);
                $this->assertEquals(53, $id);
                $this->assertEquals(TestMetadata::META_TYPE_POST, $type);
                $wasCalled = true;
                return true;
            }
        );
        $mock->enable();
        $out = $this->service->updateMetadata(new TestMetadata(
            'key1',
            'value',
            MetadataInterface::META_TYPE_POST,
            53
        ));
        $mock->disable();
        $this->assertFalse($out->isDirty());
        $this->assertEquals(53, $out->getId());
    }

    public function testUpdateMetadataError() {
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'update_metadata',
            function() use(&$wasCalled) {
                $wasCalled = true;
                return false;
            }
        );
        $mock->enable();
        $ex = null;
        try {
            $this->service->updateMetadata(new TestMetadata());
        } catch (\Exception $exception) {
            $ex = $exception;
        }
        $mock->disable();

        $this->assertNotNull($ex);

        $this->assertInstanceOf(\Exception::class, $ex);
        $this->assertEquals('Unexpected error.', $ex->getMessage());

    }


    public function getMetadataAll() {
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'get_metadata',
            function(string $type, int $id, string $key) use(&$wasCalled) {
                $this->assertEquals(0, $id);
                $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $type);
                $this->assertEquals('abc', $key);
                $wasCalled = true;
                return ["a", "b", "c", "d"];
            }
        );


        $mock->enable();
        $values = $this->service->getMetadata('test', 0, 'abc');
        $mock->disable();
        $this->assertTrue($wasCalled);
        $this->assertCount(4, $values);

        /** @return MetadataInterface */
        $getVal = function(string $val) use($values) {
            return Arrays::first($values, function($v) use($val) {
                    return $v->getValue() === $val;
            });
        };

        $this->assertEquals(0, $getVal('a')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('a')->getMetaType());
        $this->assertEquals(0, $getVal('b')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('b')->getMetaType());
        $this->assertEquals(0, $getVal('c')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('c')->getMetaType());
        $this->assertEquals(0, $getVal('d')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('d')->getMetaType());
    }

    public function testGetMetadataWithoutKey() {
        $wasCalled = false;
        $mock      = new Mock(
            $this->namespace,
            'get_metadata',
            function(string $type, int $id, string $key) use(&$wasCalled) {
                $this->assertEquals(0, $id);
                $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $type);
                $this->assertEquals('', $key);
                $wasCalled = true;
                return ["a", "b", "c", "d"];
            }
        );


        $mock->enable();
        $values = $this->service->getMetadata(TestMetadata::META_TYPE_COMMENT, 0);
        $mock->disable();
        $this->assertTrue($wasCalled);
        $this->assertCount(4, $values);

        /** @return MetadataInterface */
        $getVal = function(string $val) use($values) {
            return Arrays::first($values, function($v) use($val) {
                return $v->getValue() === $val;
            });
        };

        $this->assertEquals(0, $getVal('a')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('a')->getMetaType());
        $this->assertEquals(0, $getVal('b')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('b')->getMetaType());
        $this->assertEquals(0, $getVal('c')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('c')->getMetaType());
        $this->assertEquals(0, $getVal('d')->getId());
        $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $getVal('d')->getMetaType());
    }

    public function testDeleteMetadataValid() {
        $wasCalled = false;
        $metadata  = new TestMetadata();
        $mock      = new Mock(
            $this->namespace,
            'delete_metadata',
            function($type, $id, $key, $value, $deleteAll) use(&$wasCalled) {
                $this->assertEquals(72, $id);
                $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $type);
                $this->assertEquals('testkey', $key);
                $this->assertEquals('testvalue', $value);
                $this->assertFalse($deleteAll);
                $wasCalled = true;
                return true;
            }
        );


        $mock->enable();
        $this->assertTrue($this->service->deleteMetadata($metadata));
        $mock->disable();
    }

    public function testDeleteMetadataInvalid() {
        $wasCalled = false;
        $metadata  = new TestMetadata();
        $mock      = new Mock(
            $this->namespace,
            'delete_metadata',
            function() use(&$wasCalled) {
                $wasCalled = true;
                return false;
            }
        );


        $mock->enable();
        $this->assertFalse($this->service->deleteMetadata($metadata));
        $mock->disable();
    }

    public function testDeleteAllMetadata() {

        $wasCalled = false;
        $metadata  = new TestMetadata();
        $mock      = new Mock(
            $this->namespace,
            'delete_metadata',
            function($type, $id, $key, $value, $deleteAll) use(&$wasCalled) {
                $this->assertEquals(10, $id);
                $this->assertEquals(TestMetadata::META_TYPE_COMMENT, $type);
                $this->assertEquals('testkey', $key);
                $this->assertEquals('abc', $value);
                $this->assertTrue($deleteAll);
                $wasCalled = true;
                return true;
            }
        );

        $mock->enable();
        $this->assertTrue($this->service->deleteAllMetadata(
            "testkey",
            10,
            MetadataInterface::META_TYPE_COMMENT,
            "abc"));
        $mock->disable();
    }

}

class TestMetadata implements MetadataInterface {

    public $value;
    public $key;
    public $type;
    public $id;
    public $dirty;


    public function __construct(string $key = "testkey",
                                 string $value = "testvalue",
                                 string $type = self::META_TYPE_COMMENT,
                                 int $id = 72) {

        $this->key   = $key;
        $this->value = $value;
        $this->type  = $type;
        $this->id    = $id;
        $this->dirty = true;
    }

    public function getKey(): string {
        return $this->key;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getMetaType(): string {
        return $this->type;
    }

    public function isDirty(): bool {
        return $this->dirty;
    }

    public function setDirtyState(bool $state) {
        $this->dirty = $state;
    }

}
