@if(!emptyOrHasEmptyTemplate($legacyDatas))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.legacy_data')</div>
            <div class="activity-element-info">
                @foreach($legacyDatas as $legacyData)
                    <li>{{ $legacyData['name'] . ': '. $legacyData['value'] }}
                        <em>@lang('activityView.iati_equivalent')
                            : {!!   checkIfEmpty($legacyData['iati_equivalent']) !!}</em>
                    </li>
                @endforeach
            </div>
        </div>
        <a href="{{route('activity.legacy-data.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'legacy_data'])}}" class="delete pull-right">remove</a>
    </div>
@endif
