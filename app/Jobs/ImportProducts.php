<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $count)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // i need make testing for the job Queues i need seeding 100,000 Record for Products
        Product::factory($this->count)->create();

        // when is finish you can send the notification for user toald him the Import is Done
    }
}
