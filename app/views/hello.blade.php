@extends('layouts.master')



@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@stop

@section('content')
<div class="hero-unit">
	<h1>Reportr</h1>
    <p>An AppWorld analytics engine</p>
    <p><a href="{{ (Auth::check()) ? URL::to('/data') : URL::to('/login')}}" class="btn btn-primary btn-large">Get Started &raquo;</a></p>
</div>
<div class="row-fluid">
	<div class="span4">
		<h2>Who</h2>
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		<p><a class="btn" href="#">View details &raquo;</a></p>
	</div><!--/span-->

	<div class="span4">
		<h2>What</h2>
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		<p><a class="btn" href="#">View details &raquo;</a></p>
	</div><!--/span-->

	<div class="span4">
		<h2>Why</h2>
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		<p><a class="btn" href="#">View details &raquo;</a></p>
	</div><!--/span-->

           
</div><!--/row-->
          
@stop
