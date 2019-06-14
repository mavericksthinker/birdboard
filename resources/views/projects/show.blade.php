@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">

        <div class="flex justify-between items-end w-full">

            <p class="grey text-sm font-normal">
                <a href="/projects" class="grey text-sm font-normal no-underline">My Projects</a> / {{$project->title}}
            </p>

            <a href="{{$project->path().'/edit'}}" class="button no-underline" >Edit Project</a>

        </div>

    </header>

    <main>
        <div class="lg:flex -mx-3">

            <div class="lg:w-3/4 px-3 mb-6">

                <div class="mb-8">

                    <h2 class="text-lg grey font-normal mb-3">Tasks</h2>

                    {{-- Tasks --}}
                    @foreach($project->tasks as $task)

                        <div class="card mb-3">

                            <form method="POST" action="{{ $task->path() }}">

                                {{-- Patch request --}}
                                @method('PATCH')

                                @csrf

                                <label class="flex" onchange="this.form.submit()">

                                    <input name="body" value="{{ $task->body }}" class="w-full {{$task->completed ? 'grey':''}}">

                                    <input name="completed" type="checkbox" {{$task->completed? 'checked':''}}>

                                </label>

                            </form>

                        </div>

                    @endforeach

                    <div class="card mb-3">

                        <form action="{{ $project->path().'/tasks' }}" method="POST">

                            @csrf

                            <input type="text" placeholder="Add a New Task" class="w-full" name="body">

                        </form>

                    </div>

                </div>

                <div>

                    <h2 class="text-lg grey font-normal mb-3">General Notes</h2>

                 {{-- General Notes --}}

                    <form method="POST" action = "{{ $project->path() }}">
                        @csrf
                        @method('PATCH')
                        <label>

                            <textarea
                                name="notes"
                                class="card w-full mb-4"
                                style="min-height: 200px"
                                placeholder="Anything special that you want to make a note of ?"
                            >{{ $project->notes }}</textarea>

                            <button type="submit" class="button ">Save</button>

                        </label>

                    </form>

                    @if($errors->any())

                        <div class="field mt-6">

                            @foreach ($errors->all() as $error)

                                <li class="text-sm text-red-700">{{ $error }}</li>

                            @endforeach

                        </div>

                     @endif

                </div>

            </div>

            <div class="lg:w-1/4 px-3">

                 @include('projects.card')

            </div>

        </div>

    </main>

@endsection
