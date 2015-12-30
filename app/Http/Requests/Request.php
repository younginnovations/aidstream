<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all()
    {
        $input      = $this->input();
        $whitespace = create_function('&$value, &$key', '$key; $value = trim(preg_replace("/\s+/", " " ,$value));');
        array_walk_recursive($input, $whitespace);
        $this->replace($input);

        return array_replace_recursive($this->input(), $this->files->all());
    }

    public function rules()
    {
        return [];
    }

}
