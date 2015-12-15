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
     * @return mixed
     */
    public function isValidCsv($file)
    {
        $transactions                   = Excel::load($file)->get()->toArray();
        $transactionTypeCodes           = implode(',', $this->getCodes('TransactionType', 'Activity'));
        $disbursementChannelCodes       = implode(',', $this->getCodes('DisbursementChannel', 'Activity'));
        $sectorVocabularyCodes          = implode(',', $this->getCodes('SectorVocabulary', 'Activity'));
        $sectorCodes                    = implode(',', $this->getCodes('Sector', 'Activity'));
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
                    "$transactionIndex.transaction_ref"             => 'required',
                    "$transactionIndex.transactiontype_code"        => 'required|in:' . $transactionTypeCodes,
                    "$transactionIndex.transactiondate_iso_date"    => 'required|date',
                    "$transactionIndex.transactionvalue_value_date" => 'required|date',
                    "$transactionIndex.transactionvalue_text"       => 'required|numeric',
                    "$transactionIndex.description_text"            => 'required',
                    "$transactionIndex.disbursementchannel_code"    => 'in:' . $disbursementChannelCodes,
                    "$transactionIndex.sector_vocabulary"           => 'in:' . $sectorVocabularyCodes,
                    "$transactionIndex.sector_code"                 => 'in:' . $sectorCodes,
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
                    "$transactionIndex.transaction_ref.required"             => sprintf('At row %s Transaction-ref is required', $transactionIndex + 1),
                    "$transactionIndex.transactiontype_code.required"        => sprintf('At row %s TransactionType-code is required', $transactionIndex + 1),
                    "$transactionIndex.transactiontype_code.in"              => sprintf('At row %s TransactionType-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.transactiondate_iso_date.required"    => sprintf('At row %s TransactionDate-iso_date is required', $transactionIndex + 1),
                    "$transactionIndex.transactiondate_iso_date.date"        => sprintf('At row %s TransactionDate-iso_date is invalid', $transactionIndex + 1),
                    "$transactionIndex.transactionvalue_value_date.required" => sprintf('At row %s TransactionValue-value_date is required', $transactionIndex + 1),
                    "$transactionIndex.transactionvalue_value_date.date"     => sprintf('At row %s TransactionValue-value_date is invalid', $transactionIndex + 1),
                    "$transactionIndex.transactionvalue_text.required"       => sprintf('At row %s TransactionValue-text is required', $transactionIndex + 1),
                    "$transactionIndex.transactionvalue_text.numeric"        => sprintf('At row %s TransactionValue-text should ne numeric', $transactionIndex + 1),
                    "$transactionIndex.description_text.required"            => sprintf('At row %s Description-text is required', $transactionIndex + 1),
                    "$transactionIndex.disbursementchannel_code.in"          => sprintf('At row %s DisbursementChannel-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.sector_vocabulary.in"                 => sprintf('At row %s Sector-vocabularye is invalid', $transactionIndex + 1),
                    "$transactionIndex.sector_code.in"                       => sprintf('At row %s Sector-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.recipientcountry_code.in"             => sprintf('At row %s RecipientCountry-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.recipientregion_code.in"              => sprintf('At row %s RecipientRegion-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.recipientregion_vocabulary.in"        => sprintf('At row %s RecipientRegion-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.flowtype_code.in"                     => sprintf('At row %s FlowType-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.financetype_code.in"                  => sprintf('At row %s FinanceType-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.aidtype_code.in"                      => sprintf('At row %s AidType-code is invalid', $transactionIndex + 1),
                    "$transactionIndex.tiedstatus_code.in"                   => sprintf('At row %s TiedStatus-code is invalid', $transactionIndex + 1),
                ]
            );

        }

        return Validator::make($transactions, $rules, $messages);
    }

    /**
     * Check if the activities in csv are valid or not
     * @param $file
     * @return mixed
     */
    public function isValidActivityCsv($file)
    {
        $activities            = Excel::load($file)->get()->toArray();
        $activityStatus        = implode(',', $this->getCodes('ActivityStatus', 'Activity'));
        $sectorCategory        = implode(',', $this->getCodes('SectorCategory', 'Activity'));
        $recipientCountryCodes = implode(',', $this->getCodes('Country', 'Organization'));
        $recipientRegionCodes  = implode(',', $this->getCodes('Region', 'Activity'));

        $rules    = [];
        $messages = [];
        foreach ($activities as $activityIndex => $activityRow) {
            $rules = array_merge(
                $rules,
                [
                    "$activityIndex.activity_identifier"                => 'required',
                    "$activityIndex.activity_title"                     => 'required',
                    "$activityIndex.actual_start_date"                  => sprintf(
                        'date|required_any:%s.actual_start_date,$s,%s.actual_end_date,$s,%s.planned_start_date,%s,%s.planned_end_date',
                        $activityIndex,
                        $activityRow['actual_start_date'],
                        $activityIndex,
                        $activityRow['actual_end_date'],
                        $activityIndex,
                        $activityRow['planned_start_date'],
                        $activityIndex,
                        $activityRow['planned_end_date']
                    ),
                    "$activityIndex.actual_end_date"                    => 'date',
                    "$activityIndex.planned_start_date"                 => 'date',
                    "$activityIndex.planned_end_date"                   => 'date',
                    "$activityIndex.description_general"                => sprintf(
                        'required_any:%s.description_general,%s,%s.description_objectives,%s,%s.description_target_group,%s',
                        $activityIndex,
                        $activityRow['description_general'],
                        $activityIndex,
                        $activityRow['description_objectives'],
                        $activityIndex,
                        $activityRow['description_target_group']
                    ),
                    "$activityIndex.funding_participating_organization" => sprintf(
                        'required_any:%s.funding_participating_organization,%s,%s.implementing_participating_organization,%s',
                        $activityIndex,
                        $activityRow['funding_participating_organization'],
                        $activityIndex,
                        $activityRow['implementing_participating_organization']
                    ),
                    "$activityIndex.activity_status"                    => 'required|in:' . $activityStatus,
                    "$activityIndex.sector_dac_3digit"                  => 'required|multiple_value_in:' . $sectorCategory,
                    "$activityIndex.recipient_country"                  => 'required|multiple_value_in:' . $recipientCountryCodes,
                    "$activityIndex.recipient_region"                   => 'required|multiple_value_in:' . $recipientRegionCodes
                ]
            );

            $messages = array_merge(
                $messages,
                [
                    "$activityIndex.sector_dac_3digit.multiple_value_in"             => sprintf('At row %s Sector_DAC_3Digit category code is invalid', $activityIndex + 1),
                    "$activityIndex.recipient_country.multiple_value_in"             => sprintf('At row %s Recipient_Country is invalid', $activityIndex + 1),
                    "$activityIndex.recipient_region.multiple_value_in"              => sprintf('At row %s Recipient_Region is invalid', $activityIndex + 1),
                    "$activityIndex.activity_status.in"                              => sprintf('At row %s Activity_Status is invalid', $activityIndex + 1),
                    "$activityIndex.activity_identifier.required"                    => sprintf('At row %s Activity_Identifier is required', $activityIndex + 1),
                    "$activityIndex.activity_title.required"                         => sprintf('At row %s Activity_Title is required', $activityIndex + 1),
                    "$activityIndex.actual_start_date.date"                          => sprintf('At row %s Actual_start_date is required', $activityIndex + 1),
                    "$activityIndex.actual_end_date.date"                            => sprintf('At row %s Actual_end_date is required', $activityIndex + 1),
                    "$activityIndex.planned_start_date.date"                         => sprintf('At row %s Planned_start_date is required', $activityIndex + 1),
                    "$activityIndex.planned_end_date.date"                           => sprintf('At row %s Planned_end_date is required', $activityIndex + 1),
                    "$activityIndex.actual_start_date.required_any"                  => sprintf(
                        'At row %s date among Actual_start_date/Actual_end_date/Planned_start_date/Planned_end_date is required',
                        $activityIndex + 1
                    ),
                    "$activityIndex.description_general.required_any"                => sprintf(
                        'At row %s at least one description among description_general/description_objectives/description_target_group is required',
                        $activityIndex + 1
                    ),
                    "$activityIndex.funding_participating_organization.required_any" => sprintf(
                        'At row %s at least one participating_organization among funding/implementing is required',
                        $activityIndex + 1
                    ),
                ]
            );

        }

        return Validator::make($activities, $rules, $messages);
    }
}
