<h2>Notification to admin</h2>
<p>Dear {{$admin->name}}, </p>
<p>List post unread today</p>
<ul>
    @foreach($posts as $key=>$post)
    <li><a href= "{{URL::to(route('screen_user_view_posts', ['id'=>$post->id]))}}">{{$post->title}}</a></li>
    @endforeach
</ul>
