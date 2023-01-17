<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class SimpleMail extends Mailable
{
    use Queueable, SerializesModels;


    private Array $params = [];


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // from($this->params['from'])
        // to($this->params['to'])
        // cc($this->params['cc'])
        // bcc($this->params['bcc'])
        // subject($this->params['subject'])
        // view($this->params['view'], $this->params['data'])

        $mail = $this;

        if(Arr::has($this->params, 'from')){
            // check if from is a string
            if(is_string($this->params['from'])){
                $mail = $mail->from($this->params['from']);
            }else{
                $mail = $mail->from($this->params['from']['address'], $this->params['from']['name']);
            }
        }

        if(Arr::has($this->params, 'to')){
            // check if to is a string
            if(is_string($this->params['to'])){
                $mail = $mail->to($this->params['to']);
            }else{
                $mail = $mail->to($this->params['to']['address'], $this->params['to']['name']);
            }
        }

        if(Arr::has($this->params, 'cc')){
            // check if cc is a string
            if(is_string($this->params['cc'])){
                $mail = $mail->cc($this->params['cc']);
            }else{
                $mail = $mail->cc($this->params['cc']['address'], $this->params['cc']['name']);
            }
        }

        if(Arr::has($this->params, 'bcc')){
            // check if bcc is a string
            if(is_string($this->params['bcc'])){
                $mail = $mail->bcc($this->params['bcc']);
            }else{
                $mail = $mail->bcc($this->params['bcc']['address'], $this->params['bcc']['name']);
            }


        }

        if(Arr::has($this->params, 'subject')){
            $mail = $mail->subject($this->params['subject']);
        }

        if(Arr::has($this->params, 'view')){
            // check if data is exist
            if(Arr::has($this->params, 'data')){
                $mail = $mail->view($this->params['view'], $this->params['data']);
            }else{
                $mail = $mail->view($this->params['view']);
            }
        }
        else {
            // sed test message
            $mail = $mail->message('Test message');
        }

        return $mail;
    }
}
