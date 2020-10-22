<?php

namespace Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;
use InvalidArgumentException;

class Theme
{
    /**
     * Theme installer instance.
     *
     * @var Installer
     */
    protected $installer;

    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Laravel container.
     *
     * @var Application
     */
    protected $laravel;

    /**
     * The theme being used.
     *
     * @var string
     */
    protected $theme;

    /**
     * Create new Theme instance.
     *
     * @param  Filesystem  $files
     * @param  Application $laravel
     * @return void
     */
    public function __construct(Filesystem $files, Application $laravel)
    {
        $this->files = $files;
        $this->laravel = $laravel;
    }

    /**
     * Install theme by the given name.
     *
     * @param  string $name
     * @param  bool   $symlink
     * @return void
     */
    public function install($name, $symlink = false)
    {
        if ($this->installed($name)) {
            return;
        }

        if (! $this->themePackageExists($name)) {
            shell_exec('composer require ' . $name);
        }

        if (! $packagePath = $this->getThemePackagePath($name)) {
            throw new InvalidArgumentException("Unable to install theme [{$name}]");
        }

        $path = $this->getThemePath($name);

        $this->files->ensureDirectoryExists($path, 0755, true);
        $this->files->ensureDirectoryExists(public_path('themes/' . $name), 0755, true);

        if ($symlink) {
            $this->files->deleteDirectory($path);
            $this->files->deleteDirectory(public_path('themes/' . $name));
            $this->files->link($packagePath . '/resources', $path);
            $this->files->link($packagePath . '/assets', public_path('themes/' . $name));
        } else {
            $this->files->copyDirectory($packagePath . '/resources', $path);
            $this->files->copyDirectory($packagePath . '/assets', public_path('themes/' . $name));
        }
    }

    /**
     * Use theme with the given name.
     *
     * @param  string $name
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function use($name)
    {
        if ($name == 'default') {
            return $this->files->delete($this->getManifestPath());
        }

        if (! $this->installed($name)) {
            throw new InvalidArgumentException("Theme [{$name}] not installed.");
        }

        $this->files->put(
            $this->getManifestPath(),
            "<?php return '{$name}';"
        );
    }

    /**
     * Gets the name of the theme being used.
     *
     * @return string
     */
    public function using()
    {
        if ($this->theme) {
            return $this->theme;
        }

        if (! $this->files->exists($path = $this->getManifestPath())) {
            return;
        }

        if (! realpath($this->getThemePath($theme = require_once $path))) {
            return;
        }

        return $this->theme = $theme;
    }

    /**
     * Apply theme for the current lifecycle.
     *
     * @param  string $name
     * @return void
     */
    public function apply($name)
    {
        View::addNamespace('theme', $this->getThemePath($name) . '/views');
        // $this->laravel->bind('view.finder', function ($app) use ($name) {
        //     $paths = array_merge(
        //         [$this->getThemePath($name) . '/views'],
        //         $app['config']['view.paths'],
        //     );

        //     return new FileViewFinder($app['files'], $paths);
        // });
    }

    /**
     * Determines if a theme with the given name is installed.
     *
     * @param  string $name
     * @return bool
     */
    public function installed($name)
    {
        return (bool) realpath($this->getThemePath($name));
    }

    /**
     * Make new theme.
     *
     * @param  string $name
     * @return void
     */
    public function make($name)
    {
        $path = $this->getThemeDevelopmentPath($name);

        $this->files->ensureDirectoryExists($path, 0755, true);

        // Skip if theme has already been created.
        if ($this->files->exists($path . '/resources')) {
            return;
        }

        $this->files->copyDirectory(__DIR__ . '/../skeleton', $path);
    }

    /**
     * Get theme development path.
     *
     * @param  string $name
     * @return string
     */
    protected function getThemeDevelopmentPath($name)
    {
        return base_path('themes/' . $name);
    }

    /**
     * Get theme path for the given name.
     *
     * @param  string $name
     * @return void
     */
    public function getThemePath($name)
    {
        return resource_path("themes/{$name}");
    }

    /**
     * Get theme package path for the given name.
     *
     * @param  string $name
     * @return void
     */
    protected function getThemePackagePath($name)
    {
        // Using "themes/vendor/name" when exists.
        if ($path = realpath($this->getThemeDevelopmentPath($name))) {
            return $path;
        }

        // Otherwise use vendor path.
        return base_path('vendor/' . $name);
    }

    /**
     * Determines if a theme package exists.
     *
     * @param  string $name
     * @return bool
     */
    protected function themePackageExists($name)
    {
        return realpath(
            $this->getThemePackagePath($name)
        );
    }

    /**
     * Gets the theme manifest path.
     *
     * @return string
     */
    protected function getManifestPath()
    {
        return base_path('bootstrap/cache/theme.php');
    }
}
