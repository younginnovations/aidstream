<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

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
        if (is_a($e, HttpResponseException::class) || is_a($e, ValidationException::class) || env('APP_DEBUG')) {
            if (is_a($e, HttpResponseException::class)) {
                $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Failed to save data due to validation errors.']]];
                session()->flash('response', $response);
            }

            return parent::render($request, $e);
        }

        $error = $e->getMessage() ? sprintf('Error: %s', $e->getMessage()) : 'Unknown Error:';
        $this->log->error($error, ['url' => $request->url(), 'session' => session()->all(), 'trace' => $e->getTraceAsString()]);
        $message = 'Whoops, look like something went wrong.';

        return response()->view(sprintf('errors.%s', auth()->check() ? 'errors' : 'noAuthErrors'), compact('message'));
    }
}
