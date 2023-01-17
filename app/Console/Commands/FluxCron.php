<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use  App\Http\Controllers\flux\fluxController;
class FluxCron extends Command
{

    protected $handler;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flux:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'starting the flux';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->handler = new fluxController();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return $this->handler->getData();
    }
}
