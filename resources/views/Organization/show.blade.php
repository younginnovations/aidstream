@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container activity-container">
        <div class="row">
        	@include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
            	<?php
					$status_label = ['draft', 'completed', 'verified', 'published'];
					$btn_status_label = ['Complete', 'Verify', 'Publish'];
					$btn_text = $status > 2 ? "" : $btn_status_label[$status];
				?>
                <div class="element-panel-heading">
                	<span class="pull-left">GPAF-IMP-062</span>
                    @if($btn_text != "")
                        <form method="POST" id="change_status" class="pull-right" action="{{ url('/organization/' . Auth::user()->org_id . '/update-status') }}">
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
                </div>
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">				
        	        <div class="activity-status activity-status-{{ $status_label[$status] }}">
						<ol>
							@foreach($status_label as $key => $val)
								@if($key == $status)
									<li class="active"><span>{{ $val }}</span></li>
								@else
									<li><span>{{ $val }}</span></li>
								@endif
							@endforeach
						</ol>
					</div>
	                <div class="panel panel-default panel-element-detail">
	                    <div class="panel-body">
						@if(!empty($reporting_org))
						<div class="panel panel-default">
							<div class="panel-heading">Reporting Organization
							<a href="#" class="edit-element">edit</a>
							</div>
							<div class="panel-body panel-element-body row">
								<div class="col-md-12">
									<div class="col-xs-4">Ref:</div>
									<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
								</div>
								<div class="col-md-12">
									<div class="col-xs-4">Type:</div>
									<div class="col-xs-8">{{ $reporting_org['reporting_organization_type'] }}</div>
								</div>
								@foreach($reporting_org['narrative'] as $narrative)
								<div class="col-md-12">
									<div class="col-xs-4">Narrative Text:</div>
									<div class="col-xs-8">{{ $narrative['narrative'] . ' [' . $narrative['language'] . ']' }}</div>
								</div>
								@endforeach
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">Organization Identifier
							<a href="#" class="edit-element">edit</a>
							</div>
							<div class="panel-body panel-element-body row">
								<div class="col-md-12">
									<div class="col-xs-4">Text:</div>
									<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
								</div>
							</div>
						</div>
						@endif

						@if(!empty($org_name))
						<div class="panel panel-default">
							<div class="panel-heading">Name
							<a href="#" class="edit-element">edit</a>
							</div>
							<div class="panel-body panel-element-body row">
								@foreach($org_name as $name)
								<div class="col-md-12">
									<div class="col-xs-4">Text:</div>
									<div class="col-xs-8">{{ $name['narrative'] . ' [' . $name['language'] . ']' }}</div>
								</div>
								@endforeach
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
            <div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
                @include('includes.menu_org')
            </div>

        </div>
    </div>
@endsection
