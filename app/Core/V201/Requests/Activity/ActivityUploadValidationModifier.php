<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Validation\Validator;

/**
 * Class ActivityUploadValidationModifier
 * @package App\Core\V201\Requests\Activity
 */
class ActivityUploadValidationModifier extends Validator
{
    /**
     * Get the indices for the rows having the validation failed.
     *
     * @return array
     */
    public function failures()
    {
        $failedRows = [];

        foreach ($this->failedRules as $index => $failedRule) {
            $failedRows[] = explode('.', $index + 1)[0];
        }

        return array_unique($failedRows);
    }
}
