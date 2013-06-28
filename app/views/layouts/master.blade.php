<!DOCTYPE html>
<html>
    <head>
        <title>
            @section('title')
            Laravel 4 - Tutorial
            @show
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSS are placed here -->
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/bootstrap-responsive.css') }}

	   @yield('auxStyles')


        <style>
        @section('styles')
            body {
                padding-top: 60px;
            }
        @show
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <a class="brand" href="#">Laravel</a>

                    <!-- Everything you want hidden at 940px or less, place within here -->
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="{{{ URL::to('') }}}">Home</a></li>
                        </ul> 
                    </div>

                    <div class="nav pull-right">
                        <ul class="nav">
                            @if ( Auth::guest() )
                                <li>{{ HTML::link('login', 'Login') }}</li>
                            @else
                                <li>{{ HTML::link('logout', 'Logout') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div> 

    	<div class="container-fluid">
                <!-- Success-Messages -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Success</h4>
                        {{{ $message }}}
                    </div>
                @endif


            <div class="row-fluid">
        	    <div class="span3">
                <!--Sidebar content-->

                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">

                            @section('sidebarList')
                            @show
                            <li class="nav-header">Sidebar</li>
                            <li class="active"><a href="#">LInk</a></li>
                            <li><a href="#">LInk</a></li>
                            <li><a href="#">LInk</a></li>

                        </ul>
                    </div>
                    
        	    </div>
        	    <div class="span9">
        	    	<!--Body content-->
                    @yield('content')
        	    </div>
    	    </div>
    	</div>

        <!-- Global scripts are placed here -->
        {{ HTML::script('js/jquery.min.js') }}
        {{ HTML::script('js/bootstrap.min.js') }}

	   @yield('auxScripts')



        <hr>

        @section('footer')
        <footer>
            <p>this is my footer</p>
        </footer>
        @show

    </body>
</html>
