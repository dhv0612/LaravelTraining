@extends('admin.layout')
@section('admin_content')

    <div class="col-12">
        <div class="col-12">
            <form class="shadow p-3 mb-5 bg-transparent rounded" action="{{URL::to(route('add_posts'))}} "
                  enctype="multipart/form-data"
                  method="POST">
                @csrf

                <div class="form-group">
                    <label for="exampleFormControlInput1">Title</label>
                    <input type="text" name="title" required class="form-control" placeholder="New title">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlInput1">Description</label>
                    <input type="text" name="description" required class="form-control" placeholder="New description">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlInput1">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Category</label>
                    <div class="container row form-check form-switch" style="display: flex">
                        @foreach($categories as $category)
                            <div class="input_group col-4">
                                <input class="form-check-input" type="checkbox" name="category[]"
                                       value="{{$category->id}}" id="flexSwitchCheckChecked">
                                <label>{{$category->name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                   <label>Get voucher </label>
                    <div class="form-check form-switch form-group">
                        <input class="form-check-input" name="voucher_enabled" type="checkbox" id="flexSwitchCheckChecked">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlInput1">Quantity voucher</label>
                    <input type="number" name="voucher_quantity" class="form-control" placeholder="Quantity voucher">

                </div>

                <div class="text-center">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>

            </form>
        </div>
    </div>

@endsection
