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
                <p>You are about to change your publisher id from <strong>{{$oldPublisherId}}</strong> to <strong>{{$publisherId}}</strong></p>
                <p>The following changes will come into effect after confirmation:</p>
                <ul>
                    <li>Your present file(s) will be renamed.</li>
                    @if($inputApiKey)
                        <li>Your current files will be deleted from IATI Registry and the renamed files will be published.</li>
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
                        <p>Please enter your api key to reflect changes in registry.</p>
                        {!! Form::label('apiKey', 'Api Key') !!}
                        {!! Form::text('apiKey', '',['id' => 'newApiKey']) !!}
                        <span id="apiStatus"></span>
                    @else
                        {!! Form::hidden('apiKey',$apiKey) !!}
                    @endif
                </div>
            @elseif(!$isUnique)
                <p>
                    The publisher id <strong>{{$publisherId}}</strong> has already been used. Please check your publisher id and try again.
                </p>
            @elseif(!$isAuthorized)
                <p>
                    You are not authorized to change the settings. Please contact your administrator to change the settings.
                </p>
            @else
                <p>
                    The publisher id you have entered is incorrect. Since, these changes will affect your datasets and xml files, we cannot allow you to make these changes.
                </p>
            @endif
        </div>
        <div class="modal-footer">
            @if($isCorrect)
                <button type="submit" class="btn btn-primary" id="saveChanges" @if($inputApiKey) disabled @endif>Yes</button>
            @endif
            <button type="button" class="btn btn-default" onclick="window.location.href= '/publishing-settings'">Close</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
