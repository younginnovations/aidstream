@if(!empty($documentLinks))
    <div class="panel panel-default">
        <div class="panel-heading">Document Link
            <a href="{{route('activity.document-link.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($documentLinks as $documentLink)
                <div class="panel panel-default">
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Url: </div>
                            <div class="col-xs-12 col-sm-8">{{$documentLink['url']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Format: </div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('FileFormat', $documentLink['format'])}}</div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Title</div>
                            @foreach($documentLink['title'] as $title)
                                @foreach($title['narrative'] as $narrative)
                                    <div class="panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-sm-4">Text: </div>
                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Category</div>
                            <div class="panel-element-body row">
                                @foreach($documentLink['category'] as $category)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Code: </div>
                                        <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('DocumentCategory', $category['code'])}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Language</div>
                            <div class="panel-element-body row">
                                @foreach($documentLink['language'] as $language)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Code: </div>
                                        <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('Language', $language['language'])}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
