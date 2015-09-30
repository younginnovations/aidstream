<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Narrative
 * Contains function that creates narrative form
 * @package App\Core\V201\Forms\Activity
 */
class Narrative extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $json          = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Organization/LanguageCodelist.json")
        );
        $response      = json_decode($json, true);
        $language      = $response['Language'];
        $languageCodes = [];
        foreach ($language as $val) {
            $languageCodes[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add('narrative', 'text', ['label' => 'Text', 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices' => $languageCodes,
                    'label'   => 'Language'
                ]
            );
    }
}
