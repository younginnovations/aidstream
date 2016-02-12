@if(!empty($legacyDatas))
    <div class="panel panel-default">
        <div class="panel-heading">Legacy Data
            <a href="{{route('activity.legacy-data.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($legacyDatas as $legacyData)
                <div class="panel panel-default">
                    <div class="panel-heading">{{$legacyData['name'] . '; ' . $legacyData['value']}}</div>
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Name: </div>
                            <div class="col-xs-12 col-sm-8">{{$legacyData['name']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Value: </div>
                            <div class="col-xs-12 col-sm-8">{{$legacyData['value']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Iati Equivalent: </div>
                            <div class="col-xs-12 col-sm-8">{{$legacyData['iati_equivalent']}}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
