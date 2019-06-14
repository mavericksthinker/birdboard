<?php

namespace Tests\Feature;

use App\Project;
// Its like a magic in laravel to convert the ProjectFactory to Facades add Facades in the beginning of it
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_guest_cant_add_task(){

        $project = factory('App\Project')->create();

        $this->post($project->path().'/tasks')->assertRedirect('login');

    }

    public function test_only_owner_of_the_project_can_add_tasks_to_their_projects(){

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
             ->assertStatus(403);

        $this->assertDatabaseMissing('tasks',['body'=>'Test task']);
    }


    public function test_a_project_can_have_tasks()
    {
        //$this->withoutExceptionHandling();
        // Helper functions included from the TestCase.php to avoid repetition of code
//        $this->signIn();
//
//        $project = auth()->user()->projects()->create(
//            factory(Project::class)->raw()
//        );

        //$project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        //$this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->post($project->path(). '/tasks/',['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }


    public function test_a_project_requires_a_body(){

//        $this->signIn();
//
//        $project = auth()->user()->projects()->create(
//            factory(Project::class)->raw()
//        );

        $project = ProjectFactory::create();

        $attributes = factory('App\Task')->raw(['body'=>'']);

        $this->actingAs($project->owner)
             ->post($project->path().'/tasks', $attributes)
            ->assertSessionHasErrors('body');

    }

    function test_a_task_can_be_updated(){

        // $this->withoutExceptionHandling();

        // $this->signIn();

        // $project = auth()->user()->projects()->create(
          //  factory(Project::class)->raw()
        // );

        // $task = $project->addTask(' test task');

//        $project = app(ProjectFactory::class)
//            ->ownedBy($this->signIn())
//            ->withTasks(1)
//            ->create();
//
//        $this->patch($project->task[0]->path(), [
//            'body' => 'changed',
//            'completed' => true
//        ]);
// Or
//        $project = app(ProjectFactory::class)
//            ->withTasks(1)
//            ->create();
        // Or
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed'
            ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed'
        ]);

    }

    function test_a_task_can_be_completed(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true
            ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed',
            'completed' => true
        ]);

    }

    function test_a_task_can_be_marked_as_incomplete(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => false
            ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed',
            'completed' => false
        ]);

    }

    function test_only_the_owner_of_the_project_can_update_the_task(){

        $this->signIn();

//       $project = factory('App\Project')->create();
//
//        $task = $project->addTask('Test_task');
//
//        $this->patch($task->path(),['body' => 'changed'])
//              ->assertStatus(403);

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(),['body' => 'Changed'])
            ->assertStatus(403);



        $this->assertDatabaseMissing('tasks',['body' => 'changed']);

    }
}
