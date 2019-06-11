<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'project_id' => factory(App\Project::class)
        // Same as this
        //'project_id' => function() {
            //return factory(\App\Project::class)->create()->id;
        //  }
    ];
});
