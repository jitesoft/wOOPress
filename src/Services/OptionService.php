<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  OptionService.php - Part of the woopress project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\wOOPress\Services;

use InvalidArgumentException;
use Jitesoft\wOOPress\Contracts\OptionInterface;
use Jitesoft\wOOPress\Contracts\OptionServiceInterface;
use Jitesoft\wOOPress\Option;
use OutOfBoundsException;

class OptionService implements OptionServiceInterface {
    private const INVALID_OPTION_MESSAGE     = 'The argument $option has to be either a string' .
                                               ' or derive from OptionInterface.';
    private const INVALID_VALUE_SIZE_MESSAGE = 'Invalid value size, maximum size is 2^32 bytes.';
    private const MAX_VALUE_SIZE             = 2**32;



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
        if (!is_string($option) && !($option instanceof OptionInterface)) {
            throw new InvalidArgumentException(self::INVALID_OPTION_MESSAGE);
        }

        if ($option instanceof OptionInterface) {
            $value  = $option->getValue();
            $option = $option->getName();
        }

        // Check with strlen how large in bytes the string is.
        if (self::MAX_VALUE_SIZE < strlen($value)) {
            throw new OutOfBoundsException(self::INVALID_VALUE_SIZE_MESSAGE);
        }

        if (add_option($option, $value, "", $autoload ? "yes" : "no")) {
            $out = new Option($option, $value);
            $out->setDirty(false);
            return $out;
        }


        return null;
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
        if (!is_string($option) && !($option instanceof OptionInterface)) {
            throw new InvalidArgumentException(self::INVALID_OPTION_MESSAGE);
        }

        if ($option instanceof OptionInterface) {
            $option = $option->getName();
        }

        return delete_option($option);
    }

    /**
     * Get a option from the database.
     *
     * @param string $option Name of the option to fetch.
     *
     * @return OptionInterface|null The fetched option or null if none found.
     */
    public function get(string $option): ?OptionInterface {
        $result = get_option($option, null);
        if ($result !== null) {
            $out = new Option($option, $result);
            $out->setDirty(false);
            return $out;
        }

        return null;
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
        if (!is_string($option) && !($option instanceof OptionInterface)) {
            throw new InvalidArgumentException(self::INVALID_OPTION_MESSAGE);
        }

        if ($option instanceof OptionInterface) {
            $value  = $option->getValue();
            $option = $option->getName();
        }

        // Check with strlen how large in bytes the string is.
        if (self::MAX_VALUE_SIZE < strlen($value)) {
            throw new OutOfBoundsException(self::INVALID_VALUE_SIZE_MESSAGE);
        }

        $result = update_option($option, $value);
        if ($result === true) {
            $out = new Option($option, $value);
            $out->setDirty(false);
            return $out;
        }

        return null;

    }
}
