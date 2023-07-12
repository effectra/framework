[33mcommit 703224ff9842079a29ac3a7616750843a1d8fc58[m[33m ([m[1;36mHEAD -> [m[1;32mmain[m[33m)[m
Author: Mohammed Taha [BMT] <bmt.mohammedtaha@gmail.com>
Date:   Fri Jul 7 12:54:42 2023 +0200

    Add "getConfig, getLang, resourcesPath" methods

[1mdiff --git a/src/Application.php b/src/Application.php[m
[1mindex c6ede2a..b13b848 100644[m
[1m--- a/src/Application.php[m
[1m+++ b/src/Application.php[m
[36m@@ -6,6 +6,7 @@[m [mnamespace Effectra\Core;[m
 [m
 use App\AppCore;[m
 use DI\Container;[m
[32m+[m[32muse Effectra\Config\ConfigFile;[m
 use Effectra\Core\Console\AppConsole;[m
 use Effectra\Core\Error\AppError;[m
 use Effectra\Core\Http\Cors;[m
[36m@@ -88,6 +89,24 @@[m [mclass Application[m
         static::$APP_PATH = $path;[m
     }[m
 [m
[32m+[m[32m    /**[m
[32m+[m[32m     * Get the app configuration.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @return array The app configuration.[m
[32m+[m[32m     */[m
[32m+[m[32m    private static function getConfig()[m
[32m+[m[32m    {[m
[32m+[m[32m        $file = Application::configPath('app.php');[m
[32m+[m[32m        $configFile = new ConfigFile($file);[m
[32m+[m[32m        $config = $configFile->read();[m
[32m+[m
[32m+[m[32m        return $config;[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public static function getLang() : string {[m
[32m+[m[32m        return static::getConfig()['lang'] ?? 'en';[m
[32m+[m[32m    }[m
[32m+[m
     /**[m
      * Get the container instance.[m
      *[m
[36m@@ -184,6 +203,17 @@[m [mclass Application[m
         return static::$APP_PATH . Path::ds() . 'public' . Path::ds() . $path;[m
     }[m
 [m
[32m+[m[32m    /**[m
[32m+[m[32m     * Get the resources path.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @param string $path The additional path within the resources directory.[m
[32m+[m[32m     * @return string The full path.[m
[32m+[m[32m     */[m
[32m+[m[32m    public static function resourcesPath(string $path = ''): string[m
[32m+[m[32m    {[m
[32m+[m[32m        return static::$APP_PATH . Path::ds() . 'resources' . Path::ds() . $path;[m
[32m+[m[32m    }[m
[32m+[m
     /**[m
      * Get the storage path.[m
      *[m
