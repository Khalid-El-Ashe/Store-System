<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProducts;
use Illuminate\Http\Request;

class ImportProductsController extends Controller
{
    /**
     * this controller for the Job Queues to can controlling the Job
     * this action (function controller) to turned the Job Queues
     *
     */

    public function create()
    {
        return view('dashboard.products.import');
    }

    public function store(Request $request)
    {
        // (dispatch) this method for executed the Job
        $job = new ImportProducts($request->post('count'));
        $job->onQueue('import')->onConnection('database');
        dispatch($job);

        return redirect()->route('dashboard.products.index')->with('success', 'Import is runing....');
    }
}
