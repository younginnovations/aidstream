@if(!empty($recipientCountries))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Recipient Country
            </div>
            <a href="{{route('activity.recipient-country.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($recipientCountries as $recipientCountry)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$getCode->getOrganizationCodeName('Country', $recipientCountry['country_code']) . ' ; ' . $recipientCountry['percentage']}}</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Percentage:</div>
                            <div class="col-xs-12 col-sm-8">{{$recipientCountry['percentage']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Code:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getOrganizationCodeName('Country', $recipientCountry['country_code'])}}</div>
                        </div>
                        @foreach($recipientCountry['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $getCode->getOrganizationCodeName('Language', $narrative['language'])) . ']'}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
