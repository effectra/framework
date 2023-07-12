<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Fs\Directory;
use Effectra\Fs\File;

/**
 * The Localization class handles translation of strings based on the selected language.
 */
class Localization
{
    /** @var string The selected language. */
    private string $language;

    /** @var array The translations for the selected language. */
    private array $translations = [];

    /**
     * Localization constructor.
     *
     * @param string $language The selected language.
     */
    public function __construct(string $language)
    {
        $this->language = $language;
        $this->loadTranslations();
    }

    /**
     * Loads the translations for the selected language.
     *
     * @return void
     */
    private function loadTranslations(): void
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

    /**
     * Translates a string based on the selected language.
     *
     * @param string $key The translation key.
     * @return string The translated string or the key itself if the translation is not found.
     */
    public function translate(string $key): string
    {
        if (isset($this->translations[$key])) {
            return $this->translations[$key];
        }

        // If the translation for the key is not found, you can return the key itself
        return $key;
    }
}
