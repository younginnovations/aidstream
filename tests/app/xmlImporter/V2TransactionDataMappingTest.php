<?php

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements\Transaction;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlErrorServiceProvider;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;
use Sabre\Xml\Service;
use Test\AidStreamTestCase;

class V2TransactionDataMappingTest extends AidStreamTestCase
{

    protected $transactionService;
    protected $service;
    protected $xmlServiceProvider;
    protected $template;
    protected $data;
    protected $xml;
    protected $transaction = [];

    public function setUp()
    {
        parent::setUp();
        $this->transactionService = new Transaction();
        $this->service            = new Service();
        $this->xmlServiceProvider = new XmlServiceProvider($this->service, new XmlErrorServiceProvider());
        $xmlData                  = file_get_contents('tests/app/xmlImporter/xml/V202.xml');
        $this->data               = $this->xmlServiceProvider->load($xmlData);
        $this->template           = $template = json_decode(file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json')), true);
    }

    public function testTransactionMapping()
    {
        $this->filterForTransactions($this->data, 'transaction');
        $expectedTransaction = [
            0 => [
                'reference'             => 'Ref1234',
                'humanitarian'          => '1',
                'transaction_type'      => [
                    0 => [
                        'transaction_type_code' => '4'
                    ]
                ],
                'transaction_date'      => [
                    0 => [
                        'date' => '2016-11-17'
                    ]
                ],
                'value'                 => [
                    0 => [
                        'amount'   => '100',
                        'date'     => '2016-11-04',
                        'currency' => 'BGN'
                    ]
                ],
                'description'           => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'desc 1',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'desc 2',
                                'language'  => 'ae'
                            ]
                        ]
                    ]
                ],
                'provider_organization' => [
                    0 => [
                        'organization_identifier_code' => 'pro  identifier  code',
                        'provider_activity_id'         => 'activity  id',
                        'type'                         => '22',
                        'narrative'                    => [
                            0 => [
                                'narrative' => 'provider org I',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'provider org II',
                                'language'  => 'ab'
                            ]
                        ]
                    ]
                ],
                'receiver_organization' => [
                    0 => [
                        'organization_identifier_code' => 'receiver  org  code',
                        'receiver_activity_id'         => 'activity  id',
                        'type'                         => '22',
                        'narrative'                    => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'disbursement_channel'  => [
                    0 => [
                        'disbursement_channel_code' => '2'
                    ]
                ],
                'sector'                => [
                    0 => [
                        'sector_vocabulary'    => '3',
                        'vocabulary_uri'       => 'http://www.google.com',
                        'sector_code'          => '',
                        'sector_category_code' => '',
                        'sector_text'          => '11110',
                        'narrative'            => [
                            0 => [
                                'narrative' => 'Sector text 1',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'sector text 2',
                                'language'  => 'bo'
                            ]
                        ]
                    ]
                ],
                'recipient_country'     => [
                    0 => [
                        'country_code' => '',
                        'narrative'    => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'recipient_region'      => [
                    0 => [
                        'region_code'    => '',
                        'vocabulary'     => '',
                        'vocabulary_uri' => '',
                        'narrative'      => [
                            0 => [
                                'narrative' => '',
                                'language'  => ''
                            ]
                        ]
                    ]
                ],
                'flow_type'             => [
                    0 => [
                        'flow_type' => ''
                    ]
                ],
                'finance_type'          => [
                    0 => [
                        'finance_type' => ''
                    ]
                ],
                'aid_type'              => [
                    0 => [
                        'aid_type' => ''
                    ]
                ],
                'tied_status'           => [
                    0 => [
                        'tied_status_code' => ''
                    ]
                ]

            ]
        ];

        $this->assertEquals($expectedTransaction, $this->transactionService->map($this->transaction, $this->template));
    }

    /**
     * Filter data for Transactions Elements.
     *
     * @param $activities
     * @param $elementName
     */
    protected function filterForTransactions($activities, $elementName)
    {
        foreach ($activities as $elements) {
            foreach ($this->value($elements) as $element) {
                if ($this->name($element) == $elementName) {
                    $this->transaction[] = $element;
                }
            }
        }
    }

    /**
     * @param      $element
     * @param bool $snakeCase
     * @return string
     */
    protected function name($element, $snakeCase = false)
    {
        if (is_array($element)) {
            $camelCaseString = camel_case(str_replace('{}', '', $element['name']));

            return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
        }

        $camelCaseString = camel_case(str_replace('{}', '', $element));

        return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
    }

    /**
     * Get the value from the array.
     * If key is provided then the value is fetched from the value field of the data.
     * If key is provided then the $fields = $data['value'] else $fields = $data.
     * If the value is array then narrative is returned else only the value is returned.
     * @param array $fields
     * @param null  $key
     * @return array|mixed|string
     */
    public function value(array $fields, $key = null)
    {
        if (!$key) {
            return getVal($fields, ['value'], '');
        }
        foreach ($fields as $field) {
            if ($this->name($field['name']) == $key) {
                if (is_array($field['value'])) {
                    return $this->narrative($field);
                }

                return getVal($field, ['value'], '');
            }
        }

        return [['narrative' => '', 'language' => '']];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
