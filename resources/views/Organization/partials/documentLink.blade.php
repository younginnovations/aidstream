@if(!empty($document_link))
    <div class="panel panel-default">
        <div class="panel-heading">Document Link
            <a href="#" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            @foreach($document_link as $documentLink)
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
                                <div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . ' [' . $documentLinkNarrative['language'] . ']' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Category</div>
                    @foreach($documentLink['category'] as $documentLinkCategory)
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Code:</div>
                                <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('DocumentCategory', $documentLinkCategory['code'])}}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Language</div>
                    @foreach($documentLink['language'] as $documentLinkLanguage)
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Code:</div>
                                <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Language', $documentLinkLanguage['language'])}}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Recipient Country</div>
                    @foreach($documentLink['recipient_country'] as $documentLinkRecipientCountry)
                        <div class="panel-body panel-element-body row">
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-xs-4">Code:</div>
                                <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $documentLinkRecipientCountry['code'])}}</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            @foreach($documentLinkRecipientCountry['narrative'] as $documentLinkNarrative)
                                <div class="panel-heading">Narrative</div>
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . ' [' . $documentLinkNarrative['language'] . ']' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
