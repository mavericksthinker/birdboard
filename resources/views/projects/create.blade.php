@extends('layouts.app')

@section('content')
   <form method="POST" action="/projects">
       @csrf
       <h1 class="heading is-1">Create a Project</h1>

          <div class="field">
             <label class="label" for="title">Title</label>

             <div class="control">
                 <input type="text" class="input" name="title" placeholder="Title">
             </div>
          </div>

         <div class="field">
             <label class="label" for="description">Description</label>

             <div class="control">
                 <textarea name="description" class="textarea">

                 </textarea>
             </div>
         </div>

         <div class="field">
             <div class="control">
                 <button type="submit" class="button is-link">Create Project</button>
                 <a href="/projects" style="padding-left: 10px;">Cancel</a>
             </div>
         </div>
     </form>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.css">
    @endsection
