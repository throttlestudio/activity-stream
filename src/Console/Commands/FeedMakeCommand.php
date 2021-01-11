<?php

namespace ThrottleStudio\ActivityStream\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class FeedMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'stream:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new custom feed';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Feed';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/feed_model.stub';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace().'Feeds';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/Feeds'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the id for the model.
     *
     * @return string
     */
    protected function getId()
    {
        return $this->option('id') ?? Str::uuid();
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'ID'],
            [$this->getNamespace($name), $this->getId()],
            $stub
        );

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['id', 'i', InputOption::VALUE_OPTIONAL, 'Set the ID for the model'],
        ];
    }
}
