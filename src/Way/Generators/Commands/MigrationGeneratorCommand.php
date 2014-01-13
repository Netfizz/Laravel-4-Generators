<?php namespace Way\Generators\Commands;

use Way\Generators\Generators\MigrationGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrationGeneratorCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new migration.';

    /**
     * Model generator instance.
     *
     * @var Way\Generators\Generators\MigrationGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MigrationGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->argument('name');
        $path = $this->getPath();
        $fields = $this->option('fields');

        $created = $this->generator
                        ->parse($name, $fields)
                        ->make($path, null);

        $this->call('dump-autoload');

        $this->printResult($created, $path);
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
            $path = $this->laravel['path.base'] . "/workbench/{$bench}/src/migrations";
        }


        return $path . '/' . ucwords($this->argument('name')) . '.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the migration to generate.'),
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
            array('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations folder', app_path() . '/database/migrations'),
            array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to migrate.', null),
            array('fields', null, InputOption::VALUE_OPTIONAL, 'Table fields', null)
        );
    }

}
