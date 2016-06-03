@if(isset($edit))
    <div class="col-sm-12 add-wrap">
        <h2>Results/Outcomes Documents</h2>
        {!! Form::hidden('document_link[0][category][0][code]', 'A08') !!}
        {!! Form::hidden('document_link[0][format]', 'text/html') !!}
        {!! Form::hidden('document_link[0][title][0][narrative][0][language]', "") !!}
        {!! Form::hidden('document_link[0][language]', '[]') !!}
        {!! Form::hidden('document_link[0][id]', getVal($documentLinks, ['document_link', 0, 'id'])) !!}

        <div class="col-sm-6">
            {!! Form::label('result_document_title', 'Title', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[0][title][0][narrative][0][narrative]', getVal($documentLinks, ['document_link', 0, 'title', 0, 'narrative', 0, 'narrative']), ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-6">
            {!! Form::label('result_document_url', 'Document URL', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[0][url]', getVal($documentLinks, ['document_link', 0, 'url']), ['class' => 'form-control']) !!}
            <span>Example: http://example.com</span>
        </div>
    </div>

    <div class="col-sm-12 add-wrap">
        <h2>Annual Reports</h2>
        {!! Form::hidden('document_link[1][category][0][code]', 'B01') !!}
        {!! Form::hidden('document_link[1][format]', 'text/html') !!}
        {!! Form::hidden('document_link[1][title][0][narrative][0][language]', "") !!}
        {!! Form::hidden('document_link[1][language]', '[]') !!}
        {!! Form::hidden('document_link[1][id]', getVal($documentLinks, ['document_link', 1, 'id'])) !!}
        <div class="col-sm-6">
            {!! Form::label('annual_document_title', 'Title', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[1][title][0][narrative][0][narrative]', getVal($documentLinks, ['document_link', 1, 'title', 0, 'narrative', 0, 'narrative']), ['class' => 'form-control']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('annual_document_url', 'Document Url', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[1][url]', getVal($documentLinks, ['document_link', 1, 'url']), ['class' => 'form-control']) !!}
            <span>Example: http://example.com</span>
        </div>

    </div>
@else
    <div class="col-sm-12 add-wrap">
        <h2>Results/Outcomes Documents</h2>
        {!! Form::hidden('document_link[0][category][0][code]', 'A08') !!}
        {!! Form::hidden('document_link[0][format]', 'text/html') !!}
        {!! Form::hidden('document_link[0][title][0][narrative][0][language]', "") !!}
        {!! Form::hidden('document_link[0][language]', '[]') !!}

        <div class="col-sm-6">
            {!! Form::label('result_document_title', 'Title', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[0][title][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-6">
            {!! Form::label('result_document_url', 'Document URL', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[0][url]', null, ['class' => 'form-control']) !!}
            <span>Example: http://example.com</span>
        </div>
    </div>

    <div class="col-sm-12 add-wrap">
        <h2>Annual Reports</h2>
        {!! Form::hidden('document_link[1][category][0][code]', 'B01') !!}
        {!! Form::hidden('document_link[1][format]', 'text/html') !!}
        {!! Form::hidden('document_link[1][title][0][narrative][0][language]', "") !!}
        {!! Form::hidden('document_link[1][language]', '[]') !!}
        <div class="col-sm-6">
            {!! Form::label('annual_document_title', 'Title', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[1][title][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
        </div>

        <div class="col-sm-6">
            {!! Form::label('annual_document_url', 'Document Url', ['class' => 'control-label']) !!}
            {!! Form::text('document_link[1][url]', null, ['class' => 'form-control']) !!}
            <span>Example: http://example.com</span>
        </div>
    </div>
@endif
