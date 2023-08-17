<?php

namespace Effectra\Core\Utils;

use Effectra\Fs\File;
use InvalidArgumentException;

/**
 * EnvManager - A utility class for managing environment variables stored in an .env file.
 *
 * This class provides methods to read, modify, and save environment variables stored in a
 * formatted .env file. It offers functionality to retrieve, set, delete, and save environment variables
 * in a user-friendly manner.
 */
class EnvManager
{

    /**
     * @var array The array containing the environment variables.
     */
    protected array $envs = [];

    /**
     * Constructor.
     *
     * Initializes the EnvManager with the specified environment file.
     *
     * @param string $file The path to the environment file. Defaults to '.env'.
     */
    public function __construct(protected string $file = '.env')
    {
        $this->envs = $this->read();
    }

    /**
     * Reads the environment file and parses its contents into an array.
     *
     * @return array The parsed environment variables.
     */
    private function read(): array
    {
        $envContent = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $envArray = [];

        foreach ($envContent as $line) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $envArray[$key] = $value;
            }
        }

        return $envArray;
    }

    /**
     * Retrieves all environment variables as an array.
     *
     * @return array All environment variables.
     */
    public function getAll(): array
    {
        return $this->envs;
    }

    /**
     * Retrieves the value of a specific environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param mixed $default The default value if the variable is not found.
     * @return mixed The value of the environment variable.
     */
    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->envs[$key];
        }

        return $default;
    }

    /**
     * Retrieves the values of multiple environment variables.
     *
     * @param iterable $keys An iterable containing the names of variables to retrieve.
     * @param mixed $default The default value if a variable is not found.
     * @return array An associative array of retrieved values, indexed by variable names.
     */
    public function getMultiple(iterable $keys, $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * Sets the value of a specific environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param mixed $value The value to set for the variable.
     * @return bool True on successful set, false otherwise.
     */
    public function set(string $key, $value): bool
    {
        $this->envs[$key] = $value;

        return true;
    }

    /**
     * Sets multiple environment variables at once.
     *
     * @param iterable $values An associative array of key-value pairs to set.
     * @param mixed $ttl (Optional) Time-to-live for the variables.
     * @return bool True on successful set of all variables, false otherwise.
     */
    public function setMultiple(iterable $values): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }
    /**
     * Checks if a specific environment variable exists.
     *
     * @param string $key The name of the environment variable.
     * @return bool True if the variable exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->envs[$key]);
    }

    /**
     * Deletes a specific environment variable.
     *
     * @param string $key The name of the environment variable.
     * @return bool True if the variable was deleted successfully, false otherwise.
     */
    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->envs[$key]);

            return true;
        }

        return false;
    }
    /**
     * Deletes multiple environment variables.
     *
     * @param iterable $keys An iterable containing the names of variables to delete.
     * @return bool True if all variables were deleted successfully, false otherwise.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $success = true;

        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Clears all environment variables.
     *
     * @return bool True on successful clear, false otherwise.
     */
    public function clear(): bool
    {
        $this->envs = [];

        return true;
    }

    /**
     * Validates a specific environment variable key.
     *
     * Throws an exception if the key is empty and already exists.
     *
     * @param string $key The name of the environment variable.
     * @return void
     * @throws InvalidArgumentException If the key is empty and already exists.
     */
    public function validateKey(string $key): void
    {
        if ($this->has($key) && $this->get($key) === '') {
            throw new InvalidArgumentException('The env key cannot be empty.');
        }
    }

    /**
     * Saves the modified environment variables back to the environment file.
     *
     * @return bool True on successful save, false otherwise.
     */
    public function save(): bool
    {
        return (bool) File::put($this->file, $this->toString());
    }

    /**
     * Converts the environment variables to a formatted string for saving.
     *
     * @return string The formatted string of environment variables.
     */
    private function toString(): string
    {
        $groupedEnvArray = [];

        foreach ($this->envs as $key => $value) {
            $parts = explode('_', $key, 2); // Split key using underscore as delimiter
            $group = $parts[0];

            if (!isset($groupedEnvArray[$group])) {
                $groupedEnvArray[$group] = [];
            }

            $groupedEnvArray[$group][$key] = $value;
        }

        $file = '';

        foreach ($groupedEnvArray as $group => $groupedVars) {
            $file .= implode("\n", array_map(function ($k, $v) {
                return "$k=$v";
            }, array_keys($groupedVars), $groupedVars));
            $file .= "\n\n";
        }

        return $file;
    }
}
