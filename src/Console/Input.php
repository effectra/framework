<?php

declare(strict_types=1);

namespace Effectra\Core\Console;

/**
 * Class Input
 *
 * A utility class for capturing and processing command line inputs, flags, and options.
 */
class Input
{
    /**
     * @var array An array containing the command line arguments.
     */
    private array $arguments;

    /**
     * Input constructor.
     *
     * Initializes the Input class by capturing and storing command line arguments.
     */
    public function __construct()
    {
        $this->arguments = array_slice($_SERVER['argv'], 1);
    }

    /**
     * Get the full command as a string.
     *
     * @return string The full command including arguments, flags, and options.
     */
    public function getFullCommand(): string
    {
        return implode(' ', $this->arguments);
    }

    /**
     * Get the array of command line arguments.
     *
     * @return array The array of command line arguments.
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Check if a specific flag exists in the command line inputs.
     *
     * @param string $flag The flag to check for.
     * @return bool True if the flag exists, false otherwise.
     */
    public function hasFlag(string $flag): bool
    {
        return in_array($flag, $this->arguments);
    }

    /**
     * Get the value of a specific option from the command line inputs.
     *
     * @param string $option The option to retrieve the value for.
     * @return string|null The value of the option if found, or null if not found.
     */
    public function getOption(string $option): ?string
    {
        $optionIndex = array_search($option, $this->arguments);
        if ($optionIndex !== false && isset($this->arguments[$optionIndex + 1])) {
            return $this->arguments[$optionIndex + 1];
        }
        return null;
    }
}
