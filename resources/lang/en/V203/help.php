<?php
$help                                           = trans('V202/help');
$help['activity_defaults-budget_not_provided']  = "A code indicating the reason why this activity does not contain any iati-activity/budget elements. The value must exist in the BudgetNotProvided codelist.";
$help['Activity_DefaultAidTypeVocabulary-code'] = "A code for the vocabulary aid-type classifications. If omitted the AidType (OECD DAC) codelist is assumed. The code must be a valid value in the AidTypeVocabulary codelist";
$help['ActivityResultsIndicatorDimensionName']  = "Freetext description of a category being disaggregated.";
$help['ActivityResultsIndicatorDimensionValue'] = "Description of the value being disaggregated.";

return $help;
