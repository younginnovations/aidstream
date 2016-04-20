@if(!empty($humanitarianScopes))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">
                Humanitarian Scope
            </div>
            <a href="{{route('activity.humanitarian-scope.index', $id)}}" class="edit-element">edit</a>
            <a href="{{route('activity.delete-element', [$id, 'humanitarian_scope'])}}" class="delete pull-right">remove</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($humanitarianScopes as $humanitarianScope)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        {{$getCode->getActivityCodeName('HumanitarianScopeType', $humanitarianScope['type'])}}
                    </div>
                </div>
                <div class="panel-body row">
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Vocabulary:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('HumanitarianScopeVocabulary', $humanitarianScope['vocabulary'])}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Vocabulary Uri</div>
                            <div class="col-xs-12 col-sm-8">{{$humanitarianScope['vocabulary_uri']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Code</div>
                            <div class="col-xs-12 col-sm-8">{{$humanitarianScope['code']}}</div>
                        </div>
                        @foreach($humanitarianScope['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
