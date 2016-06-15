<?php namespace App\Services\Xml;

use Illuminate\Support\Str;
use SimpleXMLIterator;

class XmlSchemaErrorParser
{

    protected $mappings = [
        '1868'    => 'The required :element Code is missing from the ‘:element’ element.',
        '1871'    => 'The required :sub-element is missing from the ‘:element’ element.',
        'default' => 'The required :element is missing from the ‘:element’ element'
    ];


    /**
     * get modified message send as per the schema
     * @param $error
     * @param $validateXml
     * @return mixed|string
     */
    public function getModifiedError($error, $validateXml)
    {
        $errorLine        = $error->line;
        $xmlLines         = explode("\n", $validateXml);
        $xmlLines         = $this->removeSpace($xmlLines);
        $errorCodeElement = $this->getErrorElementName($xmlLines, $errorLine);
        $elementsInXml    = $this->getElementsFromXml($validateXml);
        $mainElement      = $this->getMainElement($errorCodeElement, $elementsInXml, $xmlLines, $errorLine);
        $message          = $this->getProperMessage($errorCodeElement, $mainElement, $error);

        return $message;
    }

    /**
     * remove extra space for an xml
     * @param $xmlLines
     * @return mixed
     */
    protected function removeSpace($xmlLines)
    {
        array_walk_recursive(
            $xmlLines,
            function (&$value) {
                $value = trim(preg_replace('/\s+/', " ", $value));
            }
        );
        return $xmlLines;
    }

    /**
     * get sub-elements/element code in which error occur
     * @param $xmlLines
     * @param $errorLine
     * @return mixed
     */
    protected function getErrorElementName($xmlLines, $errorLine)
    {
        $errorCode = substr($xmlLines[$errorLine - 1], 1);
        $errorCode = preg_split("/( |>|<)/", $errorCode);
        return $errorCode[0];
    }


    /**
     * get all the main elements from xml
     * @param $validateXml
     * @return array
     */
    protected function getElementsFromXml($validateXml)
    {
        $parsedXml = new SimpleXMLIterator($validateXml);
        $elements  = [];
        foreach ($parsedXml->children()->children() as $key => $data) {
            if($key != "title"){
                $elements[] = $key;
            }
        }
        return $elements;
    }

    /**
     * get main element of specific error
     * @param $errorCodeElement
     * @param $elementsInXml
     * @param $xmlLines
     * @param $errorLine
     * @return mixed
     */
    protected function getMainElement($errorCodeElement, $elementsInXml, $xmlLines, $errorLine)
    {
        if (in_array($errorCodeElement, $elementsInXml)) {
            return $errorCodeElement;
        } else {
            $errorLine        = $errorLine - 1;
            $errorCodeElement = $this->getErrorElementName($xmlLines, $errorLine);
            return $this->getMainElement($errorCodeElement, $elementsInXml, $xmlLines, $errorLine);
        }
    }

    /**
     * get modified message
     * @param $errorCodeElement
     * @param $mainElement
     * @param $error
     * @return mixed|string
     */
    protected function getProperMessage($errorCodeElement, $mainElement, $error)
    {
        $mappings = (array_key_exists($error->code, $this->mappings)) ? $this->mappings[$error->code] : $this->mappings['default'];

        if ($errorCodeElement == $mainElement) {
            $message = str_replace(':element', ucfirst($errorCodeElement), $mappings);
        } elseif ($error->code == "1871") {
            preg_match("/\( ([a-z\-]+) \)/", $error->message, $matches);
            $missingElem = str_replace('-', ' ', Str::title($matches[1]));
            $message     = str_replace(':element', ucfirst($mainElement), $mappings);
            $message     = str_replace(':sub-element', $missingElem, $message);
        } else {
            $message = str_replace(':element', ucfirst($errorCodeElement), $mappings);
            $message .= ' of ' . ucfirst($mainElement) . ' element.';
        }

        return $message;
    }
}