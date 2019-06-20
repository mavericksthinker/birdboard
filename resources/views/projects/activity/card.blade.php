<div class="card mt-3">

    <ul class="test-xs">

        @foreach($project->activity as $activity)

            <li class="{{$loop->last?'':'mb-1'}}">

                {{--Using Polymorphism to clean up the view--}}
                @include("projects.activity.{$activity->description}")

                <span class="grey ml-2"> {{$activity->created_at->diffForHumans(null,true)}}</span>

            </li>

        @endforeach

    </ul>

</div>
