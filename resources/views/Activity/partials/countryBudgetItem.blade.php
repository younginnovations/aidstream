@if(!empty($countryBudgetItems))
    <div class="panel panel-default">
        <div class="panel-heading">Country Budget Items
            <a href="{{route('activity.country-budget-items.index', $id)}}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-1">
            @foreach($countryBudgetItems as $countryBudgetItem)
                <div class="panel panel-default">
                    <div class="panel-element-body">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Vocabulary:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('BudgetIdentifierVocabulary', $countryBudgetItem['vocabulary'])}}</div>
                        </div>
                    </div>
                    <div class="panel-heading">Budget Item</div>
                    <div class="panel-element-body">
                        @foreach($countryBudgetItem['budget_item'] as $budgetItem)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Code:</div>
                                <div class="col-xs-12 col-sm-8">{{$countryBudgetItem['vocabulary'] == 1 ? $getCode->getActivityCodeName('BudgetIdentifier', $budgetItem['code']) : $budgetItem['code_text']}}</div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Percentage:</div>
                                <div class="col-xs-12 col-sm-8">{{$budgetItem['percentage']}}</div>
                            </div>
                            @foreach($budgetItem['description'] as $budgetNarrative)
                                @foreach($budgetNarrative['narrative'] as $narrative)
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Text:</div>
                                        <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
