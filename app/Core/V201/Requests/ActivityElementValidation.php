<?php namespace App\Core\V201\Requests;


class ActivityElementValidation
{
    public function validateActivity($activityData, $transactionData)
    {

        $messages = [];

        if (empty($activityData->title)) {
            $messages[] = 'Title is Required Element.';
        }

        if (empty($activityData->description)) {
            $messages[] = 'Description is required Element.';
        }

        if (empty($activityData->participating_organization)) {
            $messages[] = 'Participating Organization is required Element.';
        }

        if (empty($activityData->activity_status)) {
            $messages[] = 'Activity Status is required Element.';
        }

        if (empty($activityData->activity_date)) {
            $messages[] = 'Activity Date is required Element.';
        }

        if (empty($activityData->sector)) {
            $messages[] = 'Sector is required Element.';
        }

        if (empty($activityData->recipient_country) && empty($activityData->recipient_region)) {
            $messages[] = 'Either Recipient Country or Recipient Region is required';
        }

        $recipientCountryValue         = false;
        $activityRecipientCountryValue = false;
        $recipientRegionValue          = false;
        $activityRecipientRegionValue  = false;
        $totalPercentage               = 0;

        if (!empty($activityData->recipient_country)) {
            foreach ($activityData->recipient_country as $recipientCountry) {
                if ($recipientCountry['percentage'] !== '') {
                    $totalPercentage += $recipientCountry['percentage'];
                    $recipientCountryValue = true;
                }
                $activityRecipientCountryValue = true;
            }
        }

        if (!empty($activityData->recipient_region)) {
            foreach ($activityData->recipient_region as $recipientRegion) {
                if ($recipientRegion['percentage'] !== '') {
                    $totalPercentage += $recipientRegion['percentage'];
                    $recipientRegionValue = true;
                }
                $activityRecipientRegionValue = true;
            }
        }

        if ($totalPercentage !== 100 && $totalPercentage !== 0) {
            if ($recipientCountryValue == true && $recipientRegionValue == true) {
                $messages[] = 'The sum of percentage in Recipient Country and Recipient Region must be 100.';
            } elseif ($recipientCountryValue == true) {
                $messages[] = 'The sum of percentage in Recipient Country must be 100.';
            } elseif ($recipientRegionValue == true) {
                $messages[] = 'The sum of percentage in Recipient Region must be 100.';
            }
        }

        $transactionCountryRegion = false;

        if (!empty($transactionData)) {
            foreach ($transactionData as $transactions) {
                $transactionDetail = $transactions->transaction;
                removeEmptyValues($transactionDetail);
                if (!empty($transactionDetail['recipient_country']) || !empty($transactionDetail['recipient_region'])) {
                    $transactionCountryRegion = true;
                }
            }
        }

        if ($transactionCountryRegion == true && ($activityRecipientCountryValue == true || $activityRecipientRegionValue == true)) {
            $messages[] = 'You can only mention recipient Country or region either in Activity Level only or in Transaction level only.';
        }

        $messageList = '';

        foreach ($messages as $message) {
            $messageList .= sprintf('<li>- %s</li>', $message);
        }

        $messageHtml = '';
        if ($messageList) {
            $messageHtml .= 'Please make sure you have all following criteria full filled before changing to completed state: ';
            $messageHtml .= sprintf('<ul>%s</ul>', $messageList);
        }

        return $messageHtml;
    }
}
