<?php

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements\Result;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlErrorServiceProvider;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;
use Sabre\Xml\Service;
use Test\AidStreamTestCase;

class V2ResultDataMappingTest extends AidStreamTestCase
{
    protected $xmlServiceProvider;
    protected $service;
    protected $data;
    protected $template;
    protected $resultMapper;
    protected $results;

    public function setUp()
    {
        parent::setUp();
        $this->service            = new Service();
        $this->xmlServiceProvider = new XmlServiceProvider($this->service, new XmlErrorServiceProvider());
        $xmlData                  = file_get_contents('tests/app/xmlImporter/xml/V202.xml');
        $this->data               = $this->xmlServiceProvider->load($xmlData);
        $this->template           = $template = json_decode(file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json')), true);
        $this->resultMapper       = new Result();
        $this->filterForResults($this->data, 'result');
    }

    public function testResultMapping()
    {
        $expectedResults = $this->getExpectedResults();

        $this->assertEquals($expectedResults, $this->resultMapper->map($this->results, $this->template));
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
     * Filter data for Transactions Elements.
     *
     * @param $activities
     * @param $elementName
     */
    protected function filterForResults($activities, $elementName)
    {
        foreach ($activities as $elements) {
            foreach ($this->value($elements) as $element) {
                if ($this->name($element) == $elementName) {
                    $this->results[] = $element;
                }
            }
        }
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

    protected function getExpectedResults()
    {
        return [
            0 => [
                'type'               => '1',
                'aggregation_status' => '1',
                'title'              => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Result title',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Result title 2',
                                'language'  => 'af'
                            ]
                        ]
                    ]
                ],
                'description'        => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Result Description',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Result Description',
                                'language'  => 'ay'
                            ]
                        ]
                    ]
                ],
                'indicator'          => [
                    0 => [
                        'measure'     => '1',
                        'ascending'   => '1',
                        'title'       => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'indicator title',
                                        'language'  => ''
                                    ],
                                    1 => [
                                        'narrative' => 'indicator title 2',
                                        'language'  => 'cs'
                                    ]
                                ]
                            ]
                        ],
                        'description' => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'indicator description i',
                                        'language'  => ''
                                    ],
                                    1 => [
                                        'narrative' => 'indicator description II',
                                        'language'  => 'ae'
                                    ]
                                ]
                            ]
                        ],
                        'reference'   => [
                            0 => [
                                'vocabulary'    => '2',
                                'code'          => '',
                                'indicator_uri' => 'http://www.google.com'
                            ],
                            1 => [
                                'vocabulary'    => '4',
                                'code'          => '123123',
                                'indicator_uri' => 'http://www.google.com'
                            ]
                        ],
                        'baseline'    => [
                            0 => [
                                'year'    => '2010',
                                'value'   => '1123',
                                'comment' => [
                                    0 => [
                                        'narrative' => [
                                            0 => [
                                                'narrative' => 'Indicator baseline narrative',
                                                'language'  => ''
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'period'      => [
                            0 => [
                                'period_start' => [
                                    0 => [
                                        'date' => '2016-11-09'
                                    ]
                                ],
                                'period_end'   => [
                                    0 => [
                                        'date' => '2017-02-28'
                                    ]
                                ],
                                'target'       => [
                                    0 => [
                                        'value'     => '123',
                                        'location'  => [
                                            0 => [
                                                'ref' => 'Target  Reg'
                                            ],
                                            1 => [
                                                'ref' => 'ref  324234'
                                            ]
                                        ],
                                        'dimension' => [
                                            0 => [
                                                'name'  => 'dimension  i',
                                                'value' => '123'
                                            ],
                                            1 => [
                                                'name'  => 'asdasd',
                                                'value' => 'asdasd'
                                            ]
                                        ],
                                        'comment'   => [
                                            0 => [
                                                'narrative' => [
                                                    0 => [
                                                        'narrative' => 'Baseline comment',
                                                        'language'  => ''
                                                    ],
                                                    1 => [
                                                        'narrative' => 'Baseline comment II',
                                                        'language'  => 'eu'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'actual'       => [
                                    0 => [
                                        'value'     => '2010',
                                        'location'  => [
                                            0 => [
                                                'ref' => 'ref  12334'
                                            ]
                                        ],
                                        'dimension' => [
                                            0 => [
                                                'name'  => '1123',
                                                'value' => '213123'
                                            ]
                                        ],
                                        'comment'   => [
                                            0 => [
                                                'narrative' => [
                                                    0 => [
                                                        'narrative' => 'asdasdasdasd',
                                                        'language'  => ''
                                                    ],
                                                    1 => [
                                                        'narrative' => 'asdasdasdasdasd',
                                                        'language'  => 'af'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
