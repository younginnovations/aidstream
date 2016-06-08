@extends('tz.base.sidebar')

@section('title', 'Edit Profile - ' . $user->first_name)
@inject('getCodeList', 'App\Core\Form\BaseForm')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.errors')
        <div class="element-panel-heading">
            <div>Edit Profile</div>
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper profile-wrapper">
            <div class="create-form">
                <form class="form-edit-profile" role="form" method="POST"
                      action="{{ route('user.update-profile', $user->id)}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="collection_form col-md-12">
                        <div class="row">
                            <div class="form-block clearfix">
                                <div class="col-sm-12 title">Personal Information</div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                           value="{{$user->first_name}}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                           value="{{$user->last_name}}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control" name="email"
                                           value="{{$user->email}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="control-label">Time Zone</label>
                                    {{ Form::select('time_zone', ['' => 'Select Time Zone'] + $timeZone, $user->time_zone_id . ' : '. $user->time_zone) }}
                                </div>
                            </div>

                            <div class="form-block clearfix">
                                <div class="col-sm-12 title">Organization Information</div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Name</label>
                                    <input type="text" class="form-control" name="organization_name"
                                           value="{{$organization->name}}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Address</label>
                                    <input type="text" class="form-control" name="organization_address"
                                           value="{{$organization->address}}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Country</label>
                                    <?php $countries = $getCodeList->getCodeList('Country', 'Organization'); ?>
                                    <select name="country">
                                        <option value="">Select any option:</option>
                                        @foreach($countries as $countryIndex=>$country)
                                            <option value={{$countryIndex}} {{$organization->country != $countryIndex ?: 'selected="selected"'}}> {{$country}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Url</label>
                                    <input type="text" class="form-control" name="organization_url"
                                           value="{{ $organization->organization_url }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Telephone</label>
                                    <input type="text" class="form-control" name="organization_telephone"
                                           value="{{ $organization->telephone }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Organization Twitter</label>
                                    <input type="text" class="form-control" name="organization_twitter"
                                           value="{{$organization->twitter }}">
                                    <div class="description">
                                        <span>Please insert a valid twitter username. Example: '@oxfam
                                            ' or 'oxfam'</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Upload Organization Logo</label>
                                    <input type="file" class="form-control" name="organization_logo">
                                    <div class="description"><span>Please use jpg/jpeg/png/gif format and 150x150 dimensions image.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-form btn-submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
@stop
