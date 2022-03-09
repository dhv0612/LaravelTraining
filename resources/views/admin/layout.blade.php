<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<body>
<nav class="navbar navbar-expand-sm bg-primary navbar-dark justify-content-around">
    <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="{{URL::to(route('screen_admin_home'))}}">Home</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="{{URL::to(route('screen_list_categories'))}}">Categories</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="{{URL::to(route('screen_list_posts'))}}">Post</a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbardrop" data-toggle="dropdown">
                {{auth()->user()->name}}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{URL::to(route('logout'))}}">Logout</a></a>
            </div>
        </li>
    </ul>

</nav>

<div class="container bg-light">
    {{-- start content --}}
    @yield('admin_content')
    {{-- end content --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
