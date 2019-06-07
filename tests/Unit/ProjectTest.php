<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Checks if the project model has a path
     *
     * @return void
     */
    public function test_project_model_has_path(){

        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/'. $project->id,$project->path());
    }
}
