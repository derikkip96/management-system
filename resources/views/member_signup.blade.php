
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Adrian || Apply Leave</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/form.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/bundles/multiselect/css/multi-select.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css') }}" rel="stylesheet" />


    <!-- Custom Css -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <!-- Theme style. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('assets/css/styles/all-themes.css') }}" rel="stylesheet" />
</head>
<body class="light">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30">
            <img class="loading-img-spin" src="{{ asset('assets/images/loading.png') }}" width="20" height="20" alt="admin">
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
@extends('layouts.navbar')
<!-- #Top Bar -->
<div>
    <!-- Left Sidebar -->
@extends('layouts.sidebar')
<!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <!-- #END# Right Sidebar -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="breadcrumb breadcrumb-style ">
                        <li class="breadcrumb-item">
                            <h4 class="page-title">Dashboard</h4>
                        </li>
                        <li class="breadcrumb-item bcrumb-1">
                            <a href="index-2.html">
                                <i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Apply Leave</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Widgets -->
        <!-- #END# Widgets -->
        <!-- Task Info -->
        <!-- #END# Task Info -->
        <!-- Vertical Layout | With Floating Label -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <strong>Add Member</strong></h2>

                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('add-employee')}}">
                            {{ csrf_field() }}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="name" id="name" class="form-control">
                                    <label class="form-label">Name</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="email" id="email" class="form-control">
                                    <label class="form-label">Email</label>
                                </div>
                            </div>
                            {{--<div class="form-group form-float">--}}
                                {{--<div class="form-line">--}}
                                    {{--<input type="password" name="password" id="password" class="form-control">--}}
                                    {{--<label class="form-label">Password</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group form-float">
                                <label class="form-label">Select Department</label>
                                <div class="form-line">
                                    <select class="form-group col-10" name="department" id="department">
                                        @foreach ($department as $dpt)
                                            <option value="{{ $dpt->id }}">{{ $dpt->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="nat_id" id="nat_id" class="form-control">
                                    <label class="form-label">National Id</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="NSSF" id="NSSF" class="form-control">
                                    <label class="form-label">NSSF</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="NHIF" id="MHIF" class="form-control">
                                    <label class="form-label">NHIF</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="KRA_Pin" id="KRA_Pin" class="form-control">
                                    <label class="form-label">KRA Pin</label>
                                </div>
                            </div>
                            <div class="form-group">
                                {{-- <p><b>With Search Bar</b>  </p> --}}
                                <label class="form-label">Select MemberType</label>
                                <select class="form-group col-10" id="reliever" name="type">
                                    @foreach ($type as $tpy)
                                        <option value="{{ $tpy->id }}">{{ $tpy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Vertical Layout | With Floating Label -->

    <!-- Browser Usage -->
    <!-- #END# Browser Usage -->
    </div>


    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="/home">
                <div class="box-part text-center">
                    <div class="title p-t-15">
                        <h3><h3 class="col-dark-gray">Dashboard</h3>
                    </div>
                    <i class="fab fa-accusoft fa-3x col-dark-gray"></i>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="/leave-approval">
                <div class="box-part text-center">
                    <div class="title p-t-15">
                        <h3 class="col-dark-gray">Leave Approval</h3>
                    </div>
                    <i class="fas fa-user-edit fa-3x col-dark-gray"></i>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="/logout">
                <div class="box-part text-center">
                    <div class="title p-t-15">
                        <h3 class="col-dark-gray">Logout</h3>
                    </div>
                    <i class="fas fa-sign-out-alt fa-3x col-dark-gray"></i>
                </div>
            </a>
        </div>
    </div>
    </div>
</section>
<!-- Plugins Js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/form.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/basic-form-elements.js') }}"></script>
<script src="{{ asset('assets/js/bundles/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{ asset('assets/js/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js') }}"></script>
<!-- Custom Js -->
<script src="{{ asset('assets/js/admin.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script src="{{ asset('assets/js/pages/sparkline/sparkline-data.js') }}"></script>
<script src="{{ asset('assets/js/pages/medias/carousel.js') }}"></script>
</body>
</html>
