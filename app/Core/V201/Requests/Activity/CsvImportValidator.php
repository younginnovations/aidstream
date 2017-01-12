<?php namespace App\Core\V201\Requests\Activity;

use App\Core\V201\Traits\GetCodes;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

/**
 * Class CsvImportValidator
 * @package App\Core\V201
 */
class CsvImportValidator
{
    use GetCodes;
    const REQUIRED_NONEMPTY_FIELD      = 1;
    const IDENTICAL_INTERNAL_REFERENCE = 1;

    function __construct()
    {
        Validator::extend(
            'multiple_value_in',
            function ($attribute, $value, $parameters, $validator) {
                $inputs = explode(';', $value);
                foreach ($inputs as $input) {
                    if (!in_array($input, $parameters)) {
                        return false;
                    }
                }

                return true;
            }
        );

        Validator::extendImplicit(
            'required_any',
            function ($attribute, $value, $parameters, $validator) {
                for ($i = 1; $i < count($parameters); $i = $i + 2) {
                    $values = $parameters[$i];
                    if (!empty($values)) {
                        return true;
                    }
                }

                return false;
            }
        );

        Validator::extendImplicit(
            'required_only_one',
            function ($attribute, $value, $parameters, $validator) {
                $counter = 0;
                foreach ($parameters as $parameterIndex => $parameter) {
                    if (($parameterIndex % 2 != 0) && (!empty($parameter))) {
                        $counter ++;
                    }
                }

                if ($counter == self::REQUIRED_NONEMPTY_FIELD) {
                    return true;
                }

                return false;
            }
        );

        Validator::extendImplicit(
            'unique_validation',
            function ($attribute, $value, $parameters, $validator) {
                $csvDatas = Excel::load($parameters[2])->get()->toArray();
                $counter  = 0;
                $csvFiled = $parameters[3];
                foreach ($csvDatas as $csvDataIndex => $csvData) {
                    if ($csvData[$csvFiled] == $parameters[1]) {
                        $counter ++;
                    }
                }

                if ($counter == self::IDENTICAL_INTERNAL_REFERENCE) {
                    return true;
                }

                return false;
            }
        );
    }

    /**
     * check if date is valid or not
     * @param $date
     * @return bool
     */
    public function validateDate($date)
    {
        $dateFormat = DateTime::createFromFormat('Y-m-d', $date);
        $date       = explode('-', $dateFormat);
        $year       = $date[0];
        $month      = $date[1];
        $day        = $date[2];
        $checkDate  = checkdate($month, $day, $year);

        return ($dateFormat && $checkDate);
    }

    /**
     * check if the csv file data is valid or not
     * @param $file
     * @return Validator
     */
    public function getDetailedCsvValidator($file)
    {
        $transactions      = Excel::load($file)->get()->toArray();
        $transactionHeader = Excel::load($file)->get()->first()->keys()->toArray();
        $templateHeader    = Excel::load(app_path('Core/V201/Template/Csv/iati_transaction_template_detailed.csv'))->get()->first()->keys()->toArray();

        if (count(array_intersect($transactionHeader, $templateHeader)) !== count($templateHeader)) {
            return null;
        }

        $transactionTypeCodes           = implode(',', $this->getCodes('TransactionType', 'Activity'));
        $disbursementChannelCodes       = implode(',', $this->getCodes('DisbursementChannel', 'Activity'));
        $sectorVocabularyCodes          = implode(',', $this->getCodes('SectorVocabulary', 'Activity'));
        $recipientCountryCodes          = implode(',', $this->getCodes('Country', 'Organization'));
        $recipientRegionCodes           = implode(',', $this->getCodes('Region', 'Activity'));
        $recipientRegionVocabularyCodes = implode(',', $this->getCodes('RegionVocabulary', 'Activity'));
        $flowTypeCodes                  = implode(',', $this->getCodes('FlowType', 'Activity'));
        $financeTypeCodes               = implode(',', $this->getCodes('FinanceType', 'Activity'));
        $aidTypeCodes                   = implode(',', $this->getCodes('AidType', 'Activity'));
        $tiedStatusCodes                = implode(',', $this->getCodes('TiedStatus', 'Activity'));

        $rules    = [];
        $messages = [];
        foreach ($transactions as $transactionIndex => $transactionRow) {
            $rules = array_merge(
                $rules,
                [
                    "$transactionIndex.transaction_ref"             => sprintf(
                        'required|unique_validation:%s.transaction_ref,%s,%s,transaction_ref',
                        $transactionIndex,
                        trimInput($transactionRow['transaction_ref']),
                        $file
                    ),
                    "$transactionIndex.transactiontype_code"        => 'required|in:' . $transactionTypeCodes,
                    "$transactionIndex.transactiondate_iso_date"    => 'required|date',
                    "$transactionIndex.transactionvalue_value_date" => 'required|date',
                    "$transactionIndex.transactionvalue_text"       => 'required|numeric',
                    "$transactionIndex.description_text"            => 'required',
                    "$transactionIndex.disbursementchannel_code"    => 'in:' . $disbursementChannelCodes,
                    "$transactionIndex.sector_vocabulary"           => 'in:' . $sectorVocabularyCodes,
                    "$transactionIndex.recipientcountry_code"       => 'in:' . $recipientCountryCodes,
                    "$transactionIndex.recipientregion_code"        => 'in:' . $recipientRegionCodes,
                    "$transactionIndex.recipientregion_vocabulary"  => 'in:' . $recipientRegionVocabularyCodes,
                    "$transactionIndex.flowtype_code"               => 'in:' . $flowTypeCodes,
                    "$transactionIndex.financetype_code"            => 'in:' . $financeTypeCodes,
                    "$transactionIndex.aidtype_code"                => 'in:' . $aidTypeCodes,
                    "$transactionIndex.tiedstatus_code"             => 'in:' . $tiedStatusCodes,
                ]
            );

            $messages = array_merge(
                $messages,
                [
                    "$transactionIndex.transaction_ref.required"             => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.transaction') . '-' . trans('elementForm.ref')]
                    ),
                    "$transactionIndex.transaction_ref.unique_validation"    => trans(
                        'validation.csv_unique',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.transaction') . '-' . trans('elementForm.ref')]
                    ),
                    "$transactionIndex.transactiontype_code.required"        => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_type') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.transactiontype_code.in"              => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_type') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.transactiondate_iso_date.required"    => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_date') . '-' . trans('elementForm.iso_date')]
                    ),
                    "$transactionIndex.transactiondate_iso_date.date"        => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_value') . '-' . trans('elementForm.value_date')]
                    ),
                    "$transactionIndex.transactionvalue_value_date.required" => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_value') . '-' . trans('elementForm.value_date')]
                    ),
                    "$transactionIndex.transactionvalue_value_date.date"     => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_value') . '-' . trans('elementForm.value_date')]
                    ),
                    "$transactionIndex.transactionvalue_text.required"       => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_value') . '-' . trans('elementForm.text')]
                    ),
                    "$transactionIndex.transactionvalue_text.numeric"        => trans(
                        'validation.csv_numeric',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.transaction_value') . '-' . trans('elementForm.text')]
                    ),
                    "$transactionIndex.description_text.required"            => trans(
                        'validation.csv_required',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.description') . '-' . trans('elementForm.text')]
                    ),
                    "$transactionIndex.disbursementchannel_code.in"          => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.disbursement_channel') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.sector_vocabulary.in"                 => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.sector') . '-' . trans('elementForm.vocabulary')]
                    ),
                    "$transactionIndex.recipientcountry_code.in"             => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.recipient_country') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.recipientregion_code.in"              => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.recipient_region') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.recipientregion_vocabulary.in"        => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('element.recipient_region') . '-' . trans('elementForm.vocabulary')]
                    ),
                    "$transactionIndex.flowtype_code.in"                     => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.flow_type') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.financetype_code.in"                  => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.finance_type') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.aidtype_code.in"                      => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.aid_type') . '-' . trans('elementForm.code')]
                    ),
                    "$transactionIndex.tiedstatus_code.in"                   => trans(
                        'validation.csv_invalid',
                        ['number' => $transactionIndex + 1, 'attribute' => trans('elementForm.tied_status') . '-' . trans('elementForm.code')]
                    )
                ]
            );

            $sectorVocabulary = $transactionRow['sector_vocabulary'];
            if ($sectorVocabulary == 1) {
                $sectorCodes                                  = implode(',', $this->getCodes('Sector', 'Activity'));
                $rules["$transactionIndex.sector_code"]       = 'in:' . $sectorCodes;
                $messages["$transactionIndex.sector_code.in"] = trans(
                    'validation.csv_invalid',
                    ['number' => $transactionIndex + 1, 'attribute' => trans('element.sector') . '-' . trans('elementForm.code')]
                );
            } elseif ($sectorVocabulary == 2) {
                $sectorCodes                                  = implode(',', $this->getCodes('SectorCategory', 'Activity'));
                $rules["$transactionIndex.sector_code"]       = 'in:' . $sectorCodes;
                $messages["$transactionIndex.sector_code.in"] = trans(
                    'validation.csv_invalid',
                    ['number' => $transactionIndex + 1, 'attribute' => trans('element.sector') . '-' . trans('elementForm.code')]
                );
            }

        }

        return Validator::make($transactions, $rules, $messages);
    }

    /**
     * Check if the activities in csv are valid or not
     * @param $file
     * @param $identifiers
     * @return mixed
     */
    public function isValidActivityCsv($file, $identifiers)
    {
        try {
            $activities            = Excel::load($file)->get()->toArray();
            $activityStatus        = implode(',', $this->getCodes('ActivityStatus', 'Activity'));
            $sectorCategory        = implode(',', $this->getCodes('Sector', 'Activity'));
            $recipientCountryCodes = implode(',', $this->getCodes('Country', 'Organization'));
            $recipientRegionCodes  = implode(',', $this->getCodes('Region', 'Activity'));
            $rules                 = [];
            $messages              = [];
            $identifiers           = implode(',', $identifiers);
            foreach ($activities as $activityIndex => $activityRow) {
                $rules = array_merge(
                    $rules,
                    [
                        "$activityIndex.activity_identifier"                => sprintf(
                            'required|unique_validation:%s.activity_identifier,%s,%s,activity_identifier|not_in:%s',
                            $activityIndex,
                            trimInput($activityRow['activity_identifier']),
                            $file,
                            $identifiers
                        ),
                        "$activityIndex.activity_title"                     => 'required',
                        "$activityIndex.actual_start_date"                  => sprintf(
                            'date|required_any:%s.actual_start_date,%s,%s.actual_end_date,%s,%s.planned_start_date,%s,%s.planned_end_date,%s',
                            $activityIndex,
                            trimInput($activityRow['actual_start_date']),
                            $activityIndex,
                            trimInput($activityRow['actual_end_date']),
                            $activityIndex,
                            trimInput($activityRow['planned_start_date']),
                            $activityIndex,
                            trimInput($activityRow['planned_end_date'])
                        ),
                        "$activityIndex.actual_end_date"                    => 'date',
                        "$activityIndex.planned_start_date"                 => 'date',
                        "$activityIndex.planned_end_date"                   => 'date',
                        "$activityIndex.description_general"                => sprintf(
                            'required_any:%s.description_general,%s,%s.description_objectives,%s,%s.description_target_group,%s',
                            $activityIndex,
                            trimInput($activityRow['description_general']),
                            $activityIndex,
                            trimInput($activityRow['description_objectives']),
                            $activityIndex,
                            trimInput($activityRow['description_target_group'])
                        ),
                        "$activityIndex.funding_participating_organization" => sprintf(
                            'required_any:%s.funding_participating_organization,%s,%s.implementing_participating_organization,%s',
                            $activityIndex,
                            trimInput($activityRow['funding_participating_organization']),
                            $activityIndex,
                            trimInput($activityRow['implementing_participating_organization'])
                        ),
                        "$activityIndex.activity_status"                    => 'required|in:' . $activityStatus,
                        "$activityIndex.sector_dac_5digit"                  => 'required|multiple_value_in:' . $sectorCategory,
                        "$activityIndex.recipient_country"                  => 'required_without:' . $activityIndex . '.recipient_region|multiple_value_in:' . $recipientCountryCodes,
                        "$activityIndex.recipient_region"                   => 'required_without:' . $activityIndex . '.recipient_country|multiple_value_in:' . $recipientRegionCodes
                    ]
                );

                $messages = array_merge(
                    $messages,
                    [
                        "$activityIndex.sector_dac_5digit.multiple_value_in"   => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.sector_5_digit') . '-' . trans('elementForm.category_code')]
                        ),
                        "$activityIndex.sector_dac_5digit.required"            => trans(
                            'validation.csv_required',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.sector_5_digit') . '-' . trans('elementForm.category_code')]
                        ),
                        "$activityIndex.recipient_country.multiple_value_in"   => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.recipient_country')]
                        ),
                        "$activityIndex.recipient_country.required_without"    => trans(
                            'validation.csv_required',
                            [
                                'number'    => $activityIndex + 1,
                                'attribute' => trans('global.either') . ' ' . trans('elementForm.recipient_country') . ' ' . trans('global.or') . ' ' . trans('elementForm.recipientRegion')
                            ]
                        ),
                        "$activityIndex.recipient_region.multiple_value_in"    => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.recipient_region')]
                        ),
                        "$activityIndex.recipient_region.required_without"     => trans(
                            'validation.csv_required',
                            [
                                'number'    => $activityIndex + 1,
                                'attribute' => trans('global.either') . ' ' . trans('elementForm.recipient_country') . ' ' . trans('global.or') . ' ' . trans('elementForm.recipientRegion')
                            ]
                        ),
                        "$activityIndex.activity_status.in"                    => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('element.activity_status')]
                        ),
                        "$activityIndex.activity_status.required"              => trans(
                            'validation.csv_required',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.activity_status')]
                        ),
                        "$activityIndex.activity_identifier.required"          => trans(
                            'validation.csv_required',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.activity_identifier')]
                        ),
                        "$activityIndex.activity_identifier.not_in"            => trans(
                            'validation.csv_unique',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.activity_identifier')]
                        ),
                        "$activityIndex.activity_identifier.unique_validation" => trans(
                            'validation.csv_unique_validation',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.activity_identifier')]
                        ),
                        "$activityIndex.activity_title.required"               => trans(
                            'validation.csv_required',
                            ['number' => $activityIndex + 1, 'attribute' => trans('element.title')]
                        ),
                        "$activityIndex.actual_start_date.date"                => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.actual_start_date')]
                        ),
                        "$activityIndex.actual_end_date.date"                  => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.actual_end_date')]
                        ),
                        "$activityIndex.planned_start_date.date"               => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.planned_start_date')]
                        ),
                        "$activityIndex.planned_end_date.date"                 => trans(
                            'validation.csv_invalid',
                            ['number' => $activityIndex + 1, 'attribute' => trans('elementForm.planned_end_date')]
                        ),
                        "$activityIndex.actual_start_date.required_any"        => trans(
                            'validation.csv_required',
                            [
                                'number'    => $activityIndex + 1,
                                'attribute' => trans('global.among') . ' ' . trans('elementForm.actual_start_date') . '/' . trans('elementForm.actual_end_date') . '/' . trans(
                                        'elementForm.planned_start_date'
                                    ) . '/' . trans(
                                        'elementForm.planned_end_date'
                                    )
                            ]
                        ),

                        "$activityIndex.description_general.required_any"                => trans(
                            'validation.csv_among',
                            [
                                'number'    => $activityIndex + 1,
                                'type'      => trans('elementForm.type'),
                                'attribute' => trans('elementForm.description_general') . '/' . trans('elementForm.description_objectives') . '/' . trans('elementForm.description_target_group')
                            ]
                        ),
                        "$activityIndex.funding_participating_organization.required_any" => trans(
                            'validation.csv_among',
                            [
                                'number'    => $activityIndex + 1,
                                'type'      => trans('element.participating_organisation'),
                                'attribute' => trans('elementForm.funding') . '/' . trans('elementForm.implementing')
                            ]
                        )
                    ]
                );

            }

            return Validator::make($activities, $rules, $messages);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Undefined index:') == 0) {
                return false;
            }
        }
    }

    public function getSimpleCsvValidator($file)
    {
        $transactions      = Excel::load($file)->get()->toArray();
        $transactionHeader = Excel::load($file)->get()->first()->keys()->toArray();
        $templateHeader    = Excel::load(app_path('Core/V201/Template/Csv/iati_transaction_template_simple.csv'))->get()->first()->keys()->toArray();

        if (count(array_intersect($transactionHeader, $templateHeader)) !== count($templateHeader)) {
            return null;
        }

        $transactionCurrency = implode(',', $this->getCodes('Currency', 'Organization'));

        $rules    = [];
        $messages = [];

        foreach ($transactions as $transactionIndex => $transactionRow) {
            $requiredOnlyOneRule = sprintf(
                'required_only_one:%s.incoming_fund,%s,%s.expenditure,%s,%s.commitment,%s,%s.disbursement,%s',
                $transactionIndex,
                trimInput($transactionRow['incoming_fund']),
                $transactionIndex,
                trimInput($transactionRow['expenditure']),
                $transactionIndex,
                trimInput($transactionRow['commitment']),
                $transactionIndex,
                trimInput($transactionRow['disbursement'])
            );

            $rules = array_merge(
                $rules,
                [
                    "$transactionIndex.internal_reference"   => sprintf(
                        'unique_validation:%s.internal_reference,%s,%s,internal_reference',
                        $transactionIndex,
                        trimInput($transactionRow['internal_reference']),
                        $file
                    ),
                    "$transactionIndex.incoming_fund"        => (trimInput($transactionRow['incoming_fund'])) ? ($requiredOnlyOneRule . '|numeric') : $requiredOnlyOneRule,
                    "$transactionIndex.expenditure"          => (trimInput($transactionRow['expenditure'])) ? 'numeric' : '',
                    "$transactionIndex.disbursement"         => (trimInput($transactionRow['disbursement'])) ? 'numeric' : '',
                    "$transactionIndex.commitment"           => (trimInput($transactionRow['commitment'])) ? 'numeric' : '',
                    "$transactionIndex.transaction_date"     => 'required|date',
                    "$transactionIndex.transaction_currency" => 'in:' . $transactionCurrency,
                    "$transactionIndex.description"          => 'required'
                ]
            );

            $messages = array_merge(
                $messages,
                [
                    "$transactionIndex.internal_reference.required"          => trans(
                        'validation.csv_required',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.internal_reference')
                        ]
                    ),
                    "$transactionIndex.internal_reference.unique_validation" => trans(
                        'validation.csv_unique',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.internal_reference')
                        ]
                    ),
                    "$transactionIndex.incoming_fund.numeric"                => trans(
                        'validation.csv_numeric',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.incoming_fund')
                        ]
                    ),
                    "$transactionIndex.incoming_fund.required_only_one"      => trans(
                        'validation.csv_only_one',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.incoming_fund') . ', ' . trans('elementForm.expenditure') . ', ' . trans('elementForm.disbursement') . ', ' . trans(
                                    'elementForm.commitment'
                                )
                        ]
                    ),
                    "$transactionIndex.expenditure.numeric"                  => trans(
                        'validation.csv_numeric',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.expenditure')
                        ]
                    ),
                    "$transactionIndex.disbursement.numeric"                 => trans(
                        'validation.csv_numeric',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.disbursement')
                        ]
                    ),
                    "$transactionIndex.commitment.numeric"                   => trans(
                        'validation.csv_numeric',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.commitment')
                        ]
                    ),
                    "$transactionIndex.transaction_date.required"            => trans(
                        'validation.csv_required',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.transaction_date')
                        ]
                    ),
                    "$transactionIndex.transaction_date.date"                => trans(
                        'validation.csv_invalid',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.transaction_date')
                        ]
                    ),
                    "$transactionIndex.transaction_currency.in"              => trans(
                        'validation.csv_invalid',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('elementForm.transaction_currency')
                        ]
                    ),
                    "$transactionIndex.description.required"                 => trans(
                        'validation.csv_required',
                        [
                            'number'    => $transactionIndex + 1,
                            'attribute' => trans('element.description')
                        ]
                    ),
                ]
            );
        }

        return Validator::make($transactions, $rules, $messages);
    }
}
