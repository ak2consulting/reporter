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
		<h1>Manage your Data</h1>
	</div>

	@if( count($tableData['rows']) === 0)
		<p> You have no data!  Try <a href="{{URL::to('/data/upload')}}">Uploading</a> some</p>
	@stop
	@endif

	<a href="{{ URL::to('/data/upload')}}" class="btn btn-success">Upload Data</a>
	<a href="{{ URL::to('/data/delete/all')}}" class="btn btn-danger">Delete All Data</a>

	<!-- Start of the table; most of it is auto generated! -->
	<table class="table table-hover">
		<caption>{{ $tableData['caption'] }}</caption>
			<thead>
				<tr>

					@foreach($tableData['threads'] as $thread)
					<th>{{ $thread }}</th>
					@endforeach

				</tr>
			</thead>
		<tbody>
			
			@for ($i = 0; $i < count($tableData['rows']); $i++)
			<tr>
	
				<td>{{$i}}</td>
				<td>{{$tableData['rows'][$i]['product']}}</td>
				<td>{{$tableData['rows'][$i]['type']}}</td>
				<td>{{$tableData['rows'][$i]['range']}}</td>
				<td>
					<div class="btn-group">
						<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
							Action
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<!-- dropdown menu links -->
							<li><a href="{{URL::to('data/analyze/' . $tableData['rows'][$i]['fileID'] )}}">Analyze</a></li>
							<li><a href="{{URL::to('data/delete/' . $tableData['rows'][$i]['fileID'] )}}">Delete</a></li>
						</ul>
					</div>
				</td>
			</tr>
			@endfor

		</tbody>
	</table>
	
@stop