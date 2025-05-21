<?php
namespace App\Core;

/**
 * Middleware Interface
 * 
 * All middleware classes must implement this interface
 */
interface Middleware
{
    /**
     * Handle the request
     * 
     * @param callable $next Next middleware in pipeline
     * @return void
     */
    public function handle($next);
}