<h2>Notification to user</h2>
<p>Dear {{$user->name}}, </p>
<p>You haven't login in 1 day</p>
@if ($user->last_active_datetime)
<p>Your last active is {{$user->last_active_datetime}}</p>
@endif
