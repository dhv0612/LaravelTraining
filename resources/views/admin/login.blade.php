<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <p class="text-muted mb-4 mt-3">Admin</p>
                            @if (session('error'))
                                <div class="alert alert-dannger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{session('error')}}!
                                </div>
                            @endif
                        </div>

                        <form action="{{URL::to(route('admin_login'))}}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email</label>
                                <input class="form-control" type="email"  name="email" id="account"  required="" placeholder="Your email">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" id="password"  name="password" class="form-control"  placeholder="Your password ">
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary col-5" type="submit"> Login </button>
                                <a class="btn btn-warning col-5" href="{{URL::to(route('screen_admin_register'))}}"> Register </a>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-center">

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
