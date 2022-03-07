@extends('layout')
@section('admin_content')

    <div class="row mt-5">

        <div class="col-3">
            <a class="btn btn-primary" href="{{URL::to(route('screen_add_categories'))}}">
                Add category
            </a>
        </div>
        <form class="row col-9" action="{{URL::to(route('screen_list_posts'))}}" method="GET" >
            <div class="col-5">
                <select class="form-control" name="category" style=" width: auto;">
                    <option value="" hidden>Category</option>
                    <option value=""></option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-5">
                <input class="form-control" name="title" placeholder="Enter title" style=" width: auto;">
            </div>
             <div class="col-2">
               <button class="btn btn-primary " type="submit"> Filter </button>
            </div>
        </form>


        <div class="col-12 row" shadow p-3 mb-5 bg-white rounded>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Image</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{$post->title}}</td>
                            <td>{{$post->description}} </td>
                            <td> <img style="height: 50px; width: 100px" src="{{URL::to(''.$post->image) }}" alt="img"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="container">
                {{ $posts->links() }}
            </div>

        </div>

@endsection
