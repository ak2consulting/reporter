@extends('layouts.master')


@section('auxScripts')
{{ HTML::script('js/bootstrap-datatable.min.js') }}
{{ HTML::script('js/view/analyze.js') }}



@stop

@section('sidebarList')

	@parent
	<li class="nav-header">Sidebar</li>
    <li class="active"><a href="#">LInk</a></li>
    <li><a href="#">LInk</a></li>
    <li class="divider"></li>
    <li><a href="#">LInk</a></li>

@stop

@section('content')
	<!-- Main hero unit for a primary marketing message or call to action -->
	<div class="page-header">
		<h1>Analyze your Data</h1>
	</div>


	<!-- Start of the table; it's all auto gnerated! -->
	<div id="table-container">

	</div>
	
@stop