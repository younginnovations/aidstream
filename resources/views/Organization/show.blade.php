@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Organization Data</div>
                    <div class="panel-body">

					<ol class="breadcrumb">
						<?php
							$status_label = ['Draft', 'Completed', 'Verified', 'Published'];
							$btn_status_label = ['Complete', 'Verify', 'Publish'];
							$btn_text = $status > 2 ? "" : $btn_status_label[$status];
						?>
						@foreach($status_label as $key => $val)
							@if($key == $status)
								<li class="active">{{ $val }}</li>
							@else
								<li><a href="#">{{ $val }}</a></li>
							@endif
						@endforeach
					</ol>

                    @if($btn_text != "")
                        <form method="POST" id="change_status">
                            <input type="hidden" name="_method" value="PUT"/>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="status" value="{{ $status + 1 }}">
							@if($status == 2)
                            <input type="button" value="{{ $btn_text }}" class="btn_confirm"
                                   data-title="Confirmation" data-message="Are you sure you want to Publish?">
							@else
                            <input type="submit" value="{{ $btn_text }}">
							@endif
                        </form>
                    @endif

					@if(!empty($reporting_org))
					<div class="panel panel-default">
						<div class="panel-heading">Reporting Organization</div>
						<div class="panel-body row">
							<div class="col-xs-4">Ref:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
							<div class="col-xs-4">Type:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_type'] }}</div>
							@foreach($reporting_org['narrative'] as $narrative)
								<div class="col-xs-4">Narrative Text:</div>
								<div class="col-xs-8">{{ $narrative['narrative'] . ' [' . $narrative['language'] . ']' }}</div>
							@endforeach
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Organization Identifier</div>
						<div class="panel-body row">
							<div class="col-xs-4">Text:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
						</div>
					</div>
					@endif

					@if(!empty($org_name))
					<div class="panel panel-default">
						<div class="panel-heading">Name</div>
						<div class="panel-body row">
							@foreach($org_name as $name)
								<div class="col-xs-4">Text:</div>
								<div class="col-xs-8">{{ $name['narrative'] . ' [' . $name['language'] . ']' }}</div>
							@endforeach
						</div>
					</div>
					@endif

				</div>
			</div>
		</div>


            <div class="col-xs-4">
                @include('includes.menu_org')
            </div>

        </div>
    </div>
@endsection
