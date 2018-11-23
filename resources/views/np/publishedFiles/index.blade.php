@extends('np.base.sidebar')

@section('title', 'Published Files')

@section('content')
    <div class="col-xs-9 col-lg-9 content-wrapper published-wrapper">
        @include('includes.response')
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">@lang('global.published_files')</div>
            </div>
            <h2 class="panel__sub__heading">@lang('global.activities_published_files')</h2>
            <div class="panel__body">
                @if (!$publishedFiles->isEmpty())
                    <form action="{{ route('lite.published-files.bulk-publish') }}" method="POST">
                        <div class="publish-btn"><input type="submit" value="{{trans('global.publish_activities_to_iati')}}"></div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="panel__table table-header">
                            <thead>
                            <tr>
                                <th width="30px"></th>
                                <th>@lang('lite/global.filename')</th>
                                <th>@lang('lite/global.published_date')</th>
                                <th width="200px">@lang('lite/global.registered_in_iati_registry')</th>
                                <th>@lang('lite/global.preview_as')</th>
                                <th class="action">@lang('lite/global.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($publishedFiles as $publishedFile)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="activity_files[]" value="{{ $publishedFile->id . ':' . $publishedFile->filename }}">
                                    </td>
                                    <td><a href="{{ url('/files/xml/' . $publishedFile->filename) }}" target="_blank">{{ $publishedFile->filename }}</a></td>
                                    <td>{{ lastPublishedDate($publishedFile) }}</td>
                                    <td>{{ $publishedFile->published_to_register ? trans('lite/settings.yes') : trans('lite/settings.no') }}</td>
                                    <td>
                                        <a href="{{ 'http://tools.aidinfolabs.org/csv/direct_from_registry/?xml=' . url('/files/xml/' . $publishedFile->filename) }}"
                                           target="_blank">@lang('lite/global.csv')</a></td>
                                    <td>
                                        @if($publishedFile->published_to_register == 0)
                                            <a data-href="{{ route('lite.published-files.delete', [$publishedFile->id])}}" class="delete-activity" data-toggle="modal"
                                               data-target="#delete-published-files-modal"> @lang('lite/global.delete') </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                @else
                    <div class="text-center no-data no-document-data">
                        @lang('global.not_added',['type' => trans('global.activity_file')])
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="delete-published-files-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('lite/settings.confirm_upgrade')</h4>
                </div>
                <form action="" method="POST" id="delete-published-file-form">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>
                            @lang('lite/global.confirm_delete')
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="submit-delete-file" class="btn btn-primary">@lang('lite/settings.yes')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lite/settings.no')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('lite/js/publishedFiles.js') }}"></script>
@stop
