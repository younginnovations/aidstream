@if(!empty($identifier))
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="activity-element-title">
                Identifier
            </div>
            <a href="{{route('activity.iati-identifier.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-sm-4">IATI Identifier Text:</div>
                <div class="col-xs-12 col-sm-8">{{ $identifier['iati_identifier_text'] }}</div>
            </div>
        </div>
    </div>
@endif
