<?php namespace App\Services\Download;

class SimpleCsvDataFormatter
{

    protected $headers;

    public function __construct()
    {
        $this->headers = [
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
    }

    public function prepareCsvData($activities)
    {
        $CsvData = ['headers' => $this->headers];
        foreach ($activities as $activity) {
            $CsvData[] = [
                'iati-identifier'       => $activity->identifier['iati_identifier_text'],
                'title'                 => $this->formatTitle($activity->title),
                'description_general'   => $this->formatDescription('general'),
                'description_objective' => $this->formatDescription('objective'),
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
        }
    }
}
