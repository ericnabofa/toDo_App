@extends('layouts.app')

@section('content')
    <h1>Task List</h1>

    @foreach($tasks as $task)
    <div class="card @if($task->isCompleted()) border-success mb-3 @endif" style="margin-bottom: 20px;">
  <div class="card-body">
    <p> 
        @if($task->isCompleted())
        <span class="badge text-bg-success">completed</span>
        @endif{{ $task->description }} 
    </p>
    @if(!$task->isCompleted())
    <form action="/tasks/{{ $task->id }}" method="POST">
      @method('PATCH')   
      @csrf  
     
    <button class="btn btn-light btn-lg d-grid gap-2" input="submit">Complete</button>
</form>
@else 
<form action="/tasks/{{ $task->id }}" method="POST">
      @method('DELETE')   
      @csrf  
     
    <button class="btn btn-danger btn-lg d-grid gap-2" input="submit">Delete</button>
</form>
@endif
  </div>
    </div>
    @endforeach
    <a href="/tasks/create" class="btn btn-primary btn-lg d-grid gap-2">New Task</a>
@endsection