<?php

namespace ThrottleStudio\ActivityStream\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use ThrottleStudio\ActivityStream\Jobs\CreateActivity;
use ThrottleStudio\ActivityStream\Models\Activity;
use ThrottleStudio\ActivityStream\Models\Feed;

class PopulateActivitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stream:populate-activities {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the stream for a given model.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->getNameInput();

        if (!class_exists($model))
        {
            $this->error('The class "'.$model.'" doesn\'t exist.');
            return;
        }

        // Create new instance of the model
        $class = new $model();

        // truncate all activities made by this class
        $activities = Activity::where('object_type', $class->getMorphClass())->get();
        Activity::where('object_type', $class->getMorphClass())->delete();

        // Delete all related feed data
        Feed::whereIn('activity_id', $activities->pluck('id'))->delete();

        // Get all model results
        $results = $class->get();

        // Create an activity for each model
        foreach($results as $result)
        {
            dispatch(new CreateActivity(($result)));
        }

        $this->info('Your activities have been created.!');

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
