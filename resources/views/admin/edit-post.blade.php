@extends('admin.layout')
@section('admin_content')
    <div class="col-12">
        <div class="col-12">
            <form class="shadow p-3 mb-5 bg-transparent rounded"
                  action="{{URL::to(route('edit_posts', ['id'=> $post->id]))}} "
                  enctype="multipart/form-data"
                  method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Title</label>
                    <input type="text" value="{{$post->description}}" name="title" required class="form-control"
                           placeholder="Edit title">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Description</label>
                    <input type="text" value="{{$post->description}}" name="description" required class="form-control"
                           placeholder="Edit description">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <img style="height: 100px; width: 200px" src="{{asset (''.$post->image) }}" alt="img">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Category</label>
                    <div class="container row">
                        @foreach($categories as $category)
                            <div class="input_group col-4">
                                <input type="checkbox" name="category[]" value="{{$category->id}}"
                                       @if(in_array($category->id, $detail_post)) checked @endif
                                >
                                <label>{{$category->name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="col-12"></div>
    </div>
    </div>
@endsection
