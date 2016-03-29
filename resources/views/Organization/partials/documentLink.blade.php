@if(!emptyOrHasEmptyTemplate($document_link))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Document Link</div>
            <a href="{{ url('/organization/' . $orgId . '/document-link') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-2 row">
            @foreach($document_link as $documentLink)
                <div class="panel-heading">
                    <div class="activity-element-title">{{ $documentLink['url']}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Url:</div>
                                <div class="col-xs-12 col-xs-8">{{ $documentLink['url']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Format:</div>
                                <div class="col-xs-12 col-xs-8">{{ $documentLink['format']}}</div>
                            </div>
                            @foreach($documentLink['narrative'] as $documentLinkNarrative)
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-xs-4">Narrative Text:</div>
                                    <div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $documentLinkNarrative['language']) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="activity-element-title">Category</div>
                        </div>
                        <div class="panel-element-body">
                            @foreach($documentLink['category'] as $documentLinkCategory)
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-xs-4">Code:</div>
                                    <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('DocumentCategory', $documentLinkCategory['code'])}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="activity-element-title">Language</div>
                        </div>
                        <div class="panel-element-body">
                            @foreach($documentLink['language'] as $documentLinkLanguage)
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-xs-4">Code:</div>
                                    <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Language', $documentLinkLanguage['language'])}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="activity-element-title">Recipient Country</div>
                        </div>
                        @foreach($documentLink['recipient_country'] as $documentLinkRecipientCountry)
                            <div class="panel-drop-body">
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Code:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $documentLinkRecipientCountry['code'])}}</div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="activity-element-title">Narrative</div>
                                    </div>
                                    <div class="panel-drop-body row">
                                        @foreach($documentLinkRecipientCountry['narrative'] as $documentLinkNarrative)
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Text:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $documentLinkNarrative['language']) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
