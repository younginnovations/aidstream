@extends('app')

@section('content')

{{Session::get('message')}}

<div class="container">
	<div class="row">
		<div class="col-xs-8">
			<div class="panel panel-default">
				<div class="panel-heading">List Published Files</div>

				<div class="panel-body">
					<table class="table table-striped">
						<thead>
						<tr>
							<th></th>
							<th>Filename</th>
							<th>Published Date</th>
							<th>Registered in IATI Registry</th>
							<th>Preview As</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							@foreach($list as $file)
								<tr>
									<td><input type="checkbox"/></td>
									<td><a href="{{ url('/uploads/files/organization/' . $file->filename) }}" target="_blank">{{ $file->filename }}</a></td>
									<td>{{ $file->updated_at }}</td>
									<td>{{ $file->published_to_register ? 'Yes' : 'No' }}</td>
									<td><a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/uploads/files/organization/' . $file->filename) }}" target="_blank">CSV</a></td>
									<td><a href="{{ route('list-published-files', ['delete', $file->id]) }}">Delete</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-xs-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav">
						<li><a href="#">List Activities</a></li>
						<li><a href="#">Add New Activity</a></li>
						<li><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization Data</a></li>
						<li><a href="{{ route('list-published-files') }}">List Published Files</a></li>
						<li><a href="#">Download My Data</a></li>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav">
						<li><a href="#">List Users</a></li>
						<li><a href="#">Uploaded Docs</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
