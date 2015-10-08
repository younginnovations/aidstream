<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class Sector
 * @package App\Core\V201\Requests\Activity
 */
class Sector extends Request
{
    /**
     * @var
     */
    protected $validation;

    /**
     * @param Validation $validation
     */
    function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

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
        return $this->addSectorsRules($this->request->get('sector'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->addSectorsMessages($this->request->get('sector'));
    }

    /**
     * returns rules for sector
     * @param $formFields
     * @return array|mixed
     */
    public function addSectorsRules($formFields)
    {
        $rules = [];
        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm = sprintf('sector.%s', $sectorIndex);
            if ($sector['vocabulary'] == 1) {
                $rules[sprintf('%s.sector_select', $sectorForm)] = 'required';
            } else {
                $rules[sprintf('%s.sector_text', $sectorForm)] = 'required';
            }
            $rules[sprintf('%s.percentage', $sectorForm)] = 'numeric|max:100';
            $rules                                        = $this->validation->addRulesForNarrative(
                $sector['narrative'],
                $sectorForm,
                $rules
            );
        }

        return $rules;
    }

    /**
     * returns messages for sector
     * @param $formFields
     * @return array|mixed
     */
    public function addSectorsMessages($formFields)
    {
        $messages = [];
        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm = sprintf('sector.%s', $sectorIndex);
            if ($sector['vocabulary'] == 1) {
                $messages[sprintf('%s.sector_select.%s', $sectorForm, 'required')] = 'Sector is required.';
            } else {
                $messages[sprintf('%s.sector_text.%s', $sectorForm, 'required')] = 'Sector is required.';
            }
            $messages[sprintf('%s.percentage.%s', $sectorForm, 'numeric')] = 'Percentage should be numeric';
            $messages[sprintf(
                '%s.percentage.%s',
                $sectorForm,
                'max:100'
            )]                                                             = 'Percentage should be less than or equal to required';
            $messages                                                      = $this->validation->addMessagesForNarrative(
                $sector['narrative'],
                $sectorForm,
                $messages
            );
        }

        return $messages;
    }
}
