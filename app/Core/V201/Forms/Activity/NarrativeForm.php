<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class NarrativeForm
 * @package App\Core\V201\Forms\Activity
 */
class NarrativeForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $json     = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Organization/LanguageCodelist.json")
        );
        $response = json_decode($json, true);
        $language = $response['Language'];
        $codeArr = [];
        foreach ($language as $val) {
            $codeArr[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add('narrative', 'text', ['label' => 'Text', 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices' => $codeArr,
                    'label'   => 'Language'
                ]
            );
    }
}
