@extends('admin.layout')
@section('admin_content')

    <div class="row mt-5">
        <div class="col-6">
            <a class="btn btn-primary" href="{{URL::to(route('screen_add_posts'))}}">
                Add post
            </a>
        </div>
        <form class="row col-6 container" action="{{URL::to(route('screen_list_posts'))}}" method="GET">

            <div class="col-4 text-center">
                <div class="d-flex">
                    <div class="mx-auto">
                        <select class="form-control" name="category" style=" width: auto;">
                            <option value="" hidden>Category</option>
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex">
                    <div class="mx-auto">
                        <input class="form-control" name="title" placeholder="Enter title" style=" width: auto;">
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex">
                    <div class="mx-auto">
                        <button class="btn btn-primary " type="submit"> Filter</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="col-12 row" style="padding-top: 30px;" shadow p-3 mb-5 bg-white rounded>
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Category</th>
                <th scope="col">Quantity voucher</th>
                <th scope="col" class="text-center">Get voucher</th>
                <th scope="col" class="text-center">Image</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{$post->title}}</td>
                    <td>{{$post->description}} </td>
                    <td>
                        @foreach($post->category as $key=> $cate)
                            {{$cate->name}},
                        @endforeach
                    </td>
                    <td>{{$post->voucher_quantity}}</td>
                    <td>
                        <div class="form-check form-switch form-group text-center">
                            <input class="form-check-input" name="voucher_enabled"
                                   type="checkbox" id="flexSwitchCheckChecked" disabled
                                   @if ($post->voucher_enabled) checked @endif
                            >
                        </div>

                    </td>
                    <td class="text-center">
                        @if($post->image)
                            <img style="height: 50px; width: 100px" src="{{asset (''.$post->image) }}"
                                 alt="img">
                        @endif
                    </td>

                    <td class="text-center"><a class="btn btn-warning col-3"
                                               href="{{URL::to(route('screen_edit_posts', ['id'=>$post->id]))}}">
                            Edit </a>
                        <a class="btn btn-warning col-4"
                           href="{{URL::to(route('delete_posts', ['id'=>$post->id]))}}"
                           onclick="return confirm('Are you sure you want to delete this item')">
                            Delete
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="container ">
            <div class="d-flex">
                <div class="mx-auto">
                    {{$posts->links("pagination::bootstrap-4")}}
                </div>
            </div>
        </div>
    </div>

@endsection
