@if(! empty($otherLanguages))
    <span class="view-other-language">
        @if(count($otherLanguages) == 1 && empty($otherLanguages[0]['narrative']))
        @else
            @lang('activityView.view_in_other_languages')
        @endif

        <div class="view-language-info hidden">
            @foreach($otherLanguages as $otherLanguage)
                <ul>
                    <li>
                        <em>{!!  getLanguage($otherLanguage['language']) .' - '. checkIfEmpty($otherLanguage['narrative'] , 'Description Not Available')  !!}</em>
                    </li>
                </ul>
            @endforeach
        </div>
    </span>
@endif