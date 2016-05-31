@extends('app')

@section('title', 'Profile - ' . Auth::user()->first_name)

@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="element-panel-heading">
                    <div>{{Auth::user()->name}}</div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 element-content-wrapper profile-content-wrapper">
                    <div class="panel panel-default panel-profile">
                        <div class="panel-body">
                            <div class="profile-img">
                                <img src="{{url('images/avatar-full.png')}}" width="200" height="200"
                                     alt="{{Auth::user()->name}}">
                            </div>
                            <div class="profile-info">
                                <span class="profile-username">{{Auth::user()->username}}</span>
                                <span class="profile-user-email"><a
                                            href="mailto:{{Auth::user()->email}}">{{Auth::user()->email}}</a></span>
                                {{--<div class=""><a href="{{route('user.edit-profile', Auth::user()->id)}}" class="edit-profile">Edit Profile</a></div>--}}
                                <div><a href="{{route('user.edit-profile', Auth::user()->id)}}" class="edit-profile">Edit
                                        Profile</a> | <a href="{{route('user.change-username', Auth::user()->id)}}">Change
                                        Username</a> | <a
                                            href="{{route('user.reset-user-password', Auth::user()->id)}}">Change
                                        Password</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default panel-associated-organization">
                        <div class="panel-sub-heading">Associated Organization</div>
                        <div class="panel-body">
                            <div class="organization-logo"><img
                                        src="{{$organization[0]->logo ? url($organization[0]->logo_url) : url('images/no-logo.png')}}">
                            </div>
                            <div class="organization-detail">
                                <div class="organization-name">{{$organization[0]->name}}</div>
                                <ul>
                                    @if($organization[0]->telephone)
                                        <li class="telephone col-xs-6 col-md-4 col-lg-4">
                                            <label>Telephone</label><span>{{$organization[0]->telephone}}</span></li>
                                    @endif
                                    @if($organization[0]->twitter)
                                        <li class="twitter col-xs-6 col-md-4 col-lg-4"><label>Twitter</label><a
                                                    href="http://www.twitter.com/{{ $organization[0]->twitter }}">{{$organization[0]->twitter}}</a></li>
                                    @endif
                                    @if($organization[0]->organization_url)
                                        <li class="website col-xs-6 col-md-4 col-lg-4"><label>Website</label><a
                                                    href="{{$organization[0]->organization_url}}" target="_blank">{{$organization[0]->organization_url}}</a></li>
                                    @endif
                                    @if($organization[0]->address)
                                        <li class="address col-xs-6 col-md-4 col-lg-4">
                                            <label>Address</label><span>{{$organization[0]->address}}</span></li>
                                    @endif
                                    @if($organization[0]->country)
                                        <li class="country col-xs-6 col-md-4 col-lg-4">
                                            <label>Country</label><span>{{$getCode->getOrganizationCodeName('Country', $organization[0]->country)}}</span>
                                        </li>
                                    @endif
                                </ul>
                                {{--<div class="disqus-wrapper"><span>Disqus Comments : </span>{{($organization[0]->disqus_comments == 1) ? 'Enabled' : 'Disabled'}}</div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
