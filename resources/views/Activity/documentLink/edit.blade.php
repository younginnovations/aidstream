@extends('app')

@section('title', 'Activity Document Link - ' . $activityData->IdentifierTitle)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Document Link</span>
                        <div class="element-panel-heading-info"><span>{{$activityData->IdentifierTitle}}</span></div>
                    </div>
                    <div class="panel-action-btn">
                        <a href="{{ route('activity.show', $id) }}" class="btn btn-primary pull-right">View Activity
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->document_link->prototype()) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection

@section('foot')
    <div class="modal fade" id="upload_document">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Document Link</h4>
                </div>
                <div class="modal-body">
                    <div class="upload_form hidden">
                        <div class="alert alert-info">
                            <span>You can upload your document here. Once your document is uploaded,
                            a link will be provided. You can then select the link to use it.</span>
                        </div>
                        <div id="upload_message"></div>
                        <form class="form-horizontal" role="form" id="upload_file" method="POST"
                              enctype="multipart/form-data" action="{{ route('document.upload') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <div class="col-md-8">
                                    <label class="control-label">Please choose your document: </label>
                                    <input type="file" class="form-control" name="file" id="file"
                                           value="{{ old('file') }}" required="required">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="document_list">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>URL</th>
                                <th width="70px">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('js/upload-document.js')}}"></script>
    <style type="text/css">
        .loader {
            position: fixed;
            left: 0px;
            right: 0px;
            top: 0px;
            bottom: 0px;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            color: #FFFFFF;
            text-align: center;
            font-size: 60px;
            letter-spacing: -16px;
            -webkit-animation: mymove 1s infinite; /* Chrome, Safari, Opera */
            -webkit-animation-direction: alternate;
            animation: loading 1s infinite;
            animation-direction: alternate;
        }

        /* Chrome, Safari, Opera */
        @-webkit-keyframes loading {
            from {
                letter-spacing: -16px;
            }
            to {
                letter-spacing: 16px;
            }
        }

        @keyframes loading {
            from {
                letter-spacing: -16px;
            }
            to {
                letter-spacing: 16px;
            }
        }
    </style>
@endsection
