<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
        $input = $this->input();
        array_walk_recursive(
            $input,
            function (&$value) {
                $value = trim(preg_replace('/\s+/', " ", $value));
            }
        );
        $this->replace($input);

        return array_replace_recursive($this->input(), $this->files->all());
    }

    public function rules()
    {
        return [];
    }

}
