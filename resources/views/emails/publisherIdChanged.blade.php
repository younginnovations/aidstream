<p>Hi {{$first_name}} {{$last_name}},</p>
@if($status === true)
    <p>The publisher id for your account has been changed successfully.</p>
    <p>Following changes have been made.</p>
    @if(count(getVal($changes,['organizationData'],[])) > 0)
        <table border="1" style="border-collapse: collapse;width:400px;margin-bottom: 10px;" cellspacing="4" cellpadding="6">
            <caption style="font-weight:bold; margin: 10px 0; text-align: left;">Organization Data</caption>
            <thead>
            <tr>
                <th align="left">Old Filename</th>
                <th align="left">New Filename</th>
            </tr>
            </thead>
            <tbody>
            @foreach(getVal($changes,['organizationData'],[]) as $organization)
                <tr>
                    <td>{{getVal($organization,['oldFilename'])}}</td>
                    <td><a target="_blank" href="{{url(sprintf('%s/%s',config('xmlFiles.xmlStorageDir'),getVal($organization,['newFilename'])))}}">{{getVal($organization,['newFilename'])}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    @if(count(getVal($changes,['activity'],[])) > 0)
        <table border="1" style="border-collapse: collapse;width: 400px;" cellspacing="4" cellpadding="4">
            <caption style="font-weight:bold; margin: 10px 0; text-align: left;">Activities</caption>
            <thead>
            <tr>
                <th align="left">Old Filename</th>
                <th align="left">New Filename</th>
            </tr>
            </thead>
            <tbody>
            @foreach(getVal($changes,['activity'],[]) as $activity)
                <tr>
                    <td>{{getVal($activity,['oldFilename'])}}</td>
                    <td><a target="_blank" href="{{url(sprintf('%s/%s',config('xmlFiles.xmlStorageDir'),getVal($activity,['newFilename'])))}}">{{getVal($activity,['newFilename'])}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <p>If you have any questions, please feel free to send us an email with your queries at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
@else
    <p>Sorry! there was an error while changing your Publisher Id.</p>
    <p>{!! $status !!}</p>
    <p>Please contact us at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
@endif

<p>Happy publishing data!</p>
<p>Your AidStream Team</p>