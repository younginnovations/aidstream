<?php namespace App\Np\Services\Data\V202\DocumentLink;


use App\Np\Services\Data\Contract\MapperInterface;

class DocumentLink implements MapperInterface
{
    /**
     * Code for outcomes document category.
     */
    const OUTCOMES_DOCUMENT_CODE = 'A08';

    /**
     * Code for annual report document category.
     */
    const ANNUAL_REPORT_CODE = 'B01';

    /*
     * Default Document format,
     */
    const DOCUMENT_FORMAT = 'application/pdf';

    /**
     * Raw data holder for Activity entity.
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * Contains mapped data.
     * @var array
     */
    protected $mappedData = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * Data template for Activity.
     *
     * @var array
     */
    protected $template = [];

    /**
     * Path to template.
     */
    const BASE_TEMPLATE_PATH = 'Services/XmlImporter/Foundation/Support/Templates/V202.json';

    /**
     * @var array
     */
    protected $mappedFields = [
        'outcomes_document' => 'document_link',
        'annual_report'     => 'document_link'
    ];

    public function __construct(array $rawData)
    {
        $this->rawData  = $rawData;
        $this->template = $this->loadTemplate();
    }


    /**
     * Map raw data into the database compatible format.
     *
     * @return array
     */
    public function map()
    {
        foreach ($this->rawData as $key => $value) {
            if (!empty($value) && array_key_exists($key, $this->mappedFields)) {
                $methodName = $this->mappedFields[$key];

                if (method_exists($this, $methodName)) {
                    $this->resetIndex($this->mappedFields[$key]);
                    $this->{$this->mappedFields[$key]}($key, $value, $this->getTemplateOf($key));
                }
            }
        }

        return $this->mappedData;
    }

    /**
     * Map database data into frontend compatible format.
     *
     * @return array
     */
    public function reverseMap()
    {
        foreach ((array) $this->rawData as $index => $documentLink) {
            $documentCategory = $this->getDocumentCategory(getVal($documentLink, ['document_link', 'category', 0, 'code']), true);
            if (!$documentCategory) {
                return $this->mappedData;
            }

            if (!array_key_exists($documentCategory, $this->mappedData)) {
                $index = 0;
            }

            $this->mappedData[$documentCategory][$index]['document_title']   = getVal($documentLink, ['document_link', 'title', 0, 'narrative', 0, 'narrative']);
            $this->mappedData[$documentCategory][$index]['document_url']     = getVal($documentLink, ['document_link', 'url']);
            $this->mappedData[$documentCategory][$index]['document_link_id'] = getVal($documentLink, ['id']);
        }

        return $this->mappedData;
    }

    /**
     * Reset the index value to 0.
     * @param $key
     */
    protected function resetIndex($key)
    {
        if (!array_key_exists($key, $this->mappedData)) {
            $this->index = 0;
        }
    }

    /**
     * Map the data to document link template.
     *
     * @param $key
     * @param $value
     * @param $template
     */
    protected function document_link($key, $value, $template)
    {
        $documentCategory = $this->getDocumentCategory($key);

        foreach ($value as $index => $field) {
            if ($documentCategory) {
                $documentTitle = getVal($field, ['document_title']);
                $documentUrl   = getVal($field, ['document_url']);
                $documentId    = getVal($field, ['document_link_id']);

                if ($documentTitle != "" || $documentUrl != "" || $documentId != "") {
                    $this->mappedData['document_link'][$this->index] = $template;

                    if ($documentId != "") {
                        $this->mappedData['document_link'][$this->index]['id'] = $documentId;
                    }

                    $this->mappedData['document_link'][$this->index]['url']                      = $documentUrl;
                    $this->mappedData['document_link'][$this->index]['title'][0]['narrative'][0] = ['narrative' => $documentTitle, 'language' => ''];
                    $this->mappedData['document_link'][$this->index]['category'][0]['code']      = $documentCategory;
                    $this->mappedData['document_link'][$this->index]['format']                   = self::DOCUMENT_FORMAT;
                    ($documentId != "") ?: $this->mappedData['document_link'][$this->index]['document_date'][0]['date'] = date('Y-m-d');
                    $this->index ++;
                }
            }
        }
    }

    /**
     * Returns the document category.
     * If reversed is true, then key is returned.
     *
     * @param      $key
     * @param bool $reversed
     * @return mixed|string
     */
    protected function getDocumentCategory($key, $reversed = false)
    {
        $documentCategory = [
            'outcomes_document' => self::OUTCOMES_DOCUMENT_CODE,
            'annual_report'     => self::ANNUAL_REPORT_CODE
        ];

        if ($reversed) {
            return array_key_exists($key, array_flip($documentCategory)) ? getVal(array_flip($documentCategory), [$key]) : false;
        }

        return array_key_exists($key, $documentCategory) ? $documentCategory[$key] : false;
    }

    /**
     * Returns specific template of an element.
     *
     * @param $key
     * @return mixed
     */
    public function getTemplateOf($key)
    {
        return $this->template[$this->mappedFields[$key]];
    }

    /**
     * Returns template of the elements.
     *
     * @return mixed
     */
    public function loadTemplate()
    {
        return json_decode(file_get_contents(app_path(self::BASE_TEMPLATE_PATH)), true);
    }
}

