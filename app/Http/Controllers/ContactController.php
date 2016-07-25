<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Contact as ContactManager;
use App\Services\RequestManager\Contact;

/**
 * Class ContactController
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * @var ContactManager
     */
    protected $contactManager;

    /**
     * ContactController constructor.
     * @param ContactManager $contactManager
     */
    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * @param $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showContactForm($template)
    {
        return view('auth.contact');
    }

    /**
     * @param         $template
     * @param Contact $request
     * @return mixed
     */
    public function processEmail($template, Contact $request)
    {
        if ($this->contactManager->processEmail($request->all(), $template)) {
            return redirect()->to('/')->withMessage('Your query has been submitted.');
        } else {
            return redirect()->back()->withInput()->withErrorMessage('Failed to submit your query. Please try again.');
        }
    }
}
