@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <td>User Name</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($activity as $key => $value)
                        <tr>
                            <td>{{ Auth::user()->name }}</td>
                            <td>{{trans($value->action,$value->param)}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td align="center" colspan="3">No Activity Yet::</td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop