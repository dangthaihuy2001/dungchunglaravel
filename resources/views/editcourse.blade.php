@extends('master')
@section('content')
   
    <form action="
         @if(!isset($id))
            {{route('create-course')}}
        @else
            {{route('edit-course', $id)}}
        @endif
    " method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="<?php if (!empty($id)) echo $id ?>" class="form-control">
        <div class="form-group">
            <label for="exampleInputName">Name</label>
            <input type="text" name="name" value="<?php if (!empty($name)) echo $name ?>" class="form-control" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label for="exampleInputDescription">Description</label>
            <input type="text"  value="<?php if (!empty($description)) echo $description ?>" name="description" class="form-control" placeholder="Description">
        </div>
        <div class="mb-3">
            <label for="formFileMultiple" class="form-label">Multiple files input example</label>
            <input class="form-control"  value="<?php if (!empty($image)) echo $image ?>" name="image" type="file" id="formFileMultiple" multiple>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Submit</button>
    </form>
@endsection


@section('title', 'Chinh sua khoa hoc')
