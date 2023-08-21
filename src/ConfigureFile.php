<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Fs\Directory;
use Effectra\Fs\Path;

/**
 * Represents a configuration file generator.
 */
class ConfigureFile
{
    /**
     * The class name used for the configuration file.
     *
     * @var string
     */
    protected string $className;

    /**
     * The additional path that comes after the main path for the configuration file.
     *
     * @var string
     */
    protected string $afterPath = "";

    /**
     * The namespace associated with the configuration file.
     *
     * @var string
     */
    protected string $namespace = "";

    /**
     * Constant to indicate whether to create a folder if it doesn't exist.
     *
     * @var bool
     */
    public const CREATE_FOLDER_IF_NOT_EXCITE = true;


    /**
     * Constructor.
     *
     * @param string $type The type of configuration file.
     * @param string $name The name of the configuration file.
     * @param string $path The path where the configuration file will be created.
     */
    public function __construct(
        protected string $type,
        protected string $name,
        protected string $path,
    ) {

        $this->name($name);
    }

    /**
     * Set the name of the configuration file.
     *
     * @param string $name The name of the configuration file.
     * @throws \Exception If the provided class name is not valid.
     */
    public function name(string $name): void
    {

        if ($this->hasSlash($name)) {
            $name_arr = explode('/', $name);
            $name = end($name_arr);
            array_pop($name_arr);

            $this->setAfterPath($name_arr);
            $this->setNameSpace($name_arr);
        }

        if (!str_contains($this->type, $name)) {
            $name = trim($name) . $this->type;
        }
        if (!$this->isValidClassName($name)) {
            throw new \Exception("Class name '$name' is not valid.", 1);
        }
        $this->className = ucfirst($name);
    }

    /**
     * Get the full file path of the configuration file.
     *
     * @param bool $create_folder Whether to create the folder if it doesn't exist.
     * @return string The full file path of the configuration file.
     */
    public function toFilePath($create_folder = false): string
    {
        $path = $this->toPath();

        if ($create_folder) {
            if (!Directory::isDirectory($path)) {
                Directory::make($path);
            }
        }

        return $path . $this->className . '.php';
    }

    /**
     * Get the class name of the configuration file.
     *
     * @return string The class name of the configuration file.
     */
    public function toClassName(): string
    {
        return $this->className;
    }

    /**
     * Check if the provided name contains a slash.
     *
     * @param string $name The name to check.
     * @return bool True if the name contains a slash, false otherwise.
     */
    private function hasSlash(string $name): bool
    {
        return (bool) strpos($name, '/');
    }

    /**
     * Get the path where the configuration file will be created.
     *
     * @return string The path where the configuration file will be created.
     */
    public function toPath(): string
    {
        return $this->path . Path::ds() .  $this->afterPath;
    }

    /**
     * Check if the provided class name is valid.
     *
     * @param string $className The class name to check.
     * @return bool True if the class name is valid, false otherwise.
     */
    private function isValidClassName($className)
    {
        // Check if the class name starts with a letter or underscore
        if (!preg_match('/^[a-zA-Z_]\w*$/', $className)) {
            return false;
        }

        // Check if the class name is a reserved keyword in PHP
        $reservedKeywords = ['abstract', 'and', 'array', /* ... */ 'yield', 'while'];
        if (in_array(strtolower($className), $reservedKeywords)) {
            return false;
        }

        return true;
    }

    /**
     * Get the current namespace for the configuration file.
     *
     * @return string The current namespace.
     */
    public function getNameSpace(): string
    {
        return $this->namespace;
    }

    /**
     * Set the namespace for the configuration file.
     *
     * @param array $subFolder An array representing the sub-folders of the namespace.
     */
    public function setNameSpace(array $subFolder): void
    {
        $this->namespace = '\\' . join('\\', $subFolder) ;
    }

    /**
     * Set the after path for the configuration file.
     *
     * @param array $subFolder An array representing the sub-folders for the after path.
     */
    public function setAfterPath(array $subFolder): void
    {
        $this->afterPath = join(Path::ds(), $subFolder) . Path::ds();
    }
}
