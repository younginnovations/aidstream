@extends('tz.base.sidebar')

@section('title', 'Create Transaction')
@inject('code', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Edit Budget</div>
            </div>
            <div class="panel-body">
                <div class="create-form create-activity-form create-project-form edit-form">
                    {!! Form::open(['route' => ['project.budget.update', $projectId], 'method' => 'POST']) !!}

                    @if (old('budget'))
                        @foreach (old('budget') as $index => $budget)
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    {!! Form::hidden("budget[$index][budget_type]", 1, ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::label("budget[$index][period_start][0][date]", 'Budget Start Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text("budget[$index][period_start][0][date]", old("budget[$index][period_start][0][date]"), ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label("budget[$index][period_end][0][date]", 'Budget End Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text("budget[$index][period_end][0][date]", old("budget[$index][period_end][0][date]"), ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    {!! Form::label("budget[$index][value][0][amount]", 'Amount', ['class' => 'control-label required']) !!}
                                    {!! Form::text("budget[$index][value][0][amount]", old("budget[$index][value][0][amount]"), ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label("budget[$index][value][0][currency]", 'Currency', ['class' => 'control-label required']) !!}
                                    {!! Form::select("budget[$index][value][0][currency]", ['' => 'Select one of the following.'] + $currency, getVal($settings, ['default_field_values', 0, 'default_currency']), ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::hidden("budget[$index][value][0][value_date]", old("budget[$index][value][0][value_date]"), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach ($project->budget as $index => $budget)
                            <div class="added-new-block">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::hidden("budget[$index][budget_type]", 1, ['class' => 'form-control', 'required' => 'required']) !!}
                                        {!! Form::label("budget[$index][period_start][0][date]", 'Budget Start Date', ['class' => 'control-label required']) !!}
                                        {!! Form::text("budget[$index][period_start][0][date]", getVal($budget, ['period_start', 0, 'date']), ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label("budget[$index][period_end][0][date]", 'Budget End Date', ['class' => 'control-label required']) !!}
                                        {!! Form::text("budget[$index][period_end][0][date]", getVal($budget, ['period_end', 0, 'date']), ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::label("budget[$index][value][0][amount]", 'Amount', ['class' => 'control-label required']) !!}
                                        {!! Form::text("budget[$index][value][0][amount]", getVal($budget, ['value', 0, 'amount']), ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label("budget[$index][value][0][currency]", 'Currency', ['class' => 'control-label required']) !!}
                                        {!! Form::select("budget[$index][value][0][currency]", ['' => 'Select one of the following.'] + $currency, getVal($budget, ['value', 0, 'currency']), ['class' => 'form-control', 'required' => 'required']) !!}
                                        {!! Form::hidden("budget[$index][value][0][value_date]", getVal($budget, ['value', 0, 'value_date']), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0)" onclick="removeBudget(this)" class="remove_from_collection">Remove</a>
                        </div>
                    @endforeach

                    <button type="button" id="add-more-budget-edit" class="add-more">Add Another Budget</button>
                    {!! Form::submit('Update', ['class' => 'btn btn-primary btn-form btn-create']) !!}

                    {!! Form::close() !!}

                    @include('tz.project.partials.budget-clone')
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var currentBudgetCount;

        @if (old('budget'))
                currentBudgetCount = "{{ count(old('budget')) - 1 }}";
        @elseif ($project->budget)
                currentBudgetCount = "{!! count($project->budget) - 1 !!}";
        @else
                currentBudgetCount = 0;
        @endif
    </script>
    <script src="{{ asset('/js/tz/project.js') }}"></script>
    <script src="{{ asset('/js/tz/budget.js') }}"></script>
@stop
