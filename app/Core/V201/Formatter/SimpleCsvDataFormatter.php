<?php namespace App\Core\V201\Formatter;

use App\Helpers\GetCodeName;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SimpleCsvDataFormatter
 * @package App\Core\V201\Formatter
 */
class SimpleCsvDataFormatter
{
    protected $headers;
    /**
     * @var GetCodeName
     */
    protected $codeNameHelper;
    protected $csvData = [];

    /**
     * SimpleCsvDataFormatter Constructor
     * @param GetCodeName $codeNameHelper
     */
    public function __construct(GetCodeName $codeNameHelper)
    {
        $this->headers        = [
            'iati-identifier',
            'title',
            'description_general',
            'description_objective',
            'description_target',
            'description_others',
            'activity-status',
            'start-planned',
            'start-actual',
            'start_actual',
            'end-actual',
            'funding_organisations',
            'extending_organisations',
            'accountable_organisations',
            'implementing_organisations',
            'recipient-country',
            'recipient-country-codes',
            'recipient-country-percentages',
            'recipient-region',
            'recipient-region-codes',
            'recipient-region-percentages',
            'sector-text',
            'sector-vocabularies',
            'sector-codes',
            'sector-percentages',
            'total-commitments',
            'total-disbursements',
            'total-expenditure',
            'total-incoming-funds'
        ];
        $this->codeNameHelper = $codeNameHelper;
    }

    /**
     * Format data for simple csv
     * @param Collection $activities
     * @return array
     */
    public function format(Collection $activities)
    {
        if ($activities->isEmpty()) {
            return false;
        }

        $this->csvData = ['headers' => $this->headers];

        foreach ($activities as $activity) {
            $this->csvData[] = [
                'iati-identifier'               => $activity->identifier['iati_identifier_text'],
                'title'                         => !($activity->title) ? '' : $this->formatTitle($activity->title),
                'description_general'           => !($activity->description) ? '' : $this->formatDescription($activity->description, '1'),
                'description_objective'         => !($activity->description) ? '' : $this->formatDescription($activity->description, '2'),
                'description_target'            => !($activity->description) ? '' : $this->formatDescription($activity->description, '3'),
                'description_others'            => !($activity->description) ? '' : $this->formatDescription($activity->description, '4'),
                'activity-status'               => (int) $activity->activity_status,
                'start-planned'                 => !($activity->activity_date) ? '' : $this->formatActivityDate($activity->activity_date, '1'),
                'start-actual'                  => !($activity->activity_date) ? '' : $this->formatActivityDate($activity->activity_date, '2'),
                'end-planned'                   => !($activity->activity_date) ? '' : $this->formatActivityDate($activity->activity_date, '3'),
                'end-actual'                    => !($activity->activity_date) ? '' : $this->formatActivityDate($activity->activity_date, '4'),
                'funding_organisations'         => !($activity->participating_organization) ? '' : $this->formatParticipatingOrg($activity->participating_organization, '1'),
                'accountable_organisations'     => !($activity->participating_organization) ? '' : $this->formatParticipatingOrg($activity->participating_organization, '2'),
                'extending_organisations'       => !($activity->participating_organization) ? '' : $this->formatParticipatingOrg($activity->participating_organization, '3'),
                'implementing_organisations'    => !($activity->participating_organization) ? '' : $this->formatParticipatingOrg($activity->participating_organization, '4'),
                'recipient-country'             => !($activity->recipient_country) ? '' : $this->formatRecipientCountry($activity->recipient_country),
                'recipient-country-codes'       => !($activity->recipient_country) ? '' : $this->formatRecipientCountryCodes($activity->recipient_country),
                'recipient-country-percentages' => !($activity->recipient_country) ? '' : $this->formatRecipientCountryPercentages($activity->recipient_country),
                'recipient-region'              => !($activity->recipient_region) ? '' : $this->formatRecipientRegions($activity->recipient_region),
                'recipient-region-codes'        => !($activity->recipient_region) ? '' : $this->formatRecipientRegionCodes($activity->recipient_region),
                'recipient-region-percentages'  => !($activity->recipient_region) ? '' : $this->formatRecipientRegionPercentages($activity->recipient_region),
                'sector-text'                   => !($activity->sector) ? '' : $this->formatSectorText($activity->sector),
                'sector-vocabularies'           => !($activity->sector) ? '' : $this->formatSectorVocabularies($activity->sector),
                'sector-codes'                  => !($activity->sector) ? '' : $this->formatSectorCodes($activity->sector),
                'sector-percentages'            => !($activity->sector) ? '' : $this->formatSectorPercentages($activity->sector),
                'total-incoming-funds'          => (!$activity->transactions) ? '' : $this->formatTransactionValues($activity->transactions, 1),
                'total-commitments'             => (!$activity->transactions) ? '' : $this->formatTransactionValues($activity->transactions, 2),
                'total-disbursements'           => (!$activity->transactions) ? '' : $this->formatTransactionValues($activity->transactions, 3),
                'total-expenditure'             => (!$activity->transactions) ? '' : $this->formatTransactionValues($activity->transactions, 4)
            ];
        }

        return $this->csvData;
    }

    /**
     * Format title
     * @param $titles
     * @return string
     */
    protected function formatTitle($titles)
    {
        $titleNarratives = [];
        foreach ($titles as $title) {
            $titleNarratives[] = $title['narrative'];
        }

        return implode(';', $titleNarratives);
    }

    /**
     * Format description
     * @param $descriptions
     * @param $type
     * @return string
     */
    protected function formatDescription($descriptions, $type)
    {
        $narratives = [];
        foreach ($descriptions as $description) {
            ($description['type'] != $type) ?: $narratives = $this->formatNarrative($description);
        }

        return implode(';', $narratives);
    }

    /**
     * Format Narrative
     * @param $narrativeDatas
     * @return array
     */
    protected function formatNarrative($narrativeDatas)
    {
        $narratives = [];
        foreach ($narrativeDatas['narrative'] as $narrative) {
            $narratives[] = $narrative['narrative'];
        }

        return $narratives;
    }

    /**
     * Format Activity dates
     * @param $activityDates
     * @param $type
     * @return string
     */
    protected function formatActivityDate($activityDates, $type)
    {
        $dates = [];
        foreach ($activityDates as $activityDate) {
            ($activityDate['type'] != $type) ?: $dates[] = $activityDate['date'];
        }

        return implode(';', $dates);
    }

    /**
     * Format Participating Org
     * @param $participatingOrgs
     * @param $type
     * @return string
     */
    protected function formatParticipatingOrg($participatingOrgs, $type)
    {
        $narratives = [];
        foreach ($participatingOrgs as $org) {
            ($org['organization_role'] != $type) ?: $narratives = $this->formatNarrative($org);
        }

        return implode(';', $narratives);
    }

    /**
     * Format recipient Country
     * @param $recipientCountries
     * @return string
     */
    protected function formatRecipientCountry($recipientCountries)
    {
        $countries = [];
        foreach ($recipientCountries as $country) {
            $countries[] = $this->codeNameHelper->getOrganizationCodeName('Country', $country['country_code']);
        }

        return implode(';', $countries);
    }

    /**
     * Format Recipient Country Codes
     * @param $recipientCountries
     * @return string
     */
    protected function formatRecipientCountryCodes($recipientCountries)
    {
        $countryCodes = [];
        foreach ($recipientCountries as $country) {
            $countryCodes[] = $country['country_code'];
        }

        return implode(';', $countryCodes);
    }

    /**
     * Format Recipient Country Percentage
     * @param $recipientCountries
     * @return string
     */
    protected function formatRecipientCountryPercentages($recipientCountries)
    {
        $percentages = [];
        foreach ($recipientCountries as $country) {
            $percentages[] = $country['percentage'];
        }

        return implode(';', $percentages);
    }

    /**
     * Format recipient Region
     * @param $RecipientRegions
     * @return string
     */
    protected function formatRecipientRegions($RecipientRegions)
    {
        $regions = [];
        foreach ($RecipientRegions as $region) {
            $regions[] = $this->codeNameHelper->getActivityCodeName('Region', $region['region_code']);
        }

        return implode(';', $regions);
    }

    /**
     * Format Recipient Region Codes
     * @param $RecipientRegions
     * @return string
     */
    protected function formatRecipientRegionCodes($RecipientRegions)
    {
        $regionCodes = [];
        foreach ($RecipientRegions as $region) {
            $regionCodes[] = $region['region_code'];
        }

        return implode(';', $regionCodes);
    }

    /**
     * Format Recipient Region Percentages
     * @param $RecipientRegions
     * @return string
     */
    protected function formatRecipientRegionPercentages($RecipientRegions)
    {
        $regionPercentages = [];
        foreach ($RecipientRegions as $region) {
            $regionPercentages[] = $region['percentage'];
        }

        return implode(';', $regionPercentages);
    }

    /**
     * Format Sector Text
     * @param $Sectors
     * @return string
     */
    protected function formatSectorText($Sectors)
    {
        $sectorTexts = [];
        foreach ($Sectors as $sector) {
            $sectorTexts[] = $sector['sector_text'];
        }

        $texts = [];
        foreach ($sectorTexts as $sectorText) {
            $texts[] = boolval($sectorText) ? sprintf('%s;', $sectorText) : '';
        }

        return implode(null, $texts);
    }

    /**
     * Format Sector Vocabularies
     * @param $Sectors
     * @return string
     */
    protected function formatSectorVocabularies($Sectors)
    {
        $SectorVocabularies = [];
        foreach ($Sectors as $sector) {
            $SectorVocabularies[] = $sector['sector_vocabulary'];
        }

        return implode(';', $SectorVocabularies);
    }

    /**
     * Format Sector Codes
     * @param $sectors
     * @return string
     */
    protected function formatSectorCodes($sectors)
    {
        $sectorCodes = [];
        foreach ($sectors as $sector) {
            $sectorCodes[] = $sector['sector_code'];
        }

        $codes = [];
        foreach ($sectorCodes as $sectorCode) {
            $codes[] = boolval($sectorCode) ? sprintf('%s;', $sectorCode) : '';
        }

        return implode(null, $codes);
    }

    /**
     * Format Sector Percentage
     * @param $Sectors
     * @return string
     */
    protected function formatSectorPercentages($Sectors)
    {
        $SectorPercentages = [];
        foreach ($Sectors as $sector) {
            $SectorPercentages[] = $sector['percentage'];
        }

        return implode(';', $SectorPercentages);
    }

    /**
     * Format transaction values
     * @param $transactions
     * @param $type
     * @return string
     */
    protected function formatTransactionValues($transactions, $type)
    {
        $value = 0;
        foreach ($transactions as $transaction) {
            $transaction->transaction['transaction_type'][0]['transaction_type_code'] != $type ?: $value += $transaction->transaction['value'][0]['amount'];
        }

        return $value;
    }
}
