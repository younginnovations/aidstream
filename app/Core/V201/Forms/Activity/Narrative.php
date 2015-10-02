<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Narrative
 * @package App\Core\V201\Forms\Activity
 */
class Narrative extends Form
{
    protected $showFieldErrors = true;

    /**
     * builds the narrative form
     */
    public function buildForm()
    {
        $languageCodeList  = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Organization/LanguageCodelist.json")
        );
        $languages     = json_decode($languageCodeList, true);
        $language      = $languages['Language'];
        $languageCodes = [];

        foreach ($language as $narrativeLanguage) {
            $languageCodes[$narrativeLanguage['code']] = $narrativeLanguage['code'] . ' - ' . $narrativeLanguage['name'];
        }

        $this
            ->add('narrative', 'text', ['label' => $this->getData('narrativeLabel') ? $this->getData('narrativeLabel') : 'Text', 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices' => $languageCodes,
                    'label'   => 'Language'
                ]
            )
            ->add(
                'Remove this',
                'button',
                [
                    'attr' => [
                        'class' => 'remove_from_collection',
                    ]
                ]
            );
    }
}
