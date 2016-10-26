<?php namespace App\Services;

use Exception;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Contracts\Mail\Mailer;

/**
 * Class Contact
 * @package App\Services
 */
class Contact
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var array
     */
    protected $methods = [
        'not-my-organization'           => 'getNotMyOrg',
        'need-new-user'                 => 'getNeedNewUser',
        'forgot-user-email'             => 'getUserEmail',
        'contact-admin-for-same-org'    => 'getSameOrgAdmin',
        'contact-support-for-same-org'  => 'getSameOrgSupport',
        'no-secondary-contact-support'  => 'getNoSecondaryContact',
        'has-secondary-contact-support' => 'getHasSecondaryContact'
    ];

    /**
     * @var array
     */
    protected $titles = [
        'not-my-organization'           => 'Contact Support',
        'need-new-user'                 => 'Contact Administrator',
        'forgot-user-email'             => 'Contact Administrator',
        'contact-admin-for-same-org'    => 'Contact Administrator',
        'contact-support-for-same-org'  => 'Contact Support',
        'no-secondary-contact-support'  => 'Contact Support',
        'has-secondary-contact-support' => 'Contact Support'
    ];

    /**
     * @var array
     */
    protected $messages = [
        'not-my-organization'           => 'Your message has been sent to the AidStream Support Team. <br/> They will get back to you soon to help you sort out the problem.',
        'need-new-user'                 => 'Your message has been sent to the administrator of your organisation\'s AidStream account.',
        'forgot-user-email'             => 'Your message has been sent to the administrator of your organisation\'s AidStream account.',
        'contact-admin-for-same-org'    => 'Your message has been sent to the administrator of your organisation\'s AidStream account.',
        'contact-support-for-same-org'  => 'Your message has been sent to the AidStream Support Team. <br/> They will get back to you soon to help you sort out the problem.',
        'no-secondary-contact-support'  => 'Your message has been sent to the AidStream Support Team. <br/> They will get back to you soon to help you sort out the problem.',
        'has-secondary-contact-support' => 'Your message has been sent to the AidStream Support Team. <br/> They will get back to you soon to help you sort out the problem.'
    ];

    /**
     * Contact constructor.
     * @param Logger $logger
     * @param Mailer $mailer
     */
    public function __construct(Logger $logger, Mailer $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    /**
     * @param $template
     * @return string
     */
    public function getContactTitle($template)
    {
        return getVal($this->titles, [$template], 'Contact');
    }

    /**
     * processes emails according to requested contact info
     * @param $data
     * @param $template
     * @return bool
     */
    public function processEmail($data, $template)
    {
        try {
            $this->{$this->methods[$template]}($data);
            $data['content'] = $data['message'];
            $this->sendEmail($data);

            return $this->messages[$template];
        } catch (Exception $exception) {
            dd($exception);
            $this->logger->error($exception, ['data' => $data]);
        }

        return false;
    }

    /**
     * send processed contact emails
     * @param $data
     */
    protected function sendEmail($data)
    {
        $callback = function ($message) use ($data) {
            $message->subject($data['subject']);
            $fromEmail = $data['email'];
            $fromName  = $data['full_name'];
            if (getVal($data, ['fromEmail'])) {
                $fromEmail = $data['fromEmail'];
                $fromName  = $data['fromName'];
            }
            $message->from($fromEmail, $fromName);
            $message->to($data['emailTo']);
        };
        if ($view = getVal($data, ['view'])) {
            $this->mailer->send($view, $data, $callback);
        } else {
            $this->mailer->raw($data['message'], $callback);
        }
    }

    /**
     * @param $data
     */
    protected function getNotMyOrg(&$data)
    {
        $data['emailTo'] = env('MAIL_ADDRESS');
        $data['subject'] = 'Not My Organisation';
    }

    /**
     * @param $data
     */
    protected function getNeedNewUser(&$data)
    {
        $data['fromEmail'] = env('MAIL_ADDRESS');
        $data['fromName']  = env('MAIL_NAME');
        $data['emailTo']   = session()->pull('admin_email');
        $data['adminName'] = session()->pull('admin_name');
        $data['orgName']   = session()->pull('org_name');
        $data['subject']   = sprintf("Request for new user account on your %s's AidStream account.", $data['orgName']);
        $data['view']      = 'emails.need-new-user-account';
    }

    /**
     * @param $data
     */
    protected function getUserEmail(&$data)
    {
        $data['emailTo'] = session()->pull('admin_email');
        $data['subject'] = 'Forgot User Email';
    }

    /**
     * @param $data
     */
    protected function getSameOrgAdmin(&$data)
    {
        $data['fromEmail'] = env('MAIL_ADDRESS');
        $data['fromName']  = env('MAIL_NAME');
        $data['emailTo']   = session()->pull('admin_email');
        $data['adminName'] = session()->pull('admin_name');
        $data['orgName']   = session()->pull('org_name');
        $data['subject']   = 'AidStream User Account Details.';
        $data['view']      = 'emails.need-new-user-account';
    }

    /**
     * @param $data
     */
    protected function getSameOrgSupport(&$data)
    {
        $data['emailTo'] = env('MAIL_ADDRESS');
        $data['subject'] = 'Organisation name already exists.';
    }

    /**
     * @param $data
     */
    protected function getNoSecondaryContact(&$data)
    {
        $data['emailTo'] = env('MAIL_ADDRESS');
        $data['subject'] = 'Forgot admin username and password. No secondary contact.';
    }

    /**
     * @param $data
     */
    protected function getHasSecondaryContact(&$data)
    {
        $data['emailTo'] = env('MAIL_ADDRESS');
        $data['subject'] = 'Forgot admin username and password. Has secondary contact.';
    }
}
