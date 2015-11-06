<?php namespace App\Core\V201;

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
}
