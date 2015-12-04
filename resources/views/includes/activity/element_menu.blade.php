@inject('defaultFieldGroups', 'App\Helpers\DefaultFieldGroups')

{{--*/
$fieldGroups = $defaultFieldGroups->get();
$filledStatus = $defaultFieldGroups->getFilledStatus($id);
/*--}}
<div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
    @foreach($fieldGroups as $fieldGroupIndex => $fieldGroup)
        <div class="panel panel-default">
            <div class="panel-heading">{{$fieldGroupIndex}}</div>
            <div class="panel-body">
                <ul class="nav">
                    @foreach($fieldGroup as $fieldIndex => $field)
                        <li>
                            <a href="{{ route(sprintf('activity.%s.index', str_replace('_', '-', $fieldIndex)), [$id]) }}">
                                <span class="glyphicon {{ $filledStatus[$fieldGroupIndex][$fieldIndex] ? 'glyphicon-ok-circle' : 'glyphicon-remove-circle' }}"></span>
                                {{$field}}
                            </a>
                            <span class="help-text">help text</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>
