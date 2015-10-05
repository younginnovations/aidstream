<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ParticipatingOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ParticipatingOrganization extends Form
{
    /**
     * @var CodeList
     */
    protected $codeList;

    /**
     * @param CodeList $codeList
     */
    function __construct(CodeList $codeList)
    {
        $this->codeList = $codeList;
    }

    /**
     * builds activity participating organization form
     */
    public function buildForm()
    {
        $this
            ->add(
                'organization_role',
                'select',
                [
                    'choices' => $this->codeList->getCodeList('OrganisationRole'),
                    'label'   => 'Organization Role'
                ]
            )
            ->add('identifier', 'text')
            ->add(
                'organization_type',
                'select',
                [
                    'choices' => $this->codeList->getCodeList('OrganisationType'),
                    'label'   => 'Organization Type'
                ]
            )
            ->add(
                'narrative',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Narrative',
                        'label' => false,
                        'data'  => ['narrativeLabel' => 'Organization Name']
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form narrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'narrative'
                    ]
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
