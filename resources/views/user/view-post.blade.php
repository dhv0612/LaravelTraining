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
                <img class="form-control" style="height: 150px; width: 300px" src="{{asset (''.$post->image) }}"
                     alt="img">
            </div>

            @if($detail_read_user)
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Time read</label>
                    <input class="form-control" type="text" value="{{$detail_read_user->times}}"
                           aria-label="readonly input example" readonly>
                </div>
            @endif

            @if ($post->voucher_enabled && Auth::check())
                @if ($count_get_voucher!=='' && $post->voucher_quantity <= $count_get_voucher)
                    <p>There is no more available voucher.</p>
                @else
                    @if (!$detail_read_user->get_voucher)
                        <a href="{{URL::to(route('user_get_voucher', ['id'=>$post->id]))}}"
                           class="btn btn-primary">Get voucher</a>
                    @else
                        <a href="#" class="btn btn-secondary"
                           onclick="return alert('You got the voucher from this post')">Get
                            voucher</a>
                    @endif
                @endif
            @endif

        </div>
    </div>

@endsection
