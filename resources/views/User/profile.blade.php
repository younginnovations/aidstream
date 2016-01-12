@extends('app')
@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel-content-heading panel-title-heading">My Profile</div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div><span>Name : </span>{{Auth::user()->name}}</div>
                            <div><span>Email : </span>{{Auth::user()->email}}</div>
                            <div><span>Username : </span>{{Auth::user()->username}}</div>
                        </div>
                        <div><a href="{{route('user.edit-profile', Auth::user()->id)}}">Edit Profile</a> | <a href="{{route('user.change-username', Auth::user()->id)}}">Change Username</a> | <a href="{{route('user.reset-user-password', Auth::user()->id)}}">Change Password</a></div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-content-heading panel-title-heading">Organization Details</div>
                        <div class="panel-body">
                            <div><img src="{{$organization[0]->logo ? $organization[0]->logo_url : url('images/no-logo.png')}}"></div>
                            <div><span>Organization Name : </span>{{$organization[0]->name}}</div>
                            <div><span>Organization Address : </span>{{$organization[0]->address}}</div>
                            <div><span>Organization Country : </span>{{$getCode->getOrganizationCodeName('Country', $organization[0]->country)}}</div>
                            <div><span>Organization Telephone : </span>{{$organization[0]->telephone}}</div>
                            <div><span>Organization Twitter : </span>{{$organization[0]->twitter}}</div>
                            <div><span>Organization Url : </span>{{$organization[0]->organization_url}}</div>
                            <div><span>Disqus Comments : </span>{{($organization[0]->disqus_comments == 1) ? 'Enabled' : 'Disabled'}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

