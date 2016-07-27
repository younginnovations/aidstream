var humanitarianScopeTypeOneVocabularyOptions = {'1-1': '1-1 - UN OCHA FTS', '1-2': '1-2 - Glide', '99': '99 - Reporting Organisation'};
var humanitarianScopeTypeTwoVocabularyOptions = {'2-1': '2-1 - Humanitarian Plan', '99': '99 - Reporting Organisation'};

var HumanitarianScopeVocabularyImprovisor = {
    selectHumanitarianScopeTypeOne: function (humanitarianScopeVocabulary) {
        for (var key in humanitarianScopeTypeOneVocabularyOptions) {
            var value = humanitarianScopeTypeOneVocabularyOptions[key];

            HumanitarianScopeVocabularyImprovisor.appendOptions(key, value, humanitarianScopeVocabulary);
        }
    },
    selectHumanitarianScopeTypeTwo: function (humanitarianScopeVocabulary) {
        for (var key in humanitarianScopeTypeTwoVocabularyOptions) {
            var value = humanitarianScopeTypeTwoVocabularyOptions[key];

            HumanitarianScopeVocabularyImprovisor.appendOptions(key, value, humanitarianScopeVocabulary);
        }
    },
    appendOptions: function (index, value, humanitarianScopeVocabulary) {
        $(humanitarianScopeVocabulary[0]).append($('<option/>', {
            value: index,
            html: value
        }));
    },
    toggleVocabularySelector: function (disabled, humanitarianScopeVocabulary) {
        if (disabled) {
            humanitarianScopeVocabulary.attr({
                disabled: disabled
            });
        } else {
            humanitarianScopeVocabulary.removeAttr('disabled');
        }
    },
    removeValues: function (humanitarianScopeVocabulary) {
        $(humanitarianScopeVocabulary[0].options).each(function (index, value) {
            if (index != 0) {
                $(value).remove();
            }
        });
    },
    initialize: function () {
        $('form .humanitarian_scope').delegate('.humanitarian-type', 'change', function () {
            var selection = $(this).val();
            var humanitarianScopeVocabulary = $(this).parent('.form-group').next('.form-group').children('select.humanitarian-vocabulary');
            HumanitarianScopeVocabularyImprovisor.removeValues(humanitarianScopeVocabulary);

            HumanitarianScopeVocabularyImprovisor.toggleVocabularySelector(false, humanitarianScopeVocabulary);

            if (selection == 1) {
                HumanitarianScopeVocabularyImprovisor.selectHumanitarianScopeTypeOne(humanitarianScopeVocabulary);
            } else if (selection == 2) {
                HumanitarianScopeVocabularyImprovisor.selectHumanitarianScopeTypeTwo(humanitarianScopeVocabulary);
            } else {
                HumanitarianScopeVocabularyImprovisor.toggleVocabularySelector(true, humanitarianScopeVocabulary);
            }

            humanitarianScopeVocabulary.select2();
        });

        $('form .humanitarian-type').trigger('change');

        $('form .humanitarian-vocabulary').each(function (index) {
            if (countryBudgetItems) {
                $(this).val(countryBudgetItems[index].vocabulary).select2();
            }
        });
    }
};

$(document).ready(function () {
    HumanitarianScopeVocabularyImprovisor.initialize();
});
