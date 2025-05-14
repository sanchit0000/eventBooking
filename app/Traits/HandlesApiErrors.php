<?php

namespace App\Traits;

use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandlesApiErrors
{
    protected function handleError(\Exception $e)
    {
        $statusCode = $e instanceof HttpException 
            ? $e->getStatusCode()
            : 500;

        return response()->json([
            'error' => $e->getMessage()
        ], $statusCode);
    }
}