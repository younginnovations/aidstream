<?php namespace App\Core\V201\Traits;

/**
 * Class XmlServiceTrait
 * @package App\Core\V201\Traits
 */
trait XmlServiceTrait
{
    /**
     * return xml validation message with type
     * @param $error
     * @return string
     */
    protected function libxml_display_error($error)
    {
        $return = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code:";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code:";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code:";
                break;
        }
        $return .= trim($error->message);
        $errorLine = $error->line - 4;
        $return .= "in  line no. <a href='#$errorLine'><b>$error->line</b></a>";

        return $return;
    }

    /**
     * return xml validation error messages
     * @return array
     */
    protected function libxml_fetch_errors()
    {
        $errors = libxml_get_errors();
        return $errors;
    }

/**
* return xml validation error messages
* @return array
*/
    protected function libxml_display_errors()
    {
        $errors = libxml_get_errors();
        $messages = [];
        foreach ($errors as $error) {
            $messages[$error->line] = $this->libxml_display_error($error);
        }
        libxml_clear_errors();

        return $messages;
    }
}
