<?php namespace App\Core\V201\Requests;

class OrganizationElementValidation
{

    /**
     * Check if certain elements are present in Organization Data
     * @param $organization
     * @return string
     */
    public function validateOrganization($organization)
    {
        $messages = [];

        if (empty($organization->name)) {
            $messages[] = trans('validation.required', ['attribute' => trans('elementForm.name')]);
        }

        $messageList = '';

        foreach ($messages as $message) {
            $messageList .= sprintf('<li>- %s</li>', $message);
        }

        $messageHtml = '';
        if ($messageList) {
            $messageHtml .= trans('validation.validation_before_completed');
            $messageHtml .= sprintf('<ul>%s</ul>', $messageList);
        }

        return $messageHtml;
    }
}
