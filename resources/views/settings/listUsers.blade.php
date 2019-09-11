@extends('settings.settings')
@section('panel-body')
    @if(count($users) > 0)
        <div class="panel-body panel-users-settings">
            @if (auth()->user()->isAdmin())
                <div class="add-user-link">
                    <a href="{{ route('admin.register-user') }}">@lang('global.add_a_user')</a>
                </div>
            @endif
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>@lang('user.name')</th>
                    <th>@lang('user.email')</th>
                    <th>@lang('user.permission')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $value)
                    <?php
                    $user_id = $value->id;
                    ?>
                    <tr>
                        <td><span id="name">{{ $value->first_name}} {{$value->last_name}}</span>
                            <p><em>{{$value->username}}</em></p>
                        </td>
                        <td>
                            <a href="mailto:{{$value->email}}">{{$value->email}}</a>
                        </td>
                        <td class="permission">
                            @if($value->role_id == 1)
                                {{ Form::select('permission',['1' => 'Administrator'],$value->role_id,['disabled']) }}
                            @elseif(auth()->user()->role_id == 5 || auth()->user()->role_id == 1)
                                {{ Form::select('permission',$roles,$value->role_id,['id' => 'permission']) }}
                            @else
                                {{ Form::select('permission',$roles,$value->role_id,['id' => 'permission', 'disabled']) }}
                            @endif
                            {{ Form::hidden('user_id',$value->id, ['id' => 'user_id']) }}
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        </td>
                        <td>
                            @if (auth()->user()->isAdmin() || auth()->user()->role_id == 5)
                                @if($value->role_id != 1)
                                    <a href="{{ url(sprintf('organization-user/%s/delete', $value->id)) }}" class="delete">@lang('global.delete')</a>
									<a href="{{ url(sprintf("/user/edit-profile/$user_id")) }}" class="edit">@lang('global.edit')</a>
								@endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <div class="text-center no-data no-user-data">
                    <div>
                        @lang('setting.you_havent_added_any_user_yet')
                        <a href="{{ route('admin.register-user') }}" class="btn btn-primary">@lang('global.add_a_user')</a>
                    </div>
                </div>
            @endif
        </div>
@endsection
@section('foot')
    <script src="{{url('/js/chunk.js')}}"></script>
    <script>
        Chunk.updatePermission();
    </script>
@endsection
