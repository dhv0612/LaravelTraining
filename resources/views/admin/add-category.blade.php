@extends('layout')
@section('admin_content')
<div class="col-12">
            <div class="col-12">
                <form class="shadow p-3 mb-5 bg-transparent rounded" action="{{url('admin/add-categories')}}"
                      method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="New Category">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Parent Category</label>
                        <select class="form-control" name="parent">
                            <option value="">No parent</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
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
