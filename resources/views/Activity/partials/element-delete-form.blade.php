<form class="delete-element-form" action="{{ route('activity.delete-element', [$id, $element]) }}" method="POST">
    {{ csrf_field() }}
    <input class="delete-element-submit pull-right" type="submit" value="{{ trans('global.remove') }}">
</form>