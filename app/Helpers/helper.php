<?php
use App\Models\Activity\Activity;
use App\Models\Settings;
use App\Services\Collection2;
use App\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * removes empty values
 * @param $data
 */
function removeEmptyValues(&$data)
{
    foreach ($data as &$subData) {
        if (is_array($subData)) {
            removeEmptyValues($subData);
        }
    }
    $data = array_filter(
        $data,
        function ($value) {
            return ($value !== '' && $value != []);
        }
    );
}

/**
 * trim an input
 * @param $input
 * @return string
 */
function trimInput($input)
{
    return trim(preg_replace('/\s+/', " ", $input));
}

/**
 * checks empty template or empty array
 * @param $data
 * @return bool
 * @internal param $input
 */
function emptyOrHasEmptyTemplate($data)
{
    $temp = $data;
    removeEmptyValues($temp);

    return (!boolval($temp));
}

/**
 * get default currency which is predefined under activity defaults
 * @return null
 */
function getDefaultCurrency()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_currency');
        }
    }

    $defaultCurrency = $defaultFieldValues ? getVal($defaultFieldValues, [0, 'default_currency']) : null;

    return $defaultCurrency;
}

/**
 * get default language which is predefined under  activity defaults
 * @return null
 */
function getDefaultLanguage()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_language');
        }
    }

    $defaultLanguage = $defaultFieldValues ? getVal($defaultFieldValues, [0, 'default_language'], null) : null;

    return $defaultLanguage;
}

/**
 * Get the required index from a nested array.
 * @param        $arr
 * @param        $arguments
 * @param string $default
 * @return string|array
 */
function getVal(array $arr, array $arguments, $default = "")
{
    (!is_string($arguments)) ?: $arguments = explode('.', $arguments);
    if (is_array($arr)) {
        if (isset($arr[$arguments[0]]) && count(array_slice($arguments, 1)) === 0) {
            return $arr[$arguments[0]];
        } else {
            if (isset($arr[$arguments[0]]) && is_array($arr[$arguments[0]])) {
                $result = getVal($arr[$arguments[0]], array_slice($arguments, 1), $default);

                if ((((gettype($result) === 'string')) && ($result != '')) || ((gettype($result) === 'integer'))) {
                    return $result;
                } elseif ($result) {
                    return $result;
                } else {
                    return $default;
                }

//                return $result ? $result : $default;
            } else {
                return $default;
            }
        }
    } else {
        if (isset($arr) && !is_array($arr)) {
            return $arr;
        } else {
            return $default;
        }
    }
}

/**
 * Checks if the request route contains prefix SuperAdmin.
 * @return bool
 */
function isSuperAdminRoute()
{
    if (request()->route()) {
        $routeAction = request()->route()->getAction();

        return isset($routeAction['SuperAdmin']);
    }

    return false;
}

/**
 * Checks if the request route contains prefix SuperAdmin.
 * @return bool
 */
function isMunicipalityAdminRoute()
{
    if (request()->route()) {
        $routeAction = request()->route()->getAction();

        return isset($routeAction['MunicipalityAdmin']);
    }

    return false;
}

function isMunicipalityAdmin()
{
    if(session('user_permission') == 8){
        return true;
    }

    return false;
}

/**
 * Check if the logged in user is admin or user of any organization.
 * @param User $user
 * @return bool
 */
function isUserOrAdmin(User $user)
{
    if (!$user->isSuperAdmin() && !$user->isGroupAdmin()) {
        return true;
    }

    return false;
}

/**
 * Get the language name for the given language code.
 * @param $code
 * @return
 */
function getLanguage($code)
{
    $code ?: $code = getDefaultLanguage();
    $languages = json_decode(
        file_get_contents(app_path() . config('filesystems.languages.v201.activity.language_codelist_path')),
        true
    );

    foreach ($languages['Language'] as $lang) {
        if ($lang['code'] === $code) {
            return $lang['name'];
        }
    }
}

/**
 * Get the language code for other than the first one.
 * @param array $language
 * @return array
 */
function getOtherLanguages(array $language)
{
    return array_slice($language, 1);
}

/**
 *
 * @param array $elements
 * @param       $type
 * @return Collection
 */
function groupActivityElements(array $elements, $type = "")
{
    return collect($elements)->groupBy($type);
}

/**
 * Get Owner Narrative
 * @param array $groupedIdentifiers
 * @return string
 */
function getOwnerNarrative(array $groupedIdentifiers)
{
    return getVal($groupedIdentifiers, ['owner_org', 0, 'narrative'], []);
}

/**
 * Returns the first element of Narrative.
 * @param array $narrative
 * @param null  $default
 * @return string
 */
function getFirstNarrative(array $narrative, $default = null)
{
    $narrativeElements = getVal($narrative, ['narrative', 0]);

    if (!$default) {
        $default = trans('global.not_available');
    }

    return (empty($narrativeElements['narrative'])) ? sprintf('<em>%s</em>', $default) :
        sprintf(
            "%s <em>(%s: %s)</em>",
            $narrativeElements['narrative'],
            trans('elementForm.language'),
            getLanguage(getVal($narrativeElements, ['language']))
        );
}

/**
 * Returns the telephone number / email as string with commas after each other.
 * @param       $type
 * @param array $contactInformation
 * @return string
 */
function getContactInfo($type, array $contactInformation)
{
    $arrayContactInfo = [];

    foreach ($contactInformation as $information) {

        $information        = checkIfEmailOrWebSite($type, $information);
        $arrayContactInfo[] = $information;
    }
    $stringContactInfo = implode(' , ', $arrayContactInfo);

    return (empty($stringContactInfo)) ? sprintf('<em>%s</em>', trans('global.not_available')) : $stringContactInfo;
}

/**
 * Check if the provided contact information type is email or website.
 * @param $type
 * @param $information
 * @return string
 */
function checkIfEmailOrWebSite($type, $information)
{
    if ($type == "website" && !empty($information[$type])) {
        $information = getClickableLink($information[$type]);

    } else {
        if ($type == "email" && !empty($information[$type])) {
            $information = sprintf("<a href='mailto:%s'>%s</a>", $information[$type], $information[$type]);
        } else {
            $information = $information[$type];
        }
    }

    return $information;
}

/**
 * Checks if the provided value is empty or not. If empty returns not available.
 * @param        $information
 * @param string $message
 * @return string
 */
function checkIfEmpty($information, $message = '<em>Not Available</em>')
{
    $message = ($message == '<em>Not Available</em>') ? '<em>' . trans('global.not_available') . '</em>' : $message;

    return (empty($information)) ? $message : $information;
}

/**
 * Get Recipient Information
 *
 * @param       $code
 * @param       $percentage
 * @param       $type
 * @return string
 */
function getRecipientInformation($code, $percentage, $type)
{
    $method = ($type == 'Country') ? 'getOrganizationCodeName' : 'getActivityCodeName';
    $name   = app('App\Helpers\GetCodeName')->$method($type, $code);
    $name   = ucfirst(strtolower(substr($name, 0, - 5)));

    if (empty($percentage)) {
        return sprintf('%s - %s', $code, $name);
    }

    return sprintf('%s - %s (%s%s)', $code, $name, $percentage, '%');
}

/**
 * Get the location reach code value.
 * @param array $locations
 * @return array
 */
function getLocationReach(array $locations)
{
    $newLocations = [];
    foreach ($locations as $location) {
        $code = getVal($location, ['location_reach', 0, 'code']);
        $code = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'GeographicLocationReach',
            $code
        );
        $code = ($code == "") ? trans('global.other') : sprintf('%s %s', $code, trans('elementForm.location'));

        $newLocations[$code][] = $location;
    }

    return $newLocations;
}

/**
 * Get Location vocabularies
 * @param array $location
 * @return string
 */
function getLocationVocabularies(array $location)
{
    $vocabularyCode = $location['vocabulary'];
    $vocabulary     = app('App\Helpers\GetCodeName')->getActivityCodeName(
        'GeographicVocabulary',
        $vocabularyCode
    );

    $vocabularyValue = substr($vocabulary, 0, - 4);

    return $vocabularyValue;
}

/**
 * Get the location ID vocabulary when the code is provided.
 * @param array $locationId
 * @return string
 */
function getLocationIdVocabulary(array $locationId)
{
    $vocabularyValue = getLocationVocabularies($locationId);

    if (empty($vocabularyValue)) {
        return sprintf('<em>%s</em>', trans('global.not_available'));
    } else {
        return sprintf('%s - %s (%s:%s)', $locationId['vocabulary'], $vocabularyValue, trans('elementForm.code'), $locationId['code']);
    }
}

/**
 * Get Administrative Vocabulary
 * @param array $location
 * @return string
 */
function getAdministrativeVocabulary(array $location)
{
    $administrativeVocabulary = getLocationVocabularies($location);

    if (empty($administrativeVocabulary)) {
        return sprintf('<em>%s</em>', trans('global.not_available'));
    } else {
        return sprintf(
            '%s - %s (%s:%s , %s: %s)',
            $location['vocabulary'],
            $administrativeVocabulary,
            trans('elementForm.code'),
            checkIfEmpty($location['code']),
            trans('elementForm.level'),
            checkIfEmpty($location['level'])
        );
    }

}

/**
 * Get the location point provided an array of location in format.
 * @param array $location
 * @return string
 */
function getLocationPoint(array $location)
{
    $latitude  = getVal($location, ['point', 0, 'position', 0, 'latitude']);
    $longitude = getVal($location, ['point', 0, 'position', 0, 'longitude']);

    $srsLink = (empty(getVal($location, ['point', 0, 'srs_name'])) ? sprintf('<em>%s</em>', trans('global.not_available')) : getClickableLink(
        getVal($location, ['point', 0, 'srs_name'])
    )
    );
    $latLong = (empty($latitude && $longitude)) ? sprintf('<em>%s</em>', trans('global.not_available')) : sprintf('%s, %s', $latitude, $longitude);

    return sprintf('%s (<em>%s: %s </em>)', $latLong, trans('elementForm.srs_name'), $srsLink);
}

/**
 * Returns the location properties values based upon the code is provided in a specific format.
 * @param array $location
 * @param       $codeType
 * @param       $codeNameType
 * @param int   $lengthToCut
 * @return string
 */
function getLocationPropertiesValues(array $location, $codeType, $codeNameType, $lengthToCut = - 4)
{
    $codeValue         = getVal($location, [$codeType, 0, 'code']);
    $codeNameWithValue = getCodeNameWithCodeValue($codeNameType, $codeValue, $lengthToCut);

    return $codeNameWithValue;
}

/**
 * Get sector information when sector array is provided.
 * @param array $sector
 * @param       $percentage
 * @return string
 */
function getSectorInformation(array $sector, $percentage)
{
    $sector           = getSectorStructure($sector);
    $sectorVocabulary = $sector['sector_vocabulary'];

    if ($sectorVocabulary == 1 || $sectorVocabulary == "") {
        $sectorCodeValue = app('App\Helpers\GetCodeName')->getCodeNameOnly('Sector', $sector['sector_code'], - 7);

        if (empty($percentage)) {
            return sprintf(
                '%s - %s',
                checkIfEmpty($sector['sector_code'], trans('global.sector_code_not_available')),
                checkIfEmpty($sectorCodeValue),
                trans('global.sector_code_value_not_available')
            );
        }

        return sprintf(
            '%s - %s (%s)',
            checkIfEmpty($sector['sector_code'], trans('global.sector_code_not_available')),
            checkIfEmpty($sectorCodeValue, trans('global.sector_code_value_not_available')),
            $percentage . "%"
        );

    } else {
        if ($sectorVocabulary === "2") {
            $sectorCodeValue = app('App\Helpers\GetCodeName')->getCodeNameOnly(
                'SectorCategory',
                $sector['sector_category_code'],
                - 5
            );
            if (empty($percentage)) {
                return sprintf(
                    '%s - %s',
                    checkIfEmpty($sector['sector_category_code'], trans('global.sector_code_not_available')),
                    checkIfEmpty($sectorCodeValue, trans('global.sector_code_value_not_available'))
                );
            }

            return sprintf(
                '%s - %s (%s)',
                checkIfEmpty($sector['sector_category_code'], trans('global.sector_code_not_available')),
                checkIfEmpty($sectorCodeValue, trans('global.sector_code_value_not_available')),
                $percentage . " %"
            );

        } else {
            if (empty($percentage)) {
                return $sector['sector_text'];
            }

            return sprintf(
                '%s (%s)',
                checkIfEmpty($sector['sector_text'], trans('global.sector_text_not_available')),
                $percentage . "%"
            );

        }
    }
}

/**
 * Get Tag information when tag array is provided.
 * @param array $tag
 * @return string
 */
function getTagInformation(array $tag)
{
    $tagVocabulary = app('App\Helpers\GetCodeName')->getCodeNameOnly('TagVocabulary', $tag['tag_vocabulary']);

    return $tag['tag_vocabulary'].' - '.$tagVocabulary;
}

/**
 * Returns the clickable link when the link is provided
 * @param $url
 * @return string
 */
function getClickableLink($url)
{
    return ($url == "") ? sprintf('<em>%s</em>', trans('global.not_available')) : sprintf("<a target='_blank' href='%s'> %s</a>", $url, $url);
}

/**
 * Returns the codename with Code value in format. eg. 1 - Exact
 * @param $codeNameType
 * @param $codeValue
 * @param $lengthToCut
 * @return string
 */
function getCodeNameWithCodeValue($codeNameType, $codeValue, $lengthToCut)
{
    $codeName = app('App\Helpers\GetCodeName')->getCodeNameOnly($codeNameType, $codeValue, $lengthToCut);

    if ($codeValue == "") {
        return sprintf(sprintf('<em>%s</em>', trans('global.not_available')));
    } else {
        return sprintf('%s - %s', $codeValue, ucfirst($codeName));
    }
}

/**
 * Get the country budget items in format:
 * @param       $vocabularyType
 * @param array $countryBudgetItem
 * @return string
 */
function getCountryBudgetItems($vocabularyType, array $countryBudgetItem)
{
    $budgetItemCode = ($vocabularyType == 1) ? getCodeNameWithCodeValue(
        'BudgetIdentifier',
        $countryBudgetItem['code'],
        - 7
    ) : $countryBudgetItem['code_text'];

    return (empty($countryBudgetItem['percentage'])) ? sprintf('%s', $budgetItemCode) : sprintf(
        '%s (%s%s)',
        $budgetItemCode,
        $countryBudgetItem['percentage'],
        '%'
    );
}

/**
 * Get Budget of the country with currency. In format: 202020 Nepalese Rupee ( Valued at May 13, 2016)
 * @param array $budget
 * @param null  $key
 * @return string
 */
function getBudgetInformation($key = null, array $budget)
{
    $budgetInformation                            = [];
    $budgetValue                                  = getVal($budget, ['value', 0]);
    $currencyDate                                 = getCurrencyValueDate($budgetValue, "planned");
    $period                                       = getBudgetPeriod($budget);
    $budgetInformation['currency_with_valuedate'] = $currencyDate;
    $budgetInformation['period']                  = $period;

    if (session('version') != 'V201') {
        if (array_key_exists('status', $budget)) {
            $budgetInformation['status'] = getCodeNameWithCodeValue('BudgetStatus', $budget['status'], - 4);
        }
    }

    return (array_key_exists($key, $budgetInformation) ? $budgetInformation[$key] : null);
}

/**
 * Group the budget elements and planned disbursement elements according to type.
 * @param $budgets
 * @param $type
 * @return array
 */
function groupBudgetElements($budgets, $type)
{
    $newBudgetItems = [];

    foreach ($budgets as $budget) {
        $budgetType                    = (empty($budget[$type])) ? "1" : $budget[$type];
        $newBudgetItems[$budgetType][] = $budget;
    }

    return $newBudgetItems;
}

/**
 * Get the currency with its value date and currency code value. Eg. 20000 Lek (Valued at August 13, 2016)
 * @param $budgetValue
 * @param $type
 * @return string
 */
function getCurrencyValueDate($budgetValue, $type)
{
    $budgetAmount = $budgetValue['amount'];
    $currency     = $budgetValue['currency'];
    $valueDate    = ($type == "planned") ? formatDate($budgetValue['value_date']) : formatDate(
        $budgetValue['date']
    );
    $currency     = app('App\Helpers\GetCodeName')->getCodeNameOnly('Currency', $currency, - 6);


    $currency = (empty($currency)) ? getDefaultCurrency() : $currency;

    if (empty($budgetAmount)) {
        return sprintf('<em>%s</em>', trans('global.amount_not_available'));
    }

    return sprintf(
        '%s %s <em>(%s %s)</em>',
        number_format(round($budgetAmount, 2)),
        $currency,
        trans('global.valued_at'),
        $valueDate
    );
}

/**
 * Get Budget Period.
 * @param array $budget
 * @return string
 */
function getBudgetPeriod(array $budget)
{
    $periodStart = formatDate(getVal($budget, ['period_start', 0, 'date']));
    $periodEnd   = formatDate(getVal($budget, ['period_end', 0, 'date']));

    return sprintf('%s - %s', $periodStart, $periodEnd);

}

/**
 * Get the planned disbursement organization details.
 * @param array $disbursement
 * @param       $type
 * @return string
 */
function getDisbursementOrganizationDetails(array $disbursement, $type)
{
    $organization = getVal($disbursement, [$type, 0], []);
    $ref          = getVal($organization, ['ref']);
    $activity_id  = getVal($organization, ['activity_id']);
    $type         = getVal($organization, ['type']);

    $details = sprintf(
        '<em>(%s: %s , %s: %s , Type: %s)</em >;',
        trans('elementForm.ref'),
        checkIfEmpty($ref),
        trans('elementForm.activity_id'),
        checkIfEmpty($activity_id),
        checkIfEmpty($type)
    );

    return $details;
}

/**
 * Group the condition elements by the condition type.
 * @param array $conditions
 * @return array
 */
function groupConditionElements(array $conditions)
{
    $newConditions = [];

    foreach ($conditions['condition'] as $condition) {
        $conditionType                 = $condition['condition_type'];
        $newConditions[$conditionType] = $condition;
    }

    return $newConditions;
}

/**
 * Group the Result elements based upon the type.
 * @param array $results
 * @return array
 */
function groupResultElements(array $results)
{
    $newResults = [];

    foreach ($results as $result) {

        $resultType                     = getVal($result, ['result', 'type']);
        $resultTypeValue                = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'ResultType',
            $resultType,
            - 4
        );
        $newResults[$resultTypeValue][] = $result['result'];
    }

    return $newResults;
}

/**
 * write brief description
 * @param array $reference
 * @return string
 */
function getIndicatorReference(array $reference)
{
    $vocabulary = ($reference['vocabulary'] == "") ? '<em>Vocabulary not set</em>' : getCodeNameWithCodeValue(
        'IndicatorVocabulary',
        $reference['vocabulary'],
        - 4
    );

    $code         = checkIfEmpty($reference['code']);
    $indicatorUri = checkIfEmpty(getVal($reference, ['indicator_uri']));
    $indicatorUri = (empty($indicatorUri)) ? sprintf('<em>%s</em>', trans('global.not_available')) : getClickableLink($indicatorUri);


    return sprintf('%s <br> (Code: %s <br> Indicator URI: %s )', $vocabulary, $code, $indicatorUri);

}

/**
 * Returns the baseline values for the indicator.
 * @param       $measure
 * @param array $baseLine
 * @return string
 */
function getResultsBaseLine($measure, array $baseLine)
{

    $year    = checkIfEmpty($baseLine['year']);
    $measure = ($measure == 2) ? '%' : trans_choice('activityView.units', $baseLine['value']);
    $value   = ($baseLine['value'] == "") ? sprintf('<em>%s</em>', trans('global.not_available')) : $baseLine['value'] . ' ' . $measure;

    return sprintf('%s (%s: %s)', $value, trans('elementForm.year'), $year);
}

/**
 * Get Indicator Period in a format.
 * @param       $measure
 * @param array $periods
 * @return array
 * @internal param array $period
 */
function getIndicatorPeriod($measure, array $periods)
{
    $outputPeriod      = [];
    $finalOutputPeriod = [];

    foreach ($periods as $period) {

        $targetValue                  = getVal($period, ['target', 0, 'value']);
        $actualValue                  = getVal($period, ['actual', 0, 'value']);
        $periodValue                  = getBudgetPeriod($period);
        $periodMeasure                = ($measure == 2) ? '%' : ' ' . trans_choice('activityView.units', $targetValue);
        $targetValue                  = ($targetValue == "") ? sprintf('<em>%s</em>', trans('global.not_available')) : $targetValue . $periodMeasure;
        $actualValue                  = ($actualValue == "") ? sprintf('<em>%s</em>', trans('global.not_available')) : $actualValue . $periodMeasure;
        $outputPeriod['period']       = $periodValue;
        $outputPeriod['target_value'] = $targetValue;
        $outputPeriod['actual_value'] = $actualValue;
        $outputPeriod['target']       = getVal($period, ['target', 0]);
        $outputPeriod['actual']       = getVal($period, ['actual', 0]);

        $finalOutputPeriod[] = $outputPeriod;

    }

    return $finalOutputPeriod;
}

/**
 * Get additional details for the target / actual value
 * @param array $target
 * @param null  $key
 * @return array
 * @internal param $type
 * @internal param array $period
 */
function getTargetAdditionalDetails(array $target, $key = null)
{
    $details = [];
    if (session('version') != 'V201') {
        $details['locationRef'] = getLocationRef('location', $target);
        $details['dimension']   = getDimension($target);
    }
    $details['first_comment'] = getFirstNarrative(getVal($target, ['comment', 0], []));

    return (array_key_exists($key, $details)) ? checkIfEmpty($details[$key]) : sprintf('<em>%s</em>', trans('global.not_available'));
}

/**
 * Get the location ref for any period.
 * @param $type
 * @param $target
 * @return array|string
 */
function getLocationRef($type, $target)
{
    $locationRef = [];
    if (getVal($target, [$type])) {
        foreach ($target[$type] as $location) {
            $locationRef[] = checkIfEmpty($location['ref']);
        }
        $locationRef = implode(',', $locationRef);
    }

    return $locationRef;
}

/**
 * @param $target
 * @return array|string
 */
function getDimension($target)
{
    $dimensions = [];

    if (getVal($target, ['dimension'])) {
        foreach ($target['dimension'] as $dimension) {
            $name = $dimension['name'];
            if (empty($name)) {
                return $dimension = sprintf('<em>%s</em>', trans('global.not_available'));
            } else {
                $value        = $dimension['value'];
                $dimensions[] = sprintf('%s (%s)', $name, $value);
            }
        }
    }

    $dimensions = implode(' , ', $dimensions);

    return $dimensions;
}

/**
 * Group Transaction Elements.
 * @param array $transactions
 * @return array
 */
function groupTransactionElements(array $transactions)
{
    $newTransactions = [];
    foreach ($transactions as $transaction) {
        $transaction = getVal($transaction, ['transaction']);

        $transactionTypeCode = getVal($transaction, ['transaction_type', 0, 'transaction_type_code']);
        $transactionType     = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'TransactionType',
            $transactionTypeCode
        );

        $newTransactions[$transactionType][] = $transaction;
    }

    return $newTransactions;
}

/**
 * Get transaction details provided transaction array.
 * @param array $transaction
 * @param       $type
 * @return string
 */
function getTransactionProviderDetails(array $transaction, $type)
{
    $organizationIdentifierCode = checkIfEmpty(getVal($transaction, ['organization_identifier_code']));
    $activityId                 = ($type == 'provider') ? $transaction['provider_activity_id'] : $transaction['receiver_activity_id'];
    $activityId                 = checkIfEmpty($activityId);
    $transactionType            = (session('version') != 'V201') ? checkIfEmpty(
        getVal($transaction, ['type'], sprintf('<em>%s</em>', trans('global.not_available')))
    ) : sprintf('<em>%s</em>', trans('global.not_available'));
    $activityIdText             = ($type == 'provider') ? trans('elementForm.provider_activity_id') : trans('elementForm.receiver_activity_id');

    return sprintf(
        '<em>
        (%s: %s , %s: %s , %s: %s)
        </em>',
        trans('elementForm.organisational_identifier_code'),
        $organizationIdentifierCode,
        $activityIdText,
        $activityId,
        trans('elementForm.type'),
        $transactionType
    );
}

/**
 * Returns the details of the transaction sector.
 * @param array $sector
 * @return string
 */
function getTransactionSectorDetails(array $sector)
{
    $sector     = getSectorStructure($sector);
    $vocabulary = checkIfEmpty(
        app('App\Helpers\GetCodeName')->getCodeNameOnly('SectorVocabulary', $sector['sector_vocabulary'])
    );
    if (session('version') != 'V201' && array_key_exists('vocabulary_uri', $sector)) {
        $vocabularyURI = ($sector['vocabulary_uri'] == "") ? sprintf('<em> %s </em>', trans('global.not_available')) : getClickableLink(
            $sector['vocabulary_uri']
        );

        return sprintf('<em>(%s: %s , %s: %s )</em>', trans('elementForm.vocabulary'), $vocabulary, trans('elementForm.vocabulary_uri'), $vocabularyURI);
    } else {

        return sprintf('<em>(%s: %s)</em>', trans('elementForm.vocabulary'), $vocabulary);
    }

}

/**
 * Returns the country code with country name.
 * @param $countryCode
 * @return string
 */
function getCountryNameWithCode($countryCode)
{
    if ($countryCode == "") {
        return sprintf('<em>%s</em>', trans('global.not_available'));
    } else {
        $countryName = substr(
            app('App\Helpers\GetCodeName')->getOrganizationCodeName('Country', $countryCode),
            0,
            - 4
        );

        return sprintf('%s - %s', $countryCode, $countryName);
    }
}

/**
 * Get the description of the recipient region.
 * @param array $region
 * @return string
 */
function getRecipientRegionDetails(array $region)
{
    $region ? $region : $region = [
        "region_code"    => '',
        "vocabulary"     => '',
        "vocabulary_uri" => '',
        "narrative"      => [
            [
                "narrative" => "",
                "language"  => ""
            ]
        ]
    ];
    $vocabulary = checkIfEmpty(
        app('App\Helpers\GetCodeName')->getCodeNameOnly('SectorVocabulary', $region['vocabulary'])
    );

    if (session('version') != 'V201' && array_key_exists('vocabulary_uri', $region)) {
        $vocabularyURI = ($region['vocabulary_uri'] == "") ? sprintf('<em>%s</em>', trans('global.not_available')) : getClickableLink(
            $region['vocabulary_uri']
        );

        return sprintf('<em>(%s: %s %s: %s )</em>', trans('elementForm.vocabulary'), $vocabulary, trans('elementForm.vocabulary_uri'), $vocabularyURI);
    }

    return sprintf('<em>(%s: %s)</em>', trans('elementForm.vocabulary'), $vocabulary);
}

/**
 * Get the document link languages in comma separated way.
 * @param array $languages
 * @return array|string
 */
function getDocumentLinkLanguages(array $languages)
{
    $newLanguage = [];

    foreach ($languages as $language) {
        $newLanguage[] = getLanguage($language['language']);
    }

    $newLanguage = implode(', ', $newLanguage);

    return $newLanguage;

}

/**
 * Get First Name of the organization.
 * @param array $orgName
 * @return string
 */
function getFirstOrgName(array $orgName)
{
    $name     = checkIfEmpty(getVal($orgName, [0, 'narrative']));
    $language = checkIfEmpty(getLanguage(getVal($orgName, [0, 'language'])));

    return sprintf('%s <em>(%s:  %s)</em>', $name, trans('elementForm.language'), $language);
}

/**
 * Group the recipient Country Budget by recipient country name.
 * @param array $countryBudgets
 * @return array
 */
function groupByCountry(array $countryBudgets)
{
    $newCountryBudget = [];

    foreach ($countryBudgets as $countryBudget) {
        $countryCode                      = getVal($countryBudget, ['recipient_country', 0, 'code']);
        $countryName                      = getCountryNameWithCode($countryCode);
        $newCountryBudget[$countryName][] = $countryBudget;
    }

    return $newCountryBudget;
}

/**
 * Group the contact information by contact type.
 * @param array $contacts
 * @return array
 */
function groupContactInformation(array $contacts)
{
    $newContact = [];

    foreach ($contacts as $contact) {
        $contactTypeCode                = ($contact['type'] == "") ? 1 : $contact['type'];
        $contactTypeName                = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'ContactType',
            $contactTypeCode
        );
        $newContact[$contactTypeName][] = $contact;
    }

    return $newContact;
}

/**
 * Group policy marker elements by vocabulary.
 * @param array $policyMarkers
 * @return array
 */
function groupPolicyMarkerElement(array $policyMarkers)
{
    $newPolicyMarker = [];

    foreach ($policyMarkers as $policyMarker) {
        $vocabularyCode                     = ($policyMarker['vocabulary'] == "") ? 1 : $policyMarker['vocabulary'];
        $vocabularyName                     = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'PolicyMarkerVocabulary',
            $vocabularyCode
        );
        $newPolicyMarker[$vocabularyName][] = $policyMarker;
    }

    return $newPolicyMarker;
}

/**
 * Group sector elements by sector vocabulary
 * @param array $sectorElements
 * @return array
 */
function groupSectorElements(array $sectorElements)
{
    $newSectorElements = [];

    foreach ($sectorElements as $sectorElement) {
        $sectorVocabularyCode                   = ($sectorElement['sector_vocabulary'] == "") ? 1 : $sectorElement['sector_vocabulary'];
        $sectorVocabulary                       = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'SectorVocabulary',
            $sectorVocabularyCode
        );
        $newSectorElements[$sectorVocabulary][] = $sectorElement;
    }

    return $newSectorElements;
}

/**
 * structure of sector
 * @param $sector
 * @return array
 */
function getSectorStructure($sector)
{
    return $sector ? $sector : [
        "sector_vocabulary"    => '',
        "vocabulary_uri"       => '',
        "sector_code"          => '',
        "sector_category_code" => '',
        "sector_text"          => '',
        "narrative"            => [
            [
                "narrative" => '',
                "language"  => ''
            ]
        ]
    ];
}


/**
 * @return bool
 */
function xmlImportIsStarted()
{
    $filePath = storage_path('xmlImporter/tmp/file/' . session('org_id') . '/' . auth()->user()->id . '/status.json');

    if (file_exists($filePath)) {
        return getVal(json_decode(file_get_contents($filePath), true), ['xml_import_status'], null) ? true : false;
    }

    return false;
}


/**
 * Provides sector name
 *
 * @param array $sector
 * @return mixed
 */
function getSectorName(array $sector)
{
    $codeNameHelper = app()->make('App\Helpers\GetCodeName');

    if ($sector['sector_vocabulary'] == 1) {
        return $codeNameHelper->getCodeNameOnly('Sector', getVal($sector, ['sector_code'], ''), - 8);
    } elseif ($sector['sector_vocabulary'] == 2) {
        return $codeNameHelper->getCodeNameOnly('Sector', getVal($sector, ['sector_category_code'], ''), - 5);
    }

    return $codeNameHelper->getCodeNameOnly('SectorVocabulary', getVal($sector, ['sector_vocabulary']));
}

/**
 * Provides Sector codes
 *
 * @param array $sector
 * @return string
 */
function getSectorCode(array $sector)
{
    if ($sector['sector_vocabulary'] == 1) {
        return getVal($sector, ['sector_code'], '');
    } elseif ($sector['sector_vocabulary'] == 2) {
        return getVal($sector, ['sector_category_code'], '');
    }

    return getVal($sector, ['sector_text'], '');

}

/**
 * Returns formatted date
 *
 * @param $format
 * @param $date
 * @return false|string
 */
function dateFormat($format = 'M d, Y', $date)
{
    if ($date != '') {
        if ((str_contains($date, '/'))) {
            $formattedDate = str_replace('/', '-', $date);

            return date($format, strtotime($formattedDate));
        }

        return date($format, strtotime($date));
    }

    return '';
}

/**
 * Check if the vocabulary of a Sector is empty.
 *
 * @param $sectorData
 * @return bool
 */
function hasEmptySectorVocabulary($sectorData)
{
    return (!boolval(getVal($sectorData, ['sector_vocabulary'], [])));
}

/**
 * Check if the vocabularies of all Sectors in a Sector array is empty.
 *
 * @param $sectors
 * @return bool
 */
function checkAllVocabularies($sectors)
{
    foreach ($sectors as $sector) {
        if (hasEmptySectorVocabulary($sector)) {
            return false;
        }
    }

    return true;
}

/**
 * Returns only upto 100 character of long reporting organization name
 *
 * @param array $reportingOrg
 * @return array $reportingOrg
 */
function trimReportingOrg(array $reportingOrg)
{
    $reportingOrg[0]['narrative'][0]['narrative'] = substr(getVal($reportingOrg, [0, 'narrative', 0, 'narrative']), 0, 100);

    return $reportingOrg;
}

/**
 * Check if a SuperAdmin user has logged in.
 *
 * @return bool
 */
function superAdminIsLoggedIn()
{
    return (session('role_id') == 3);
}

/**
 * Helper function for add money suffix such as M for millions.
 *
 * @param $money
 * @return float|int|string
 */
function moneySuffix($money)
{
    if ($money > 9999999999999) {
        $trillion = (float) number_format(round($money / 1000000000000, 2), 2) . ' T';

        return $trillion;
    }

    if ($money > 9999999999) {
        $billion = (float) number_format(round($money / 1000000000, 2), 2) . ' B';

        return $billion;
    }

    if ($money > 999999) {
        $million = (float) number_format(round($million = $money / 1000000, 2), 2) . ' M';

        return $million;
    }

    return number_format(round($money));
}

function isRegisteredForTz()
{
    if (auth()->check()) {
        if (auth()->user()->organization->system_version_id === 3) {
            return true;
        }
    }

    return false;
}

function isRegisteredForNp()
{
    if (auth()->check()) {
        if (auth()->user()->organization->system_version_id === 4) {
            return true;
        }
    }

    return false;
}

function isTzSubDomain()
{
    if ($host = request()->getHost()) {
        if (in_array('tz', explode('.', strtolower($host)))) {
            return true;
        }
    }

    return false;
}

function isNpSubDomain()
{
    if($host = request()->getHost()) {
        if(in_array('np', explode('.', strtolower($host)))) {
            return true;
        }
    }

    return false;
}

/**
 * Check if an Activity Xml file exists.
 *
 * @param $activity
 * @return bool
 */
function fileExists($activity)
{
    $filename = getVal($activity, [0, 'filename']);

    return file_exists(public_path('/files/xml/' . $filename));
}

/**
 * Returns encoding type of the file.
 * Returns UTF-8 if any exception or charset is not found.
 *
 * @param $file
 * @return string
 */
function getEncodingType($file)
{
    try {
        $response = exec('file -i ' . $file->getPathname());
        $charset  = strripos($response, 'charset=');

        if ($charset) {
            return strtoupper(substr($response, $charset + strlen('charset=')));
        }

        return 'UTF-8';
    } catch (\Exception $exception) {
        return 'UTF-8';
    }
}

/**
 * Return filename for the segmented activity.
 *
 * @param Activity $activity
 * @param          $publisherId
 * @return string
 */
function segmentedXmlFile(Activity $activity, $publisherId)
{
    $recipientCountry = (array) $activity['recipient_country'];
    $recipientRegion  = (array) $activity['recipient_region'];

    if (count($recipientRegion) == 1 && !isEmpty($recipientRegion, 'region_code')) {
        return $recipientRegion[0]['region_code'];
    } elseif (count($recipientCountry) == 1 && !isEmpty($recipientCountry, 'country_code')) {
        return strtolower($recipientCountry[0]['country_code']);
    } elseif (count($recipientCountry) >= 1) {
        $maxPercentage = 0;
        $countryCode   = array_first(
            $recipientCountry,
            function () {
                return true;
            }
        );
        $code          = strtolower($countryCode['country_code']);
        foreach ($recipientCountry as $country) {
            $percentage = $country['percentage'];
            if ($percentage > $maxPercentage) {
                $maxPercentage = $percentage;
                $code          = strtolower($country['country_code']);
            }
        }

        return sprintf('%s-%s', $publisherId, $code);
    }

    return sprintf('%s-%s', $publisherId, '998');
}

/**
 * Check if an array is empty at the provided $key.
 * @param array $data
 * @param       $key
 * @return bool
 */
function isEmpty(array $data, $key)
{
    $isEmpty = false;

    foreach ($data as $index => $item) {
        $isEmpty = empty(getVal($item, [$key], ''));
    }

    return $isEmpty;
}

/**
 * Check if the publisher id is being changed for the organization.
 * @return bool
 */
function isPublisherIdBeingChanged()
{
    $filePath = sprintf('%s/%s/%s/%s', storage_path(), 'publisherIdChanged', session('org_id'), 'status.json');
    if (file_exists($filePath)) {
        {
            return true;
        }
    }

    return false;
}

/**
 * Check if the entered published is already present in db.
 *
 * @param $newPublisherId
 * @return bool
 */
function isUniquePublisherId($newPublisherId)
{
    $ids = Settings::select('registry_info')->where('organization_id', '<>', session('org_id'))->get()->toArray();

    foreach ($ids as $id) {
        if (getVal($id, ['registry_info', 0, 'publisher_id']) == $newPublisherId) {
            return false;
        }
    }

    return true;
}

function collect2($value = null)
{
    return new Collection2($value);
}

