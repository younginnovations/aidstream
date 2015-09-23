@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal" role="form" method="POST"
                      action="{{ route('admin.update-user-password', $user->id)}}">
                    <div class="panel panel-default">
                        <div class="panel-heading">Reset User Password</div>
                        <div class="panel-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @include('includes.side_bar_menu')
        </div>
    </div>
@endsection
