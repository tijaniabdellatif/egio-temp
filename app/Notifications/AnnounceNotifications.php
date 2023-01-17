<?php
namespace App\Notifications;

use App\Events\NotificationEvent;
use App\Lib\ApiResponser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AnnounceNotifications extends Notification
{
    use ApiResponser;
    use Queueable;
    public $announceData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->announceData = $data;

        
        $event = event(new NotificationEvent($data['body'],auth()->user()->id,$data['notification_flag']));
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
    //         ->name($this->announceData['name'])
    //         ->line($this->announceData['body'])
    //         ->action($this->announceData['announceText'], $this->announceData['announceUrl'])
    //         ->line($this->announceData['thanks']);
    // }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         'subject_id' => $this->announceData['subject_id'],
    //         'body' => $this->announceData['body'],
    //         'text' => $this->announceData['text'],
    //         'url' => $this->announceData['url'],
    //         'notification_flag' => $this->announceData['notification_flag'],
    //         'stopNotify' => 'true'
    //     ];
    // }

    public function toDatabase($notifiable): array
    {
        return [
            'subject_id' => $this->announceData['subject_id'],
            'body' => $this->announceData['body'],
            'text' => $this->announceData['text'],
            'url' => $this->announceData['url'],
            'notification_flag' => $this->announceData['notification_flag'],
        ];
    }
}
