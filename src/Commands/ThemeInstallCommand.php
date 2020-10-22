<?php

namespace Theme\Commands;

use Illuminate\Console\Command;
use Theme\Theme;

class ThemeInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:install {name}
                            {--symlink : Whether a symlink should be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use theme with the given name.';

    /**
     * Theme instance.
     *
     * @var Theme
     */
    protected $theme;

    /**
     * Create new MakeThemeCommand instance.
     *
     * @param Theme $theme
     */
    public function __construct(Theme $theme)
    {
        parent::__construct();

        $this->theme = $theme;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->theme->installed($name)) {
            return $this->error('Theme [$name] already installed.');
        }

        $this->theme->install($name, $this->option('symlink'));

        $this->line("Installed theme [<info>{$name}</info>] into [<info>resources/themes</info>].");
    }
}
