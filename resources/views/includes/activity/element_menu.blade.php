@inject('defaultFieldGroups', 'App\Helpers\DefaultFieldGroups')

{{--*/
$fieldGroups = $defaultFieldGroups->get();
$filledStatus = $defaultFieldGroups->getFilledStatus($id);
/*--}}
<div class="element-menu-wrapper">
    <div class="element-sidebar-dropdown">
        <div class="edit-element">edit<span class="caret"></span></div>
    </div>
    <div class="element-sidebar-wrapper">
        @foreach($fieldGroups as $fieldGroupIndex => $fieldGroup)
            <div class="panel panel-default">
                <div class="panel-heading">{{$fieldGroupIndex}}</div>
                <div class="panel-body">
                    <ul class="nav">
                        @foreach($fieldGroup as $fieldIndex => $field)
                            @if ($filledStatus)
                                <li>
                                    {{--*/ $filled = $filledStatus[$fieldGroupIndex][$fieldIndex]; /*--}}
                                    <a href="{{ route(sprintf('activity.%s.index', str_replace('_', '-', $fieldIndex)), [$id]) }}" class="{{ $filled ? 'active' : '' }}"
                                       title="{{ $filled ? 'Edit ' : 'Add '}}{{ $field}}">
                                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                                        {{$field}}
                                    </a>
                                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Activity_' . $fieldIndex)">help text</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>
