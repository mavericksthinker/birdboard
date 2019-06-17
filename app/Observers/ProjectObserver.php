<?php

namespace App\Observers;

use App\Project;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        // Creates an activity when the project is created
        $project->recordActivity('created');

    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        // Creates an activity when the project is updated
        $project->recordActivity('updated');
    }

    public function updating(Project $project){

        $project->old = $project->getOriginal();

    }

    /**
     * Handle the project "deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the project "restored" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the project "force deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }


}
