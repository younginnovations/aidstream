@extends('app')

@section('title', trans('title.country_budget_item').' - ' . $activityData->IdentifierTitle)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>@lang('element.country_budget_items')</span>
                        <div class="element-panel-heading-info"><span>{{$activityData->IdentifierTitle}}</span></div>
                        <div class="panel-action-btn">
                            <a href="{{ route('activity.show', $id) }}" class="btn btn-primary">@lang('global.view_activity')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->country_budget_item->prototype()) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection

@section('foot')
    <script type="text/javascript">
        function addMoreCallback() {
            $('.vocabulary').trigger('change');
        }
        $(document).ready(function () {
            /* change budget item code field according to selected vocabulary */
            $("form").on('change', '.vocabulary', function () {
                var parent = $(this).parent('.form-group');
                var vocabulary = $(this).val();
                var selectedCode = (vocabulary == 1) ? '.code' : '.code_text';
                var budgetItems = parent.siblings('.budget_item');
                $('.codes', budgetItems).addClass('hidden');
                $(selectedCode, budgetItems).removeClass('hidden');
            });
            $('.vocabulary').trigger('change');
        });
    </script>
@endsection
