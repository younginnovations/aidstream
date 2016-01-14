@extends('app')

@section('content')
    @inject('getCodeList', 'App\Core\Form\BaseForm')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel-content-heading panel-title-heading">Edit Profile</div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="POST" action="{{ route('user.update-profile', $user->id)}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{$user->first_name}}">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="{{$user->last_name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{$user->email}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Name</label>
                                <input type="text" class="form-control" name="organization_name" value="{{$organization->name}}">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Address</label>
                                <input type="text" class="form-control" name="organization_address" value="{{$organization->address}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Country</label>
                                <?php $countries = $getCodeList->getCodeList('Country', 'Organization'); ?>
                                <select name="country">
                                    <option value="">Select any option:</option>
                                    @foreach($countries as $countryIndex=>$country)
                                        <option value={{$countryIndex}} {{$organization->country != $countryIndex ?: 'selected="selected"'}}> {{$country}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Url</label>
                                <input type="text" class="form-control" name="organization_url" value="{{ $organization->organization_url }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Telephone</label>
                                <input type="text" class="form-control" name="organization_telephone" value="{{ $organization->telephone }}">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization Twitter</label>
                                <input type="text" class="form-control" name="organization_twitter" value="{{$organization->twitter }}">
                                <div><span>Please insert a valid twitter username. Example: '@oxfam' or 'oxfam'</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Upload Organization Logo</label>
                                <input type="file" class="form-control" name="organization_logo">
                                <div><span>Please use jpg/jpeg/png/gif format and 150x150 dimensions image.</span></div>
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Disqus Comments</label>
                                <input type="checkbox" name="disqus_comments" {{!$organization->disqus_comments ?: 'checked="checked"'}} value="1">
                                <div><span>Enable/disable comments on your organization page.</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-form btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

