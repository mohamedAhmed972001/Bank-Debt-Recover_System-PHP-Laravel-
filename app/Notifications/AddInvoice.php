<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddInvoice extends Notification
{
    use Queueable;
    private $invoice_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = 'http://127.0.0.1:8000/Invoices_Details/'.$this->invoice_id;
        return (new MailMessage)
                    ->greeting('ازيك يا معلم !')
                    ->subject('اصافة فاتورة جديدة') // عنوان المسدج من بره
                    ->line('انت كده قمت بإصافة فاتورة جديدة')
                    ->action('تقدر تشوفها من هنا', $url)
                    ->line('شكرا لاستخدامك نظامنا لإدارة الفواتير');
    }
    
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
