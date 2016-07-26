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
                    <div>
                        <div class="auth-name">{{Auth::user()->name}}</div>
                        <div class="edit-profile-block">
                            <a href="{{route('user.reset-user-password', Auth::user()->id)}}"
                               class="btn change-password">Change
                                Password</a>
                            <a href="{{route('user.edit-profile', Auth::user()->id)}}" class="edit-profile">Edit
                                Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 element-content-wrapper profile-content-wrapper">
                    <div class="panel panel-default panel-profile">
                        <div class="panel-body">
                            <div class="profile-img">
                                @if(Auth::user()->profile_url)
                                    <img src="{{Auth::user()->profile_url}}" width="200" height="200"
                                         alt="{{Auth::user()->name}}">
                                @else
                                    <img src="{{url('images/avatar-full.png')}}" width="200" height="200"
                                         alt="{{Auth::user()->name}}">
                                @endif
                            </div>
                            <div class="profile-info-block">
                                <div class="profile-basic-info">
                                    <span class="profile-username">{{Auth::user()->username}}</span>
                                <span class="profile-user-email"><a
                                            href="mailto:{{Auth::user()->email}}">{{Auth::user()->email}}</a></span>
                                    {{--<div class=""><a href="{{route('user.edit-profile', Auth::user()->id)}}" class="edit-profile">Edit Profile</a></div>--}}
                                </div>
                                <div class="profile-role-info">
                                    <dl>
                                        <dt>TimeZone</dt>
                                        <dd>{{Auth::user()->time_zone}}</dd>
                                    </dl>
                                    <dl>
                                        <dt>Language</dt>
                                        <dd>English</dd>
                                    </dl>
                                    <dl>
                                        <dt>Role</dt>
                                        <dd>{{Auth::user()->role->role}}</dd>
                                    </dl>
                                </div>
                            </div>
                            @if((Auth::user()->isAdmin()) && $organization->secondary_contact)
                                <div class="secondary-contact-block">
                                    <span>Secondary Contact</span>
                                    <div>
                                        <span class="profile-name">{{ getVal((array)$organization->secondary_contact,['first_name']) }} {{getVal((array)$organization->secondary_contact,['last_name'])}}</span>
                                        <a href="mailto:{{ getVal((array)$organization->secondary_contact,['email']) }}"> {{getVal((array)$organization->secondary_contact,['email'])}} </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-default panel-associated-organization">
                        <div class="panel-org-heading">
                            <div class="panel-sub-heading">Associated Organisation</div>
                            <a href="{{route('settings')}}" class="edit-profile">Edit Organisation</a>
                        </div>
                        <div class="panel-body">
                            <div class="organization-logo"><img
                                        src="{{$organization->logo ? url($organization->logo_url) : url('images/no-logo.png')}}">
                            </div>
                            <div class="organization-detail">
                                <div class="organization-name">{{getVal($organization->reporting_org,[0,'narrative',0,'narrative'])}}</div>
                                <ul>
                                    @if($organization->address)
                                        <li class="address col-xs-6 col-md-4 col-lg-3">
                                            <label>Address</label><span>{{$organization->address}}</span></li>
                                    @endif
                                    @if($organization->country)
                                        <li class="country col-xs-6 col-md-4 col-lg-3">
                                            <label>Country</label><span>{{$getCode->getOrganizationCodeName('Country', $organization->country)}}</span>
                                        </li>
                                    @endif
                                    <div class="view-organization-block">
                                        @if(getVal($organization->reporting_org,[0,'reporting_organization_identifier']))
                                            <div class="view-organization"><label>IATI Organisational
                                                    Identifier</label><span>{{getVal($organization->reporting_org,[0,'reporting_organization_identifier'])}}</span>
                                            </div>
                                        @endif
                                        @if($organization['reporting_org'][0]['reporting_organization_type'])
                                            <div class="view-organization"><label>Organisation Type</label>
                                            <span>
                                                {{substr($getCode->getOrganizationCodeName('OrganizationType',$organization['reporting_org'][0]['reporting_organization_type']),0,-4)}}
                                            </span>
                                            </div>
                                        @endif
                                    </div>
                                    @if($organization->telephone)
                                        <li class="telephone col-xs-6 col-md-4 col-lg-3">
                                            <label>Telephone</label><span>{{$organization->telephone}}</span></li>
                                    @endif
                                    @if($organization->twitter)
                                        <li class="twitter col-xs-6 col-md-4 col-lg-3"><label>Twitter</label><a
                                                    href="http://www.twitter.com/{{ $organization->twitter }}">{{$organization->twitter}}</a>
                                        </li>
                                    @endif
                                    @if($organization->organization_url)
                                        <li class="website col-xs-6 col-md-4 col-lg-3"><label>Website</label><a
                                                    href="{{$organization->organization_url}}"
                                                    target="_blank">{{$organization->organization_url}}</a></li>
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
