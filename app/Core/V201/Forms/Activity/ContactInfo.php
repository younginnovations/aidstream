<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class ContactInfo extends BaseForm
{
    /**
     * builds activity Contact Info form
     */
    public function buildForm()
    {
        $this
            ->add(
                'type',
                'select',
                [
                    'choices' => $this->getCodeList('ContactType', 'Activity'),
                    'label'   => 'Contact Type'
                ]
            )
            ->add(
                'organization',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\ContactInfoOrganization',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form organizationNarrative'
                    ]
                ]
            )
            ->add(
                'department',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Department',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form DepartmentNarrative'
                    ]
                ]
            )
            ->add(
                'person_name',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\PersonName',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form PersonNameNarrative'
                    ]
                ]
            )
            ->add(
                'job_title',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\JobTitle',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form jobTitleNarrative'
                    ]
                ]
            )
            ->add(
                'telephone',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Telephone',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form telephoneNarrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_telephoneNarrative', 'telephoneNarrative')
            ->add(
                'email',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Email',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form emailNarrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_emailNarrative', 'emailNarrative')
            ->add(
                'website',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Website',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form websiteNarrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_websiteNarrative', 'websiteNarrative')
            ->add(
                'mailing_address',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\MailingAddress',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form mailingAddressNarrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_mailingAddressNarrative', 'mailingAddressNarrative')
            ->addRemoveThisButton('remove_contact_info');
    }
}
