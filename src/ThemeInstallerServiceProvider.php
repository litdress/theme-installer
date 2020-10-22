<?php

namespace Theme;

use Illuminate\Support\ServiceProvider;
use Theme\Commands\MakeThemeCommand;
use Theme\Commands\ThemeInstallCommand;
use Theme\Commands\ThemeUseCommand;

class ThemeInstallerServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'MakeTheme'    => 'command.make.theme',
        'ThemeUse'     => 'command.theme.use',
        'ThemeInstall' => 'command.theme.install',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTheme();

        $this->registerCommands($this->commands);

        $this->applyUsedTheme();
    }

    /**
     * Apply used theme.
     *
     * @return void
     */
    public function applyUsedTheme()
    {
        if (! $theme = $this->app['theme']->using()) {
            $this->loadViewsFrom(resource_path('views'), 'theme');
        } else {
            $this->loadViewsFrom($this->app['theme']->getThemePath($theme) . '/views', 'theme');
        }
    }

    /**
     * Register the singleton.
     *
     * @return void
     */
    public function registerTheme()
    {
        $this->app->singleton('theme', function ($app) {
            return new Theme($app['files'], $app);
        });
    }

    /**
     * Register the given commands.
     *
     * @param  array $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMakeThemeCommand()
    {
        $this->app->singleton('command.make.theme', function ($app) {
            return new MakeThemeCommand($app['theme']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerThemeUseCommand()
    {
        $this->app->singleton('command.theme.use', function ($app) {
            return new ThemeUseCommand($app['theme']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerThemeInstallCommand()
    {
        $this->app->singleton('command.theme.install', function ($app) {
            return new ThemeInstallCommand($app['theme']);
        });
    }
}
