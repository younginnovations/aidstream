@extends('app')

@section('title', trans('title.user_permissions').'- ' . $user->name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>@lang('title.user_permissions')</div>
                </div>
                <div class="panel panel-default panel-element-detail panel-user-permission-edit">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ route('admin.update-user-permission', $user->id)}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="panel panel-default panel-user">
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="add_activity" name="user_permission[add]"
                                                      class="field1" @if(isset($user['user_permission']['add']))
                                                      checked="checked" @endif >@lang('global.add')</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="edit_activity" name="user_permission[edit]"
                                                      class="field1" @if(isset($user['user_permission']['edit']))
                                                      checked="checked" @endif >@lang('global.edit')</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="delete_activity"
                                                      name="user_permission[delete]"
                                                      class="field1" @if(isset($user['user_permission']['delete']))
                                                      checked="checked" @endif >@lang('global.delete')</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="publish_activity"
                                                      name="user_permission[publish]"
                                                      class="field1" @if(isset($user['user_permission']['publish']))
                                                      checked="checked" @endif >@lang('global.publish')</label>
                                    </div>
                                </div>
                            </div>
                            <label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary">@lang('global.check_all')</span></label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-submit btn-form">@lang('global.submit')</button>

                    </form>
                </div>
            </div>

            @include('includes.side_bar_menu')
        </div>
    </div>
@endsection
