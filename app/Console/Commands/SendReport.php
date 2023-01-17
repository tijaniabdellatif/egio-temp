<?php

namespace App\Console\Commands;

use App\Models\ads;
use App\Models\User;
use App\Mail\SendReport as MailSend;
use App\Mail\SimpleMail;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendreport:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report to the client of expiration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $now = Carbon::now();
        $expiredAds = DB::table('ads')
        ->whereRaw("Date(expiredate) <= '.$now.'")
        ->count();



        Mail::send(new SimpleMail(
            [
                "to" => 'abdellativovich@hotmail.com',
                "subject" => "subject",
                "view" => 'emails.SendReport.fr',
                "data" => [

                     'title' => "title"
                ]
                ]));

    }
}
