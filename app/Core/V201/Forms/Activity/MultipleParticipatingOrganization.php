<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleParticipatingOrganization
 * @package App\Core\V201\Forms\Activity
 */
class MultipleParticipatingOrganization extends BaseForm
{
    /**
     * builds activity Participating Organization
     */
    public function buildForm()
    {
        $this
            ->addCollection(
                'participating_organization',
                'Activity\ParticipatingOrganization',
                'participating_organization',
                [],
                'Participating Organisation'
            )
            ->addAddMoreButton('add', 'participating_organization');
    }
}
