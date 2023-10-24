<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class websiteSubscribeNotification extends Notification
{
    use Queueable;

    protected $form_data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $form_data)
    {
        $this->form_data = $form_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = $this->form_data['subject'];
        $message = $this->form_data['message'];
        
        return (new MailMessage)
                    ->greeting($subject)
                    ->subject($subject)
                    ->line(new HtmlString($message));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}