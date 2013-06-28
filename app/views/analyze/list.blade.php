@extends('layouts.master')


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

	@if( count($pageData['tableData']['rows']) === 0)
		<p> You have no data!  Try <a href="{{URL::to('/data/upload')}}">Uploading</a> some</p>
	@stop
	@endif

	<!-- Start of the table; most of it is auto generated! -->
	<table class="table table-hover">
		<caption>{{ $pageData['tableData']['caption'] }}</caption>
			<thead>
				<tr>

					@foreach($pageData['tableData']['threads'] as $thread)
					<th>{{ $thread }}</th>
					@endforeach

				</tr>
			</thead>
		<tbody>
			
			@for ($i = 0; $i < count($pageData['tableData']['rows']); $i++)
			<tr>
	
				<td>{{$i}}</td>
				<td>{{$pageData['tableData']['rows'][$i]['name']}}</td>
				<td>{{$pageData['tableData']['rows'][$i]['desc']}}</td>
				<td>{{$pageData['tableData']['rows'][$i]['cost']}}</td>
				<td>
			
					<a class="btn btn-primary" href="{{URL::to('data/analyze/' . $pageData['fileID'] . '/' . $pageData['tableData']['rows'][$i]['id'] )}}">Analyze</a>
			
				</td>
			</tr>
			@endfor

		</tbody>
	</table>
	
@stop