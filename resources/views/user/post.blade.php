@extends('user.layout')
@section('user_content')

    <div class="row mt-5 col-12">
        <div class="row">
            @foreach($posts as $post)
                <div class="col-sm-3" style="padding-top: 20px;">
                    <div class="card">
                        <div class="card-body">
                            <img style="width: 100%; height: 100px" src="{{asset (''.$post->image) }}" class="card-img-top">
                            <h5 class="card-title">{{$post->title}}</h5>
                            <p class="card-text">{{$post->description}}</p>
                            <a href="{{URL::to(route('screen_user_view_posts', ['id'=>$post->id]))}}" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
