<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Fs\Directory;
use Effectra\Fs\File;

class Localization
{
    private string $language;

    private array $translations = [];

    public function __construct(string $language)
    {
        $this->language = $language;
        $this->loadTranslations();
    }

    private function loadTranslations() :void
    {
        $dir_path = Application::resourcesPath('translations');

        $files = Directory::files($dir_path, true);

        $translations = [];

        foreach ($files as $file) {
            $fileContent = require $file;
            $lang = File::name($file);
            $translations[$lang] = $fileContent;
        }

        if (isset($translations[$this->language])) {
            $this->translations = $translations[$this->language];
        } else {
            $this->translations = $translations['en']; // Default to English
        }
    }

    public function translate(string $key)
    {
        if (isset($this->translations[$key])) {
            return $this->translations[$key];
        }

        // If the translation for the key is not found, you can return the key itself
        return $key;
    }
}
