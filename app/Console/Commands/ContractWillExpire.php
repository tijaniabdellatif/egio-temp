<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SimpleMail;
use App\Models\Contract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ContractWillExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ContractWillExpire:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email when contract will expire';

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
        $this->daysleft = Carbon::now()->addDays(7);
        $contractDate = Contract::all('date', 'duration', 'user_id');

        $expiredate = [];

        foreach ($contractDate as $contract) {
            array_push($expiredate, $date = Carbon::parse($contract->date)->addDays($contract->duration));
        }

        foreach ($expiredate as  $exp) {
            if ($this->daysleft <= $exp) {
                $user_id =  $contract->user_id;
                $user_email = User::select('email', 'username')->where('id', '=', $user_id)->get();
                Mail::send(new SimpleMail(
                    [
                        "to" => $user_email[0]->email,
                        "subject" => "Votre contrat va s'expirer !",
                        "view" => "emails.ContractWillExpire.fr",
                        "data" => [
                            "title" => "Votre contrat va s'expirer",
                            "username" => $user_email[0]->username,
                            "daysLeft" => $this->daysLeft,
                            "date" => $contract->date,
                            "duration" => $contract->duration,
                            "reference" => uniqid(),
                        ]
                    ]
                ));
            }
        }
    }
}
