<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Events\NotificationEvent;
use App\Lib\ApiResponser;

class TransactionsNotification extends Notification
{
    use Queueable;
    use ApiResponser;

    public $transactionData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transactionData)
    {
        $this->transactionData = $transactionData;

        $event = event(new NotificationEvent($transactionData['body'],auth()->user()->id,$transactionData['notification_flag']));
        if(!$event){
            return $this->errorResponse('Failed jobs',500);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'subject_id' => $this->transactionData['subject_id'],
            'body' => $this->transactionData['body'],
            'text' => $this->transactionData['text'],
            'url' => $this->transactionData['url'],
            'amount' => $this->transactionData['amount'],
            'notification_flag' => $this->transactionData['notification_flag'],
        ];
    }
}
