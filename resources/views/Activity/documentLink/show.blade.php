@extends('app')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper result-show">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Document Link</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activityData->IdentifierTitle}}</span>
                        </div>
                        <div class="panel-action-btn">
                            <a href="{{route('activity.show',$id)}}" class="btn btn-primary">View Activity</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-element-detail element-show">
                        <div class="panel-body">

                            @if(!emptyOrHasEmptyTemplate($documentLinks))
                                <div class="panel panel-default expanded">
                                    @foreach($documentLinks as $documentLink)
                                        {{--*/
                                            $documentLink = $documentLink['document_link'];
                                        /*--}}
                                        @if(!$documentLink['title'][0]['narrative'])
                                            {{--*/
                                            $documentLink['title'][0]['narrative'] = [['narrative' => '', 'language' => '']];
                                            /*--}}
                                        @endif
                                        <div class="panel-heading">
                                            <div class="activity-element-title">
                                                {{$documentLink['title'][0]['narrative'][0]['narrative'] .  hideEmptyArray('Organization', 'Language', $documentLink['title'][0]['narrative'][0]['language'])}}
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="panel-element-body row">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="col-xs-12 col-sm-4">Url:</div>
                                                        <div class="col-xs-12 col-sm-8">{{$documentLink['url']}}</div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="col-xs-12 col-sm-4">Format:</div>
                                                        <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('FileFormat', $documentLink['format'])}}</div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <div class="activity-element-title">Title</div>
                                                        </div>
                                                        <div class="panel-element-body row">
                                                            @foreach($documentLink['title'] as $title)
                                                                @foreach($title['narrative'] as $narrative)
                                                                    <div class="col-xs-12 col-md-12">
                                                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                                                                        <br/>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <div class="activity-element-title">Category</div>
                                                        </div>
                                                        <div class="panel-element-body row">
                                                            @foreach($documentLink['category'] as $category)
                                                                <div class="col-xs-12 col-md-12">
                                                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('DocumentCategory', $category['code'])}}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <div class="activity-element-title">Language</div>
                                                        </div>
                                                        <div class="panel-element-body row">
                                                            @foreach($documentLink['language'] as $language)
                                                                <div class="col-xs-12 col-md-12">
                                                                    <div class="col-xs-12 col-sm-4">Code:</div>
                                                                    <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('Language', $language['language'])}}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @if(array_key_exists('document_date', $documentLink))
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <div class="activity-element-title">Document Date</div>
                                                            </div>
                                                            <div class="panel-element-body row">
                                                                @foreach($documentLink['document_date'] as $date)
                                                                    <div class="col-xs-12 col-md-12">
                                                                        <div class="col-xs-12 col-sm-4">Date:</div>
                                                                        <div class="col-xs-12 col-sm-8">{{ ($date['date']) ? formatDate($date['date']) : '' }}</div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
