<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class PublishingTypeForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'publishing',
                'choice',
                [
                    'label'          => 'Publishing Type',
                    'choices'        => ['unsegmented' => 'Unsegmented', 'segmented' => 'Segmented'],
                    'expanded'       => true,
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ]
                ]
            );
    }
}
