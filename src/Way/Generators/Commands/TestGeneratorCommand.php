<?php namespace Way\Generators\Commands;

use Way\Generators\Generators\TestGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestGeneratorCommand extends BaseGeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a PHPUnit test class.';

    /**
     * Test generator instance.
     *
     * @var Way\Generators\Generators\TestGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TestGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Get the path to the file that should be generated.
     *
     * @return string
     */
    protected function getPath()
    {
        $path = $this->option('path');

        $bench = $this->input->getOption('bench');

        // Finally we will check for the workbench option, which is a shortcut into
        // specifying the full path for a "workbench" project. Workbenches allow
        // developers to develop packages along side a "standard" app install.
        if ( ! is_null($bench))
        {
            $path = $this->laravel['path.base'] . "/workbench/{$bench}/tests";
        }


        return $path . '/' . studly_case($this->argument('name')) . '.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the test to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
           array('path', null, InputOption::VALUE_OPTIONAL, 'Path to tests directory.', app_path() . '/tests'),
           array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to migrate.', null),
           array('template', null, InputOption::VALUE_OPTIONAL, 'Path to template.', __DIR__.'/../Generators/templates/test.txt'),
        );
    }

}
