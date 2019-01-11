@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['tag'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.tag') @if(array_key_exists('Tag',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(getVal($activityDataList, ['tag'], []) as $key => $tags)
        {{-- {{dd($tags)}} --}}
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">{{$tags['narrative'][0]['narrative']}}</div>
                <div class="activity-element-info">
                    {{-- @foreach($tags as $tag) --}}
                    {{-- {{dd($tags)}} --}}
                    <li>{{ getTagInformation($tags) }}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">@lang('global.show_more_info')</span>
                        <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                    </div>
                    <div class="more-info hidden">
                        @if(session('version') != 'V201')
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.vocabulary_uri')</div>
                                <div class="activity-element-info">{!!  checkIfEmpty(getClickableLink(getVal($tags,['vocabulary_uri'])))  !!}</div>
                            </div>
                        @endif
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.description')</div>
                            <div class="activity-element-info">
                                {!!  getFirstNarrative($tags)  !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($tags['narrative'])])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.tag.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'tag', 'id' => $id])
    </div>
@endif
