@extends('layouts.app')

@section('content')
    <h1>New Task</h1>
    
    @if($errors->any())
    <div class="alert alert-danger" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li> {{ $error }} </li>
        @endforeach
</ul>
</div>
@endif




    <form method=POST action="/tasks">
    <div class="form-group mb-3">
        @csrf
        <label for="description">Task Description</label>
        <input class="form-control" name="description" />
    </div>

    <!-- Use the below when you want to show the errors when there are many form inputs -->
    <!-- @error('description')
    <div class="alert alert-danger" role="alert">
    {{ $message }}
</div>
@enderror -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Create Task</button>
    </div>
    </form>
@endsection