@extends('user.layout')
@section('user_content')
    <div class="col-12">
        <div class="col-12">
            <div class="form-group">
                <label for="exampleFormControlInput1">Title</label>
                <input class="form-control" type="text" value="{{$post->title}}" aria-label="readonly input example"
                       readonly>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Description</label>
                <input class="form-control" type="text" value="{{$post->description}}"
                       aria-label="readonly input example" readonly>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Image</label>
                <img class="form-control" style="height: 150px; width: 300px" src="{{asset (''.$post->image) }}" alt="img">
            </div>

        </div>
    </div>
@endsection
