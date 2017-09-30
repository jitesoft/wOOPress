<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  DependencyContainer.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress\Tests\DI;

use ReflectionClass;
use ReflectionParameter;

/**
 * Simple container for bindings just used for tests.
 */
class DependencyContainer {

    private static $container;
    private static $instances;

    public static function initialize() {
        self::$instances = [];
        self::$container = [];
        $dir             = dirname(__FILE__);
        $bindings        = require_once $dir . "/Bindings.php";

        foreach ($bindings as $abstract => $concrete) {
            self::$container[$abstract] = $concrete;
        }
    }


    private static function getTypeHint(ReflectionParameter $param) {
        if ($param->getClass()) {
            return $param->getClass()->getName();
        }
        return null;
    }

    private static function initClass($concrete) {
        $class = new ReflectionClass($concrete);
        $out   = null;
        if ($class->getConstructor() !== null) {

            $ctr    = $class->getConstructor();
            $params = $ctr->getParameters();

            $inParam = [];
            foreach ($params as $param) {
                $type = self::getTypeHint($param);
                $get  = self::get($type);

                if ($get === null) {
                    $get = self::initClass($type);
                }
                $inParam[] = $get;
            }

            $out = $class->newInstanceArgs($inParam);
        } else {
            $out = $class->newInstanceWithoutConstructor();
        }
        self::$instances[$concrete] = $out;
        return self::get($concrete);
    }

    public static function get($abstract) {
        // First check if the abstract is actually a class and we already have an instance.
        // If so, we want to return that instance.
        if (array_key_exists($abstract, self::$instances)) {
            // We had a instance for the given class.
            return self::$instances[$abstract];
        }
        // Else check if there is a abstract value which we could fetch class from.
        if (array_key_exists($abstract, self::$container)) {
            // If class did exist as a abstract and its mapped in the instances container,
            // we return the instance of the bound abstract.
            if (array_key_exists(self::$container[$abstract], self::$instances)) {
                return self::$instances[self::$container[$abstract]];
            }
            // Else we try to create a new instance of the bound class.
            return self::initClass(self::$container[$abstract]);
        }
        // At this point there is nothing the DI can do, bai! :P
        return null;
    }

    public static function set($abstract, $concrete, bool $isInstance = false) {
        if ($isInstance === true) {
            self::$container[$abstract] = $abstract;
            self::$instances[$abstract] = $concrete;
            return true;
        }

        self::$container[$abstract] = $concrete;
        return true;
    }

}
