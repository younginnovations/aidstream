@if(!emptyOrHasEmptyTemplate($sectors))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.sector') @if(array_key_exists('Sector',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupSectorElements($sectors) as $key => $sectors)
            <div class="activity-element-list">
                <div class="activity-element-label">{{$key}}</div>
                <div class="activity-element-info">
                    @foreach($sectors as $sector)
                        <li>{!! checkIfEmpty(getSectorInformation($sector , $sector['percentage']))  !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            @if(session('version') != 'V201')
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('elementForm.vocabulary_uri')</div>
                                    <div class="activity-element-info">{!!  checkIfEmpty(getClickableLink(getVal($sector,['vocabulary_uri'])))  !!}</div>
                                </div>
                            @endif
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.description')</div>
                                <div class="activity-element-info">
                                    {!!  getFirstNarrative($sector)  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($sector['narrative'])])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.sector.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'sector'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
