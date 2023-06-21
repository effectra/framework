<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Config\ConfigFile;
use Effectra\Core\View\ViewConfigs;
use Effectra\Fs\Path;
use Effectra\Minifyer\Minify;
use Effectra\Renova\Reader;
use Effectra\Renova\Render;

/**
 * Class View
 *
 * Provides functionality for rendering views with added features such as linking stylesheets and adding scripts.
 */
class View
{
    protected static Application $appStatic;

    /**
     * View constructor.
     *
     * @param Reader $reader The reader object for reading view files.
     * @param ConfigFile $config The configuration file object.
     */
    public function __construct(
        protected Reader $reader,
        protected ConfigFile $config
    ) {

    }

    /**
     * Register content for a specific type.
     *
     * @param array $content The content to register.
     * @param string $type The type of content to register (e.g., 'functions').
     * @return void
     */
    public function register(array $content, string $type = 'functions')
    {
        // Implementation
    }

    /**
     * Render a view with optional data.
     *
     * @param string $view The path to the view file.
     * @param mixed $data The optional data to pass to the view.
     * @return string The rendered HTML content.
     */
    public function render(string $view, $data = [])
    {
        $path = Application::viewPath(Path::format($view) . '.php');

        $content = (new Render(
            $path,
            $data,
            ViewConfigs::templateFunctions(),
            ViewConfigs::templateGlobalVars(),
            $this->reader
        ))->send();

        return Minify::html($content);
    }

    /**
     * Render a specific section of a view with optional data.
     *
     * @param string $view The path to the view file.
     * @param mixed $data The optional data to pass to the view.
     * @return string The rendered HTML content of the specified section.
     */
    public function renderSection(string $view, $data = [])
    {
        $path = Application::viewPath(Path::format($view) . '.php');
        return (new Render(
            $path,
            $data,
            ViewConfigs::templateFunctions(),
            ViewConfigs::templateGlobalVars(),
            $this->reader
        ))->send();
    }
}
