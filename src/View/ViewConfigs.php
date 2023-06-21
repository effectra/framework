<?php

namespace Effectra\Core\View;

use Effectra\Core\Application;
use Effectra\Core\EncoreProvider\WebpackEncore;
use Effectra\Core\Request;
use Effectra\Fs\Path;
use Effectra\Link\Link;
use Effectra\Link\LinkProvider;
use Effectra\Renova\Reader;

/**
 * The ViewConfigs class provides configurations for template functions and global variables used in views.
 */
class ViewConfigs
{
    /**
     * Get the template functions configuration.
     *
     * @return array The template functions configuration.
     */
    public static function templateFunctions(): array
    {
        return [
            ['section' => function (string $name) {
                /** @var \Effectra\Core\View $view */
                $view = Application::container()->get(View::class);
                return $view->renderSection($name);
            }],
            ['url' => function ($path = '') {
                return Request::url() . (string) $path;
            }],
            ['app_url' => function ($path = '') {
                return env('APP_URL', '/') . (string) $path;
            }],
            ['link' => function ($path = '') {
                return LinkProvider::withHTML(
                    [new Link(href: $path, rels: ['rel' => 'collection'])]
                );
            }],
            ['include' => function ($file, $data = []) {
                $file = Path::format($file);
                $path = Application::viewPath($file . '.php');
                return (new Reader())->file($path, $data);
            }]
        ];
    }

    /**
     * Get the template global variables configuration.
     *
     * @return array The template global variables configuration.
     */
    public static function templateGlobalVars(): array
    {
        $webpackEncore = new WebpackEncore();

        return [
            // ['CSRF' => (string) Application::container()->get(Csrf::class)->insertHiddenToken()],
            ['APP_NAME' => $_ENV['APP_NAME'] ?? APP_NAME],
            ['WEB_ENCORE_JS' => $webpackEncore->scriptTags('app')],
            ['WEB_ENCORE_CSS' => $webpackEncore->linkTags('app')],
        ];
    }
}
