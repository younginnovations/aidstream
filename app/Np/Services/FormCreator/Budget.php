<?php namespace App\Np\Services\FormCreator;


use App\Np\Forms\FormPathProvider;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class Budget
 * @package App\Np\Services\FormCreator
 */
class Budget
{
    use FormPathProvider;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * Activity constructor.
     *
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
     * @param null $model
     * @return Form
     */
    public function form($route, $model = null)
    {
        $formPath = $this->getFormPath('Budgets');

        return $this->formBuilder->create(
            $formPath,
            [
                'method' => 'post',
                'model'  => $model,
                'url'    => $route
            ]
        )->add('Save', 'submit', ['label' => $model ? trans('lite/global.save') : trans('lite/elementForm.add_this_budget'), 'attr' => ['class' => 'btn btn-submit btn-form'], ['wrapper' => ['class' => 'border-btn-line']]]);
    }
}
