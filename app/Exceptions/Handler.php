<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        TokenMismatchException::class,
        HttpResponseException::class,
        NotFoundHttpException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            if (auth()->check()) {
                return redirect()->route('activity.index')->withResponse(['type' => 'warning', 'code' => ['message', ['message' => '<b>404! Not Found</b><br>The requested url cannot be found in our system.']]]);
            }

            $message = '<b>404! Not Found</b><br><br>The requested url cannot be found in our system. <br><br> Please contact us at <a href="support@aidstream.org" target="_blank">support@aidstream.org</a>';

            return response()->view(sprintf('errors.%s', auth()->check() ? 'errors' : 'noAuthErrors'), compact('message'));
//            return redirect()->to('/')->withResponse(['type' => 'warning', 'code' => ['message', ['message' => '<b>404! Not Found</b><br>The requested url cannot be found in our system.']]]);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back()->exceptInput('_token')->withResponse(['type' => 'warning', 'code' => ['message', ['message' => 'Token has been expired. Please resubmit the form again.']]]);
        }

        if ($e instanceof AuthorizationException) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => config('permissions.no_correct_permissions')]]]);
        }

        if (is_a($e, HttpResponseException::class) || is_a($e, ValidationException::class) || env('APP_DEBUG')) {
            if (is_a($e, HttpResponseException::class)) {
                $response = [
                    'type' => 'danger',
                    'code' => [
                        'message',
                        ['message' => 'Failed to save data due to validation errors. Please review and correct the errors marked in red below and once complete, save the form.']
                    ]
                ];
                session()->flash('response', $response);
            }

            return parent::render($request, $e);
        }

        $this->log->error($e);
        $message = 'Something went wrong. Please contact us at <a href="support@aidstream.org" target="_blank">support@aidstream.org</a>';

        return response()->view(sprintf('errors.%s', auth()->check() ? 'errors' : 'noAuthErrors'), compact('message'));
    }
}
