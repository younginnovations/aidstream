<?php namespace App\Np\Services\Validation;

use App\Np\Services\Validation\Rules\RulesProvider;
use App\Np\Services\Validation\Traits\RegisterValidationRules;
use Illuminate\Validation\Factory;

/**
 * Class ValidationService
 * @package App\Np\Services\Validation
 */
class ValidationService
{
    use RegisterValidationRules;
    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $entity;

    /**
     * @var
     */
    protected $version;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var
     */
    protected $validator;

    /**
     * @var RulesProvider
     */
    protected $rulesProvider;

    /**
     * ValidationService constructor.
     *
     * @param Factory       $factory
     * @param RulesProvider $rulesProvider
     */
    public function __construct(Factory $factory, RulesProvider $rulesProvider)
    {
        $this->factory       = $factory;
        $this->rulesProvider = $rulesProvider;
        $this->customRules();
    }

    /**
     * Check if the provided data passes the validation rules.
     * Validation class loaded according to version.
     *
     * @param array $data
     * @param       $entityType
     * @param       $version
     * @return mixed
     */
    public function passes(array $data, $entityType, $version)
    {
        $this->validator = $this->factory->make(
            $data,
            $this->rulesProvider->getRules($version, $entityType),
            $this->rulesProvider->getMessages($version, $entityType)
        );

        return $this->validator->passes();
    }


    /**
     * Returns errors if validation fails.
     *
     * @return mixed
     */
    public function errors()
    {
        if ($this->validator) {
            return $this->validator->errors();
        }
    }
}
