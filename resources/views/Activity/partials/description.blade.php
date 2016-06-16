@if(!emptyOrHasEmptyTemplate($descriptions))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.description')</div>
        @foreach($descriptions as $description)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{$getCode->getCodeNameOnly('DescriptionType', $description['type'])}} Description
                </div>
                <div class="activity-element-info">
                    {!! getFirstNarrative($description) !!}
                    @include('Activity.partials.viewInOtherLanguage' , ['otherLanguages' => getOtherLanguages($description['narrative']) ])
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.description.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'description'])}}" class="delete pull-right">remove</a>
    </div>
@endif
