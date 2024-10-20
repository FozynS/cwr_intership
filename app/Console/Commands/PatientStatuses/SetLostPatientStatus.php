<?php

namespace App\Console\Commands\PatientStatuses;

use App\Patient;
use App\PatientComment;
use App\PatientStatus;
use App\Status;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetLostPatientStatus extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:set-lost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        dispatch(new \App\Jobs\Patients\SetLostPatientStatus());
    }
}
