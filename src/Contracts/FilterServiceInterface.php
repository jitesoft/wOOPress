<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  FilterServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

/**
 * Interface for filter services.
 * Filter services are intended to work with the WordPress Filters.
 */
interface FilterServiceInterface {

    /**
     * Add a filter to a given tag.
     * The invoke method of the filter (or in case of a callable, the function itself) will be called when the
     * filter is applied. The passed values will be the name of the filter and any arguments passed by the
     * callee of the apply method.
     *
     * @param string                   $tag         Name of the tag to apply the filter to.
     * @param FilterInterface|callable $filter      Filter as callable or FilterInterface implementation.
     * @param int                      $priority    Priority of the attached filter.
     * @param int                      $maxArgCount Maximum number of arguments the callback will accept.
     * @return int Handle for the filter. Can later be used in the `remove` method to remove the filter.
     */
    public function add(string $tag, $filter, int $priority = 10, int $maxArgCount = -1) : int;

    /**
     * Remove a filter from a given action.
     *
     * @param string  $tag    Tag to remove the filter from.
     * @param int     $handle Handle of the filter to remove.
     * @return bool Result.
     */
    public function remove(string $tag, int $handle) : bool;

    /**
     * Invokes all filters of a given tag.
     *
     * @param string $tag      Tag to invoke.
     * @param mixed  $args,... Arguments to pass to the filter.
     * @return bool Result.
     */
    public function apply(string $tag, ...$args) : bool;

}
