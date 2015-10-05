<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class ContactInfo extends Form
{
    /**
     * builds activity Contact Info form
     */
    public function buildForm()
    {
        $contactTypeCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/ContactType.json")
        );
        $contactTypes        = json_decode($contactTypeCodeList, true);
        $contactType         = $contactTypes['ContactType'];
        $contactTypeCode     = [];

        foreach ($contactType as $contact) {
            $contactTypeCode[$contact['code']] = $contact['code'] . ' - ' . $contact['name'];
        }

        $this
            ->add(
                'type',
                'select',
                [
                    'choices' => $contactTypeCode,
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
            ->add(
                'Add More1',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'telephoneNarrative'
                    ]
                ]
            )
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
            ->add(
                'Add More2',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'emailNarrative'
                    ]
                ]
            )
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
            ->add(
                'Add More3',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'websiteNarrative'
                    ]
                ]
            )
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
            ->add(
                'Add More4',
                'button',
                [
                    'label' => 'Add More',
                    'attr'  => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'mailingAddressNarrative'
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
