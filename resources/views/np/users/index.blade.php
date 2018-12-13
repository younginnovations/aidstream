@extends('np.base.base')

@section('title', trans('lite/title.user_list'))

@section('content')
    {{Session::get('message')}}
    <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
        <div class="alert alert-success hidden" id="success"></div>
        <div class="alert alert-danger hidden" id="error"></div>
        @include('includes.response')
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">
                    @lang('lite/title.user_list')
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="add-user-link">
                        <a href="{{ route('np.users.create') }}">Add a user</a>
                    </div>
                @endif
            </div>
            <div class="panel__body panel-users-settings">
                @if(count($users) > 0)
                    <table class="panel__table table-header">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Permission</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <td>
                                    <span id="name">{{$user->first_name}}  {{$user->last_name}}</span>
                                    <p><em>{{$user->username}}</em></p>
                                </td>
                                <td> {{$user->email}} </td>
                                <td class="permission">
                                    @if($user->role_id == 1)
                                        {{ Form::select('permission',['1' => 'Administrator'],$user->role_id,['disabled']) }}
                                    @elseif(auth()->user()->role_id == 5 || auth()->user()->role_id == 1)
                                        {{ Form::select('permission',$roles,$user->role_id,['id' => 'permission']) }}
                                    @else
                                        {{ Form::select('permission',$roles,$user->role_id,['id' => 'permission', 'disabled']) }}
                                    @endif
                                    {{ Form::hidden('user_id',$user->id, ['id' => 'user_id']) }}
                                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                </td>
                                <td>
                                    @if($user->role_id != 1)
                                        <a href="{{route('lite.users.delete',['id' => $user->id])}}" class="delete">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center no-data no-activity-data">
                        <p>You haven’t added any user yet.</p>
                        <a href="{{route('lite.users.create') }}" class="btn btn-primary">Add a user</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="{{asset('lite/js/lite.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        Lite.updatePermission();
    </script>
@stop
