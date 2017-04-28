{!! Form::open(['route' => ['publishing-settings.handlePublisherIdChanged'], 'method' => 'post'])!!}
{!! Form::hidden('publisherId',$publisherId) !!}
{!! Form::hidden('changes',json_encode($changes)) !!}
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Change Publisher Id??</h4>
        </div>
        <div class="modal-body">
            @if($isCorrect)
                <p>You are about to change your Publisher Id from <strong>{{$oldPublisherId}}</strong> to <strong>{{$publisherId}}</strong></p>
                <p>The following changes will come into effect after your confirmation:</p>
                <ul>
                    <li>Your present file(s) will be renamed.</li>
                    @if($inputApiKey)
                        <li>Your current file(s) will be deleted from IATI Registry and the renamed files will be published.</li>
                    @endif
                </ul>
                @if(count(getVal($changes,['organizationData'],[])) > 0)
                    <table>
                        <caption>Organization Data</caption>
                        <thead>
                        <tr>
                            <th>Old Filename</th>
                            <th>New Filename</th>
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
                        <caption>Activities</caption>
                        <thead>
                        <tr>
                            <th>Old Filename</th>
                            <th>New Filename</th>
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
                        <p>Please enter the API Key for the new Publisher Id for the changes to come into effect.</p>
                        {!! Form::label('apiKey', 'Api Key') !!}
                        {!! Form::text('apiKey', '',['id' => 'newApiKey']) !!}
                        <span id="apiStatus"></span>
                    @else
                        {!! Form::hidden('apiKey',$apiKey) !!}
                    @endif
                </div>
            @elseif(!$isUnique)
                <p>
                    The Publisher Id <strong>{{$publisherId}}</strong> is already being used. Please check your Publisher Id and try again.
                </p>
            @elseif(!$isAuthorized)
                <p>
                    You are not authorized to change the Settings. Please contact your administrator to change the Settings.
                </p>
            @else
                <p>
                    The Publisher Id you have entered is incorrect. Since, these changes will affect your datasets and xml files, we cannot allow you to make these changes.
                </p>
            @endif
        </div>
        <div class="modal-footer">
            @if($isCorrect)
                <button type="submit" class="btn btn-primary" id="saveChanges" {{ !$inputApiKey ?: 'disabled' }}>Yes</button>
            @endif
            <button type="button" class="btn btn-default" onclick="window.location.href= '/publishing-settings'">Close</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
