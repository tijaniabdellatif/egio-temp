<?php

namespace App\Jobs;

use App\Models\email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(email $email , $to)
    {
        $this->email = $email;
        $this->email->to = $to;
    }

    // public function __construct($view, $data , $toEmail , $from = "Multilist", $fromEmail = "" )
    // {
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // send mail
        Mail::to($this->email->to)
            ->send(new \App\Mail\SendMail($this->email));
    }

}
