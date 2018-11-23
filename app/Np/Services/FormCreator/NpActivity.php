<?php namespace App\Np\Services\FormCreator;

use App\Np\Forms\FormPathProvider;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;

class NpActivity
{
    use FormPathProvider;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * Activity constructor.
     * @param FormBuilder $formBuilder
     */
    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * Builds form for the activity.
     *
     * @param      $route
     * @param null $buttonLabel
     * @param null $model
     * @return Form
     */
    public function form($route, $buttonLabel = null, $model = null)
    {
        $formPath = $this->getFormPath('Activity');
        return $this->formBuilder->create(
            $formPath,
            [
                'method' => 'post',
                'model'  => $model,
                'url'    => $route
            ]
        )->add('Save', 'submit', ['label' => $model ? trans('lite/elementForm.update_this_activity') : trans('lite/elementForm.add_this_activity'), 'attr' => ['class' => 'btn btn-submit btn-form']]);
    }

    /**
     * Builds form for the activity.
     *
     * @param      $route
     * @param null $buttonLabel
     * @param null $model
     * @return Form
     */
    public function duplicateForm($route, $buttonLabel = null, $model = null)
    {
        $formPath = $this->getFormPath('ActivityDuplicate');

        return $this->formBuilder->create(
            $formPath,
            [
                'method' => 'post',
                'model'  => $model,
                'url'    => $route
            ]
        )->add('Save', 'submit', ['label' => $model ? trans('lite/elementForm.duplicate_this_activity') : trans('lite/elementForm.add_this_activity'), 'attr' => ['class' => 'btn btn-submit btn-form']]);
    }
}
