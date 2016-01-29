<?php namespace App\Core;

use Illuminate\Contracts\Mail\Mailer;

/**
 * Class EmailQueue
 * @package App\Core
 */
class EmailQueue
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @param Mailer $mailer
     */
    function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * send registration mail
     * @param array $data
     * @param null  $callback
     */
    public function sendRegistrationMail($data = [], $callback = null)
    {
        if (null == $callback) {
            $callback = function ($message) use ($data) {
                $message->subject('Account Registration Confirmed');
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($data['email']);
            };
        }
        $this->addToQueue('emails.registration', $data, $callback);
    }

    /**
     * add mail to queue
     * @param $view
     * @param $data
     * @param $callback
     */
    protected function addToQueue($view, $data, $callback)
    {
        $this->mailer->queue($view, $data, $callback);
    }
}