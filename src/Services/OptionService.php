<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\OptionInterface;
use Jitesoft\wOOPress\Contracts\OptionServiceInterface;
use OutOfBoundsException;

class OptionService implements OptionServiceInterface {

    /**
     * Create a new option.
     * The option will be saved and returned if successful else null will be returned.
     *
     * @param string|OptionInterface $option Option  as object or the name of the option.
     * @param mixed $value Value of the option (max 2^32 bytes). If the passed option is
     *                                         an OptionInterface object, this can be left null and will be ignored.
     * @param bool $autoload If option should be auto-loaded or not.
     * @return OptionInterface|null The created and saved option or null on failure.
     *
     * @throws InvalidArgumentException on invalid option argument.
     * @throws OutOfBoundsException if value is to great.
     */
    public function add($option, $value = null, bool $autoload = true): ?OptionInterface {
        // TODO: Implement add() method.
    }

    /**
     * Remove a given option from the database.
     *
     * @param string|OptionInterface $option Option as object or the name of the option.
     *
     * @return bool Result.
     *
     * @throws InvalidArgumentException on invalid option argument.
     */
    public function remove($option): bool {
        // TODO: Implement remove() method.
    }

    /**
     * Get a option from the database.
     *
     * @param string $option Name of the option to fetch.
     *
     * @return OptionInterface|null The fetched option or null if none found.
     */
    public function get(string $option): ?OptionInterface {
        // TODO: Implement get() method.
    }

    /**
     * Update a given option in the database.
     * If the option does not exist, a new option with the given name will be created.
     *
     * @param string|OptionInterface $option Option as object or the name of option to update.
     * @param mixed $value New option value (max 2^32 bytes). If the passed option is an
     *                                       OptionInterface object, this can be left null and will be ignored.
     * @return OptionInterface|null The updated option or null in case of failure.
     *
     * @throws InvalidArgumentException on invalid option argument.
     * @throws OutOfBoundsException if value is to great.
     */
    public function update($option, $value = null): ?OptionInterface {
        // TODO: Implement update() method.
    }
}
