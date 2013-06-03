@extends('layouts.master')


@section('auxStyles')
	{{ HTML::style('css/dropzone.css') }}

@stop


@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@stop

@section('content')
	<!-- Main hero unit for a primary marketing message or call to action -->
	<div class="hero-unit">
		<h2>Upload!</h2>
		<form action="{{ url('data/upload')}}" class="dropzone" id="my-awesome-dropzone"></form>
	</div>
@stop

@section('auxScripts')
	{{ HTML::script('js/dropzone.min.js') }}

@stop
