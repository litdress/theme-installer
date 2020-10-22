<?php

namespace Theme\Commands;

use Illuminate\Console\Command;
use Theme\Theme;

class MakeThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:theme {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make new theme.';

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
        $this->theme->make(
            $name = $this->argument('name')
        );

        $this->line("Created theme [<info>{$name}</info>] in themes/{$name}.");
    }
}
