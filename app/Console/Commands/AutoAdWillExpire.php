<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ads;
use App\Mail\SimpleMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoAdWillExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AdWillExpire:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to user when ad will expire';

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
        $daysLeft = Carbon::today()->addDays(3);

        $willExpire  = ads::where('expiredate','<=',$daysLeft)->get('id_user');


        if($willExpire) {

            $user = User::where('id','=',$willExpire[0]->id_user)->get();

            Mail::send(new SimpleMail(
                [
                    "to" => $user[0]->email,
                    "subject" =>"Votre annonce va s'expirer !",
                    "view" => "emails.WillExpire.fr",
                    "data" => [
                        "title" => "Votre annonce va s'expirer",
                        "username" => $user[0]->username,
                        "title" => $willExpire[0]->title,
                        ]
                    ]));
        }
    }
}
