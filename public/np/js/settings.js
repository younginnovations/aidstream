var filteredAgencies;

function agency(status) {
    var selectedCountry = $('#country').val();
    if (selectedRegistrationAgency && !status && selectedCountry == country) {
        filteredAgencies = '<option value="' + selectedRegistrationAgency + '" selected="selected">' + agencies[selectedRegistrationAgency] + '</option>';
    } else {
        filteredAgencies = '<option value="" selected="selected">Select an agency</option>';
    }

    return filteredAgencies;
}

$('#organisationRegistrationAgency')
    .html(agency(false));

$('#country').on('change', function () {
    var agencySelectField = $('#organisationRegistrationAgency');

    filteredAgencies = agency(true);

    agencySelectField.html(filteredAgencies).change();
});

$('#country, #organisationRegistrationAgency, #organisationRegistrationNumber').on('keyup change', function () {
    var selectedRegistrationNumber = $('#organisationRegistrationNumber');
    var selectedRegistrationAgency = $('#organisationRegistrationAgency');
    var selectedCountry = $('#country');
    var identifier = '';

    if (selectedCountry.val() == '' || selectedRegistrationAgency.val() == '' || selectedRegistrationNumber.val() == '') {
    } else {
        identifier = selectedRegistrationAgency.val() + '-' + selectedRegistrationNumber.val();
    }

    $('#organisationIatiIdentifier').val(identifier).trigger('blur');
});

// auto generates abbreviation from organization name
$("[name = 'organisationName']").on('change', function () {
    var wordList = getWordList($(this).val());
    var abbr = getAbbr(wordList);
    $("[name = 'organisationNameAbbreviation']").val(abbr);

    var ignoreList = ['and', 'of', 'the', 'an', 'a'];

    function getWordList(text) {
        var nameArray = text.split(/\ +/g);
        return nameArray.filter(function (value) {
            return ($.inArray(value.toLowerCase(), ignoreList) === -1 && value.length > 1);
        })
    }

    function getAbbr(wordList) {
        var abbr = '';
        for (var i in wordList) {
            var word = wordList[i];
            abbr += word.substr(0, 1);
        }
        return abbr.toLowerCase();
    }
});

