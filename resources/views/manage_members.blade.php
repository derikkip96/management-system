
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Adrian ||</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <!-- Custom Css -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!-- You can choose a theme from css/styles instead of get all themes -->
    <link href="{{ asset('assets/css/styles/all-themes.css') }}" rel="stylesheet" />
</head>
<body class="light">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30">
            <img class="loading-img-spin" src="http://localhost:8080/assets/images/loading.png" width="20" height="20" alt="admin">
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->js-basic-example dataTable

<!-- #Top Bar -->
<div>
    <!-- Left Sidebar -->
@extends('layouts.navbar')
    <!-- #END# Left Sidebar -->
@extends('layouts.sidebar')
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
                                <i class="fas fa-home"></i> My Visitors</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <strong>Manage Members</strong></h2>

                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>department</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($employee as $value)
                                    <tr>
                                        <td class="table-img">
                                            {{$loop->iteration}}
                                        </td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->department['name']}}</td>
                                        <td>
                                            @if ($value->status =='active')
                                                <span class="label bg-success shadow-style" data-toggle="modal" data-target="#exampleModalCenter" >{{ $value->status }}</span>
                                            @elseif($value->status =='pending')
                                                <span class="label bg-warning shadow-style " data-toggle="modal" data-target="#exampleCenter">{{ $value->status }}</span>
                                            @elseif($value->status == 'terminated')
                                                <span class="label bg-danger shadow-style" data-toggle="modal" data-target="#exampleModal">{{ $value->status }}</span>

                                            @endif

                                        </td>
                                        <td>
                                            <a href="member-edit/{{ $value->id }}">
                                                <button class="btn tblActnBtn">
                                                    <i class="material-icons">mode_edit</i>
                                                </button>
                                            </a>
                                        <!-- <a href="delete-member/{{ $value->id }}"> -->
                                            <form action="{{action('HomeController@delete_member', $value->id)}}" method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="submit" class="btn tblActnBtn">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                                <!-- <button class="btn btn-danger">Delete</button> -->
                                            </form>
                                            <!-- </a> -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="6"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Leave Status</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group ">

                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover bg-danger" name="status">teminate</button></span>
                                                    </a>
                                                    <form id="delete-form" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="terminated">
                                                        {{ csrf_field() }}
                                                    </form>

                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('dm').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover color-2" >pending</button></span>
                                                    </a>
                                                    <form id="dm" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="pending">
                                                        {{ csrf_field() }}
                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{--pending--}}
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="modal fade" id="exampleCenter" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Leave Status</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group ">
                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('dform').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover bg-danger" name="status">teminate</button></span>
                                                    </a>
                                                    <form id="dform" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="terminated">
                                                        {{ csrf_field() }}
                                                    </form>
                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('orm').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover color-5" >activate</button></span>
                                                    </a>
                                                    <form id="orm" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="active">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{--end--}}
                            {{--terminated--}}
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Leave Status</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group ">
                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('pen').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover color-2">pending</button></span>
                                                    </a>
                                                    <form id="pen" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="pending">
                                                        {{ csrf_field() }}
                                                    </form>
                                                    <a href="{{route('update-status',$value->id)}}"
                                                       onclick="event.preventDefault(); document.getElementById('act').submit();">
                                                        <i class="os-icon os-icon-signs-11"></i>
                                                        <span><button type="button" class="btn-hover color-5" name="status">activate</button></span>
                                                    </a>
                                                    <form id="act" action="{{route('update-status',$value->id)}}" method="POST"
                                                          style="display: none;">
                                                        <input type="hidden" name="_method" value="POST">
                                                        <input type="hidden" name="status" value="active">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{--end--}}
        <!-- #END# Basic Examples -->
        <!-- #START# Table With State Save -->
        <!-- #END# Table With State Save -->
        <!-- #START# Add Rows -->
        <!-- #END# Add Rows -->
    </div>
</section>
<!-- Plugins Js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<!-- Custom Js -->
<script src="{{ asset('assets/js/admin.js') }}"></script>
<script src="{{ asset('assets/js/pages/index.js') }}"></script>
<script src="{{ asset('assets/js/pages/charts/jquery-knob.js') }}"></script>
<script src="{{ asset('assets/js/pages/sparkline/sparkline-data.js') }}"></script>
<script src="{{ asset('assets/js/pages/medias/carousel.js') }}"></script>
<!-- Demo Js -->
</body>

</html>

<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="sidebar-user-panel active">
                <div class="user-panel">
                    <div class=" image">
                        <img src="http://localhost:8080/assets/images/usr.png" class="img-circle user-img-circle" alt="User Image" />
                    </div>
                </div>
                <div class="profile-usertitle">
                    <div class="sidebar-userpic-name">Munyingi Ian </div>
                    <div class="profile-usertitle-job ">MD</div>
                </div>
            </li>
            <li>
                <a href="/home">
                    <i class="fab fa-accusoft"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="/add-member">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Member</span>
                </a>
            </li>
            <li>
                <a href="/manage-members">
                    <i class="fas fa-user-edit"></i>
                    <span>Manage Members</span>
                </a>
            </li>

            <li>
                <a href="/apply-leave">
                    <i class="fas fa-object-group"></i>
                    <span>Apply Leave</span>
                </a>
            </li>
            <li>
                <a href="/leave-history">
                    <i class="fas fa-user-edit"></i>
                    <span>Leave History</span>
                </a>
            </li>

            <li>
                <a href="/leave-approval">
                    <i class="fas fa-swatchbook"></i>
                    <span>Leave Approval</span>
                </a>
            </li>
            <li>
                <a href="/visitors">
                    <i class="fas fa-diagnoses "></i>
                    <span>My Visitors</span>
                </a>
            </li>
            <li>
                <a href="/profile">
                    <i class="fas fa-id-card"></i>
                    <span>My Profile</span>
                </a>
            </li>  <li>
                <a href="/leave/home">
                    <i class="fas fa-users-cog"></i>
                    <span>User Accounts</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="fas fa-mail-bulk"></i>
                    <span>Email</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
</aside>
<!-- #END# Left Sidebar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
               aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="/home">
                <img src="http://localhost:8080/assets/images/logo.png" alt="" />
                <span class="logo-name">Adrian</span>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="pull-left">
                <li>
                    <a href="javascript:void(0);" class="sidemenu-collapse">
                        <i class="material-icons">reorder</i>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- Full Screen Button -->
                <li class="fullscreen">
                    <a href="javascript:;" class="fullscreen-btn">
                        <i class="fas fa-expand"></i>
                    </a>
                </li>
                <!-- #END# Full Screen Button -->
                <!-- #START# Notifications-->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="far fa-bell"></i>
                        <span class="label-count bg-orange"></span>
                    </a>
                    <ul class="dropdown-menu pullDown">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="table-img msg-user">
                                            <img src="http://localhost:8080/assets/images/usr.png" alt="">
                                        </span>
                                        <span class="menu-info">
                                            <span class="menu-title">Adrian Group</span>
                                            <span class="menu-desc">
                                                <i class="material-icons">access_time</i> 14 mins ago
                                            </span>
                                            <span class="menu-desc">Welcome To Adrian</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View All Notifications</a>
                        </li>
                    </ul>
                </li>
                <!-- #END# Notifications-->
                <li class="dropdown user_profile">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <img src="http://localhost:8080/assets/images/usr.png" width="32" height="32" alt="User">
                    </a>
                    <ul class="dropdown-menu pullDown">
                        <li class="body">
                            <ul class="user_dw_menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">person</i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">help</i>Help
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="material-icons">power_settings_new</i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- #END# Tasks -->
            </ul>
        </div>
    </div>
</nav>


