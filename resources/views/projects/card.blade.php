
<div class="card" style="height: 200px">

    <h3 class="font-normal text-xl py-2 -ml-5 mb-3 border-l-4 border-blue-400 pl-4">

        <a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a></h3>

    <div class="grey">{{ str_limit($project->description,100) }}</div>

</div>


