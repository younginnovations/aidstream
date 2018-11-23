<?php namespace App\Np\Forms\V202;

use App\Core\Form\BaseForm;

/**
 * Class Profile
 * @package App\Np\Forms\V202
 */
class Profile extends BaseForm
{

    /**
     * Profile Form
     */
    public function buildForm()
    {
        return $this
            ->add('userName', 'text', ['label' => trans('lite/profile.username'), 'required' => true, 'attr' => ['readonly'], 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('email', 'text', ['label' => trans('lite/profile.email'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('firstName', 'text', ['label' => trans('lite/profile.first_name'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('lastName', 'text', ['label' => trans('lite/profile.last_name'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('permission', 'text', ['label' => trans('lite/profile.permission'), 'required' => true, 'attr' => ['readonly'], 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->addSelect(
                'timeZone',
                $this->getCodeList('TimeZone', 'Activity', false),
                trans('lite/profile.time_zone'),
                null,
                null,
                true,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6 timezone'],
                ]
            )
            ->add('secondaryFirstName', 'text', ['label' => trans('lite/profile.secondary') . ' ' . trans('lite/profile.first_name'), 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('secondaryLastName', 'text', ['label' => trans('lite/profile.secondary') . ' ' . trans('lite/profile.last_name'), 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('secondaryEmail', 'text', ['label' => trans('lite/profile.secondary') . ' ' . trans('lite/profile.email'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add(trans('lite/global.save'), 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
