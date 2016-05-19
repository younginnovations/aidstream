<?php namespace App\Services\Twitter;

use Thujohn\Twitter\Facades\Twitter;
use Illuminate\Contracts\Logging\Log as Logger;

class TwitterAPI
{
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function post($settings, $org)
    {
        $apiId   = $settings['registry_info'][0]['publisher_id'];
        $twitter = "";
        if ($org->twitter != "") {
            $twitter = $org->twitter;
            if (substr($twitter, 0, 1) != '@') {
                $twitter = '@' . $twitter;
            }
            $twitter = ' '. $twitter;
        }

        $status = $org->name . $twitter . " has published their #IATIData. View the data here: ";
        $status .= 'http://iatiregistry.org/publisher/' . $apiId . ' #AidStream';

        try {
            $twitterResponse = Twitter::postTweet(['status' => $status, 'format' => 'json']);
            $this->logger->info(
                sprintf('Twitter has been successfully publish for %s with info : %s', $org->name, $twitterResponse)
            );

        } catch (\Exception $e) {
            $this->logger->error($e, ['org_name' => $org->name]);
        }
    }
}
