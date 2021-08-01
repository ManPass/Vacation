<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('inc.header')

    <div class="main-body">
        <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-lg-5">
                @yield('content')
            </div>
            <div class="col-sm-3">
                @include('inc.aside')
            </div>
        </div>
    </div>
</body>
</html>
