<?php namespace App\Http\Requests\Xml;

use App\Http\Requests\Request;

/**
 * Class XmlUploadRequest
 * @package App\Http\Requests\Xml
 */
class XmlUploadRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'xml_file' => 'required|mimes:xml'
        ];
    }

    /**
     * Get the messages for the failed validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'xml_file.required' => 'You need to choose a file to upload.',
            'xml_file.mimes'    => 'The uploaded file must be an Xml file.'
        ];
    }
}
