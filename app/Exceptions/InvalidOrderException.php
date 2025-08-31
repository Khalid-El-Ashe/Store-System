<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InvalidOrderException extends Exception
{
    /**
     * this function to make report for the exception
     * this function that is to save the exceptions in the Log file
     */
    public function report(Exception $e)
    {
        if ($e->getMessage() == 23000) {
        }
    }

    /**
     * this function to make render for the exception
     * this function that is to showing the error exception to users
     */
    public function render(Request $request)
    {
        return Redirect::route('home')->withInput()->withErrors([
                'message' => $this->getMessage() //'Error while creating your Order.'
            ])->with('info', $this->getMessage());
    }
}
