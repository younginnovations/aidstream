<?php namespace App\Np\Services\FormCreator;


use App\Np\Forms\FormPathProvider;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class Users
 * @package App\Np\Services\FormCreator
 */
class Users
{
    use FormPathProvider;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * Users constructor.
     * @param FormBuilder $formBuilder
     */
    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     *  Builds form for the user.
     *
     * @param null $model
     * @return Form
     */
    public function form($model = null)
    {
        $formPath = $this->getFormPath('Users');

        return $this->formBuilder->create(
            $formPath,
            [
                'method' => 'post',
                'model'  => $model,
                'url'    => route('lite.users.store')
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']]);

    }
}