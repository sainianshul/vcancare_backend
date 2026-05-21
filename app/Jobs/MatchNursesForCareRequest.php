<?php

namespace App\Jobs;

use App\Models\CareRequest;
use App\Models\NurseProfile;
use App\Models\NurseRequestCache;
use Dom\Implementation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MatchNursesForCareRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $careRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(CareRequest $careRequest)
    {
        $this->careRequest = $careRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       
}



