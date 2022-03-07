@extends('layout')
@section('admin_content')

    <div class="row mt-5">

        <div class="col-6">
            <a class="btn btn-primary" href="{{URL::to(route('screen_add_categories'))}}">
                Add category
            </a>
        </div>

        <div class="col-12 row" shadow p-3 mb-5 bg-white rounded>
            <div class="col-6">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Category name</th>
                        <th scope="col">Parent</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{$category->name}}</td>
                            <td>
                                @foreach($category->ancestors as $item)
                                    {{$item->name}},
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table" style="text-align: center">
                    <thead class="thead-dark">
                    <tr>
                        <th>Tree view</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tree as $key => $item)
                        <tr>
                            <td>
                                {{$item}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

@endsection
