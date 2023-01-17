<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\SimpleMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ListCoinsRappel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ListCoinRappel:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email when Listcoins of users are below 500';

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
        $users = User::Select('email', 'username', 'coins')->where('coins', '<', '500')->pluck('email');

        foreach ($users as $user) {
            Mail::send(new SimpleMail(
                [
                    "to" => $user->email,
                    "subject" => "Penser à recharger votre ListCoins !",
                    "view" => "emails.ListCoinsBelow.fr",
                    "data" => [
                        "title" => "Votre solde ListCoins est moins de 500",
                        "username" => $user->username,
                        "coins" => $user->coins,
                    ]
                ]
            ));
        }
    }
}
