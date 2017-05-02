{!! Form::open(['route' => ['publishing-settings.handlePublisherIdChanged'], 'method' => 'post'])!!}
{!! Form::hidden('publisherId',$publisherId) !!}
{!! Form::hidden('changes',json_encode($changes)) !!}
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">@lang('publisherIdChanged.change_publisher_id')</h4>
        </div>
        <div class="modal-body">
            @if($isCorrect)
                <p>{!!  trans('publisherIdChanged.about_to_change_publisher_id', ['oldPublisher' => $oldPublisherId , 'publisherId' => $publisherId]) !!}</p>
                @if(count(getVal($changes,['organizationData'],[])) > 0 || count(getVal($changes,['activity'],[])) > 0)
                    <p>@lang('publisherIdChanged.changes_after_confirmation')</p>
                    <ul>
                        <li>@lang('publisherIdChanged.present_file_will_be_renamed')</li>
                        @if($inputApiKey)
                            <li>@lang('publisherIdChanged.current_files_will_be_deleted')</li>
                        @endif
                    </ul>
                @endif
                @if(count(getVal($changes,['organizationData'],[])) > 0)
                    <table>
                        <caption>@lang('publisherIdChanged.organization_data')</caption>
                        <thead>
                        <tr>
                            <th>@lang('publisherIdChanged.old_filename')</th>
                            <th>@lang('publisherIdChanged.new_filename')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(getVal($changes,['organizationData'],[]) as $organization)
                            <tr>
                                <td>
                                    <a target="_blank" href="{{sprintf('%s/%s',url(config('xmlFiles.xmlStorageDir')),getVal($organization,['oldFilename']))}}">{{getVal($organization,['oldFilename'])}}</a>
                                </td>
                                <td>{{getVal($organization,['newFilename'])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                @if(count(getVal($changes,['activity'],[])) > 0)
                    <table>
                        <caption>@lang('publisherIdChanged.activities')</caption>
                        <thead>
                        <tr>
                            <th>@lang('publisherIdChanged.old_filename')</th>
                            <th>@lang('publisherIdChanged.new_filename')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(getVal($changes,['activity'],[]) as $activity)
                            <tr>
                                <td><a target="_blank" href="{{sprintf('%s/%s',url(config('xmlFiles.xmlStorageDir')),getVal($activity,['oldFilename']))}}">{{getVal($activity,['oldFilename'])}}</a>
                                </td>
                                <td>{{getVal($activity,['newFilename'])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                <div>
                    @if($inputApiKey)
                        <p>@lang('publisherIdChanged.enter_api_key')</p>
                        {!! Form::label('apiKey', trans('publisherIdChanged.api_key')) !!}
                        {!! Form::text('apiKey', '',['id' => 'newApiKey']) !!}
                        <span id="apiStatus"></span>
                    @else
                        {!! Form::hidden('apiKey',$apiKey) !!}
                    @endif
                </div>
            @elseif(!$isUnique)
                <p>
                    {!! trans('publisherIdChanged.publisher_id_used',['publisherId' => $publisherId]) !!}
                </p>
            @elseif(!$isAuthorized)
                <p>
                    @lang('publisherIdChanged.not_authorized')
                </p>
            @else
                <p>
                    @lang('publisherIdChanged.publisher_id_incorrect')
                </p>
            @endif
        </div>
        <div class="modal-footer">
            @if($isCorrect)
                <button type="submit" class="btn btn-primary" id="saveChanges" {{ !$inputApiKey ?: 'disabled' }}>@lang('global.yes')</button>
            @endif
            <button type="button" class="btn btn-default" onclick="window.location.href= '/publishing-settings'">@lang('global.cancel')</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
