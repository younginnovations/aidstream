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
            $messages[] = 'Name is required.';
        }

        $messageList = '';

        foreach ($messages as $message) {
            $messageList .= sprintf('<li>- %s</li>', $message);
        }

        $messageHtml = '';
        if ($messageList) {
            $messageHtml .= 'Please make sure you enter the following fields before changing to completed state.';
            $messageHtml .= sprintf('<ul>%s</ul>', $messageList);
        }

        return $messageHtml;
    }
}
