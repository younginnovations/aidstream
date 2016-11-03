<?php

use App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1\Elements\Result;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlErrorServiceProvider;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;
use Sabre\Xml\Service;
use Test\AidStreamTestCase;

class V1ResultDataMappingTest extends AidStreamTestCase
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
        $xmlData                  = file_get_contents('tests/app/xmlImporter/xml/V103.xml');
        $this->data               = $this->xmlServiceProvider->load($xmlData);
        $this->template           = $template = json_decode(file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json')), true);
        $this->resultMapper       = new Result();
        $this->filterForResults($this->data, 'result');
    }

    public function testResultMapping()
    {
        $expectedResults = [
            0 => [
                'type'               => '1',
                'aggregation_status' => '0',
                'title'              => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Grant Performance Report',
                                'language'  => ''
                            ],
                            1 => [
                                'narrative' => 'Grant Informe sobre los resultados',
                                'language'  => 'es'
                            ],
                            2 => [
                                'narrative' => 'Rapport sur le rendement de subvention',
                                'language'  => 'fr'
                            ]
                        ]
                    ]
                ],
                'description'        => [
                    0 => [
                        'narrative' => [
                            0 => [
                                'narrative' => 'Description text',
                                'language'  => 'en'
                            ],
                            1 => [
                                'narrative' => 'Description text',
                                'language'  => 'en'
                            ]
                        ]
                    ]
                ],
                'indicator'          => [
                    0 => [
                        'measure'     => '',
                        'ascending'   => '0',
                        'title'       => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'Grant Performance Report',
                                        'language'  => ''
                                    ],
                                    1 => [
                                        'narrative' => 'Grant Informe sobre los resultados',
                                        'language'  => 'es'
                                    ],
                                    2 => [
                                        'narrative' => 'Rapport sur le rendement de subvention',
                                        'language'  => 'fr'
                                    ]
                                ]
                            ]
                        ],
                        'description' => [
                            0 => [
                                'narrative' => [
                                    0 => [
                                        'narrative' => 'Description text',
                                        'language'  => 'en'
                                    ]
                                ]
                            ]
                        ],
                        'reference'   => [
                            0 => [
                                'vocabulary'    => '',
                                'code'          => '',
                                'indicator_uri' => ''
                            ]
                        ],
                        'baseline'    => [
                            0 => [
                                'year'    => '2008',
                                'value'   => 'N=35;P=55',
                                'comment' => [
                                    0 => [
                                        'narrative' => [
                                            0 => [
                                                'narrative' => 'Source:administrative records',
                                                'language'  => 'en'
                                            ],
                                            1 => [
                                                'narrative' => 'Source:administrative records',
                                                'language'  => 'fr'
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
                                        'date' => '2011-01-01'
                                    ]
                                ],
                                'period_end'   => [
                                    0 => [
                                        'date' => '2011-12-31'
                                    ]
                                ],
                                'target'       => [
                                    0 => [
                                        'value'     => 'N=42;P=57',
                                        'location'  => [
                                            0 => [
                                                'ref' => ''
                                            ]
                                        ],
                                        'dimension' => [
                                            0 => [
                                                'name'  => '',
                                                'value' => ''
                                            ]
                                        ],
                                        'comment'   => [
                                            0 => [
                                                'narrative' => [
                                                    0 => [
                                                        'narrative' => 'Source:administrative records',
                                                        'language'  => 'en'
                                                    ],
                                                    1 => [
                                                        'narrative' => 'Source:administrative records',
                                                        'language'  => 'fr'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'actual'       => [
                                    0 => [
                                        'value'     => 'N=41;P=56',
                                        'location'  => [
                                            0 => [
                                                'ref' => ''
                                            ]
                                        ],
                                        'dimension' => [
                                            0 => [
                                                'name'  => '',
                                                'value' => ''
                                            ]
                                        ],
                                        'comment'   => [
                                            0 => [
                                                'narrative' => [
                                                    0 => [
                                                        'narrative' => 'Source:administrative records',
                                                        'language'  => 'en'
                                                    ],
                                                    1 => [
                                                        'narrative' => 'Source:administrative records',
                                                        'language'  => 'fr'
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
}