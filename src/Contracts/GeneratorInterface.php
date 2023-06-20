<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

/**
 * Interface GeneratorInterface
 *
 * Represents a code generator that can generate files or code snippets.
 */
interface GeneratorInterface
{
    /**
     * Generates a file or code snippet based on the provided options.
     *
     * @param string $className The name of the class to generate.
     * @param string $savePath The path where the generated file should be saved.
     * @param array $options Additional options for the generator.
     * @return int|false Returns the number of bytes written to the file or false on failure.
     */
    public static function make(string $className, string $savePath, array $options = []): int|false;
}
