<?php namespace App\Np\Forms\V202;

use App\Core\Form\BaseForm;

/**
 * Class Password
 * @package App\Np\Forms\V202
 */
class Password extends BaseForm
{
    /**
     * Password Form
     */
    public function buildForm()
    {
        return $this
            ->add('oldPassword', 'password', ['label' => trans('lite/profile.old_password'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('newPassword', 'password', ['label' => trans('lite/profile.new_password'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add('newPasswordAgain', 'password', ['label' => trans('lite/profile.new_password_again'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6']])
            ->add(trans('lite/global.change'), 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
