@extends('app')

@section('title', 'Upload Activities')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        Import Xml
                    </div>
                    <div>
                        <a href="{{ route('activity.index') }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>Back to Activity List
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('xml-import.store') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group col-xs-6 col-sm-6 activity-file-choose">
                                        <label class="control-label">Import Xml</label>
                                        <input type="file" class="form-control" name="xml_file">
                                    </div>

                                    <button type="submit" class="btn pull-left">Upload</button>
                                </form>
                            </div>
                            {{--<div class="download-transaction-wrap">--}}
                                {{--<a href="{{route('download.activity-template')}}"--}}
                                   {{--class="btn btn-primary btn-form btn-submit">Download Activity Template</a>--}}
                                {{--<div>--}}
                                    {{--This template contains few basic elements that you have to fill to import into AidStream. Please make sure that you follow the structure and format of the template.--}}
                                    {{--For more details, please follow <a href="https://github.com/younginnovations/aidstream-new/wiki/Activity-Creation#2-bulk-activity-import" target="_blank">here</a>.--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
