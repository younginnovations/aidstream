@if(! empty($otherLanguages))
    <span class="view-other-language">
        @if(count($otherLanguages) == 1 && empty(getVal($otherLanguages, [0, 'narrative'], [])))
        @else
            @lang('global.view_in_other_languages')
        @endif

        <div class="view-language-info hidden">
            @foreach($otherLanguages as $otherLanguage)
                <ul>
                    <li>
                        <em>{!!  getLanguage(getVal($otherLanguage, ['language'])) .' - '. checkIfEmpty(getVal($otherLanguage, ['narrative']) , 'Description Not Available')  !!}</em>
                    </li>
                </ul>
            @endforeach
        </div>
    </span>
@endif