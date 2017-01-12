@extends('app')

@section('title', trans('title.document_link').' - ' . $activityData->IdentifierTitle)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>@lang('element.document_link')</span>
                        <div class="element-panel-heading-info"><span>{{$activityData->IdentifierTitle}}</span></div>
                        <div class="panel-action-btn btn-action-wrap">
                            <a href="{{route('activity.document-link.show',[$id, $documentLinkId])}}" class="btn btn-primary btn-view-it">@lang('global.view_document_link')</a>
                        </div>
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
                    <h4 class="modal-title" id="myModalLabel">@lang('title.add_document_link')</h4>
                </div>
                <div class="modal-body">
                    <div class="upload_form hidden">
                        <div class="alert alert-info">
                            <span>@lang('global.upload_document_text')</span>
                        </div>
                        <div id="upload_message"></div>
                        <form class="form-horizontal" role="form" id="upload_file" method="POST"
                              enctype="multipart/form-data" action="{{ route('document.upload') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <div class="col-md-8">
                                    <label class="control-label">@lang('global.choose_your_document'): </label>
                                    <input type="file" class="form-control" name="file" id="file"
                                           value="{{ old('file') }}" required="required">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('global.upload')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="document_list">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>@lang('global.url')</th>
                                <th width="70px">@lang('global.action')</th>
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
@endsection
