<?php

namespace Tests\Feature;

use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creating_a_project()
    {
      $project = ProjectFactory::create();

      $this->assertCount(1,$project->activity);


      tap($project->activity->last(),function($activity){

          $this->assertEquals('created',$activity->description);

          $this->assertNull($activity->changes);

      });
    }

    public function test_updating_a_project(){

        $project = ProjectFactory::create();

        $originalTitle = $project->title;

        $project->update(['title' => 'Changed']);

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity) use ($originalTitle){

            $this->assertEquals('updated',$activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Changed']

            ];

            $this->assertEquals($expected,$activity->changes);
        });
    }

    public function test_creating_a_new_task(){


        $project = ProjectFactory::create();

        $project->addtask('Some task');

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity){

            $this->assertEquals('created_task',$activity->description);

            $this->assertInstanceOf(Task::class,$activity->subject);

            $this->assertEquals('Some task',$activity->subject->body);

        });


    }

    public function test_completing_a_task(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
            'body' => 'foobar',
            'completed' => true
        ]);

        $this->assertCount(3,$project->activity);


        tap($project->activity->last(),function($activity){

            $this->assertEquals('completed_task',$activity->description);

            $this->assertInstanceOf(Task::class,$activity->subject);

        });

    }

    public function test_incompleting_a_task(){

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'foobar',
                'completed' => false
            ]);

        $this->assertCount(3,$project->activity);

        $this->patch($project->tasks[0]->path(),[
                'body' => 'foobar',
                'completed' => false
            ]);

        $project->refresh();

        $this->assertCount(4,$project->activity);

        tap($project->activity->last(),function($activity){

            $this->assertEquals('incompleted_task',$activity->description);

            $this->assertInstanceOf(Task::class,$activity->subject);

        });
    }

    function test_deleting_a_task(){

        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3,$project->activity);

    }
}
