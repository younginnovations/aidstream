@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @if(count($activity) > 0)
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <td>User Name</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity as $key => $value)
                        <tr>
                            <td>{{$value->user->username}}</td>
                            <td>{{trans($value->action,$value->param)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center no-data">No Activity Log Yet::</div>
                @endif
            </div>
        </div>
    </div>

@stop