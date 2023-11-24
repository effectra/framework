<?php

namespace Effectra\Core\View;

use Effectra\Core\Application;
use Effectra\Core\EncoreProvider\WebpackEncore;
use Effectra\Core\Facades\Request;
use Effectra\Fs\Path;
use Effectra\Link\Link;
use Effectra\Link\LinkProvider;
use Effectra\Renova\Reader;
use Effectra\Security\Csrf;

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
            }],
            ['web_encore_js' => function ($file) {
                try {
                    $webpackEncore = new WebpackEncore();
                  return  $webpackEncore->scriptTags($file);
                } catch (\Exception $e) {
                    return 'WEB_ENCORE_JS: error('. $e->getMessage() .')';
                }
            }],
            ['web_encore_css' => function ($file) {
                try {
                    $webpackEncore = new WebpackEncore();
                  return  $webpackEncore->linkTags($file);
                } catch (\Exception $e) {
                    return 'WEB_ENCORE_CSS error('. $e->getMessage() .')';
                }
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

        return [
            ['CSRF' => (string) Application::container()->get(Csrf::class)->insertHiddenToken()],
            ['APP_NAME' => $_ENV['APP_NAME'] ?? ''],
            
        ];
    }
}
