@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
				<div class="panel panel-default">
					<div class="panel-content-heading">Settings</div>
					<div class="panel-body">
						<div class="create-form">
							{!! form_start($form) !!}
							{!! form_until($form, 'default_field_values') !!}
							<div class="settings-checkall-wrapper"> 
								{!! form_row($form->default_field_groups) !!}
								<div class="form-group">
								<label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary">Check All</span></label>
								</div>
							</div>
							{!! form_end($form) !!}
							{{--{!! form($form) !!}--}}
						</div>
						<div class="collection-container hidden" data-prototype="{{ form_row($form->reporting_organization_info->prototype()) }}"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
