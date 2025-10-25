<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Handle URL generation exceptions (missing routes)
     */
    public function render($request, Throwable $e)
    {
        // Handle missing route exceptions
        if ($e instanceof UrlGenerationException) {
            $routeName = $this->extractRouteNameFromException($e);
            
            if ($routeName) {
                Log::warning("Missing route referenced: {$routeName}", [
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);

                // Try to find a similar route
                $suggestions = \App\Services\RouteFallbackService::getRouteSuggestions($routeName);
                
                if (!empty($suggestions)) {
                    return response()->view('errors.404', [
                        'error' => "Route '{$routeName}' not found",
                        'suggestions' => $suggestions,
                        'originalRoute' => $routeName
                    ], 404);
                }
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Extract route name from URL generation exception
     */
    private function extractRouteNameFromException(UrlGenerationException $e)
    {
        $message = $e->getMessage();
        
        // Try to extract route name from the exception message
        if (preg_match('/Route \[([^\]]+)\] not defined/', $message, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
