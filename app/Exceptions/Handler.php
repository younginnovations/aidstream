<?php namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        QueryException::class
    ];


    protected $systemVersionRedirectPath = [
        1 => 'activity.index',
        2 => 'lite.activity.index',
        3 => 'np.activity.index'
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
        $route = 'activity.index';

        if ($e instanceof NotFoundHttpException || $e instanceof MethodNotAllowedHttpException) {
            $message = $this->getMessage($e->getStatusCode());

            if (auth()->check()) {
                if (($organization = auth()->user()->organization)) {
                    $systemVersion = $organization->system_version_id;
                    (!array_key_exists($systemVersion, $this->systemVersionRedirectPath)) ?: $route = $this->systemVersionRedirectPath[$systemVersion];
                }
            }
            
            return response()->view('errors.errors', compact('route', 'message'));
        }

        if ($e instanceof QueryException) {
            $message = $this->getMessage('505');

            return response()->view('errors.errors', compact('route', 'message'));
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
        $message = $this->getMessage();

        return response()->view('errors.errors', compact('message', 'route'));
    }

    /**
     * Returns exception message from the config file.
     *
     * @param null $code
     * @return mixed
     */
    protected function getMessage($code = null)
    {
        if ($code) {
            if (array_key_exists($code, config('exceptionMessages'))) {
                return config(sprintf('exceptionMessages.%s', $code));
            }
        }

        return config('exceptionMessages.default');
    }
}

