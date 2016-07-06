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
                        List of Activities
                    </div>
                    <div>
                        <a href="{{ route('import-activity.index') }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>Back to Import Activities
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper csv-upload-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <p>There are {{ count($activities) }} activities in the file uploaded.</p>
                            <form id="import-activities" method="POST" action="{{ route('import-activity.import') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="imported-activities">
                                    @foreach($activities as $activity)
                                        {{--*/
                                        $isDuplicate = in_array($activity['data']['activity_identifier'], $duplicateIdentifiers);
                                        if($isDuplicate || isset($activity['errors']['duplicate'])) {
                                            $messageClass = 'activity-warning-block';
                                        } elseif($activity['errors']) {
                                            $messageClass = 'activity-errors-block';
                                        } else {
                                            $messageClass = 'activity-success-block';
                                        }
                                        /*--}}
                                        <div class="activity-block {{ $messageClass }}">
                                            <div class="activity-csv-status">
                                                @if($isDuplicate)
                                                    <div class="activity-warning">
                                                        <p>Duplicate Activity identifier.</p>
                                                        <div class="activity-check-title">
                                                            @include('Activity.activityTitle')
                                                            <p>(Please fix and upload again.)</p>
                                                        </div>
                                                    </div>
                                                @elseif(isset($activity['errors']['duplicate']))
                                                    <div class="activity-warning">
                                                        <p>Duplicate Activity. </p>
                                                        <div class="activity-check-title">
                                                            @include('Activity.activityTitle')
                                                            <p>{!! $activity['errors']['duplicate'] !!}</p>
                                                        </div>
                                                    </div>
                                                @elseif($activity['errors'])
                                                    <div class="activity-error">
                                                        <p>Errors Found.</p>
                                                        <div class="activity-check-title">
                                                            @include('Activity.activityTitle')
                                                            <ol style="list-style-type: decimal;">
                                                                @foreach($activity['errors'] as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ol>
                                                            <p>(Please fix these errors and upload this file again to
                                                                import this activity.)</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="activity-success">
                                                        <p>Errors not Found.</p>
                                                        <div class="activity-check-title">
                                                            @include('Activity.activityTitle')
                                                            <p>(Please click on the checkbox to import activity.)</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn_confirm" disabled="disabled"
                                        data-title="Import Activity Confirmation"
                                        data-message="Are you sure you want to import selected activities ?">Import
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    <script type="text/javascript" src="{{url('/js/chunk.js')}}"></script>
    <script type="text/javascript">
        Chunk.checkImport();
    </script>
@endsection
