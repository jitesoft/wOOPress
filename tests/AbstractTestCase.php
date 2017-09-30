<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AbstractTestCase.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Tests;

use Jitesoft\wOOPress\Tests\DI\DependencyContainer;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase {
    use MockeryPHPUnitIntegration;

    protected function setUp() {
        parent::setUp();
        DependencyContainer::initialize();
    }

}
