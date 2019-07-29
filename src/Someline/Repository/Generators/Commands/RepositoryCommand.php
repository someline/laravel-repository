<?php
namespace Someline\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Someline\Repository\Generators\FileAlreadyExistsException;
use Someline\Repository\Generators\MigrationGenerator;
use Someline\Repository\Generators\ModelGenerator;
use Someline\Repository\Generators\RepositoryEloquentGenerator;
use Someline\Repository\Generators\RepositoryInterfaceGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryCommand extends CommandBase
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'repository:create';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * @var Collection
     */
    protected $generators = null;


    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->generators = new Collection();

        $name = $this->argument('name');
        $name = str_replace([
            "\\",
            '/'
        ], '', $name);
        $this->generators->push(new MigrationGenerator([
            'name'   => 'create_' . snake_case(str_plural($name)) . '_table',
            'fields' => $this->option('fillable'),
            'force'  => $this->option('force'),
        ]));

        $modelGenerator = new ModelGenerator([
            'name'     => $this->argument('name'),
            'fillable' => $this->option('fillable'),
            'force'    => $this->option('force')
        ]);

        $this->generators->push($modelGenerator);

        foreach ($this->generators as $generator) {
            $generator->run();
        }

        $model = $modelGenerator->getRootNamespace() . '\\' . $modelGenerator->getName();
        $model = str_replace([
            "\\",
            '/'
        ], '\\', $model);

        // Generate a controller resource
        $this->call('repository:transformer', [
            'name' => $this->argument('name'),
            '--force' => $this->option('force')
        ]);

        try {
            (new RepositoryEloquentGenerator([
                'name'      => $this->argument('name'),
                'force'     => $this->option('force'),
                'model'     => $model
            ]))->run();
            $this->info("Repository created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');

            return false;
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ]
        ];
    }
}
