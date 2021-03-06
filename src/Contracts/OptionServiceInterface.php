<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionServiceInterface.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Contracts;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * Contract for Option services.
 * Option services are made to handle creation, updating, removal and fetching of options.
 */
interface OptionServiceInterface {

    /**
     * Create a new option.
     * The option will be saved and returned if successful else null will be returned.
     *
     * @param string|OptionInterface $option   Option  as object or the name of the option.
     * @param mixed                  $value    Value of the option (max 2^32 bytes). If the passed option is
     *                                         an OptionInterface object, this can be left null and will be ignored.
     * @param bool                   $autoload If option should be auto-loaded or not.
     * @return OptionInterface|null The created and saved option or null on failure.
     *
     * @throws InvalidArgumentException on invalid option argument.
     * @throws OutOfBoundsException if value is to great.
     */
    public function add($option, $value = null, bool $autoload = true) : ?OptionInterface;

    /**
     * Remove a given option from the database.
     *
     * @param string|OptionInterface $option Option as object or the name of the option.
     *
     * @return bool Result.
     *
     * @throws InvalidArgumentException on invalid option argument.
     */
    public function remove($option) : bool;

    /**
     * Get a option from the database.
     *
     * @param string $option   Name of the option to fetch.
     *
     * @return OptionInterface|null The fetched option or null if none found.
     */
    public function get(string $option) : ?OptionInterface;

    /**
     * Update a given option in the database.
     * If the option does not exist, a new option with the given name will be created.
     *
     * @param string|OptionInterface $option Option as object or the name of option to update.
     * @param mixed                  $value  New option value (max 2^32 bytes). If the passed option is an
     *                                       OptionInterface object, this can be left null and will be ignored.
     * @return OptionInterface|null The updated option or null in case of failure.
     *
     * @throws InvalidArgumentException on invalid option argument.
     * @throws OutOfBoundsException if value is to great.
     */
    public function update($option, $value = null) : ?OptionInterface;

}
