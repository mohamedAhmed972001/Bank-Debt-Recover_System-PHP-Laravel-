<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Add_invoice_new extends Notification
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
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'id'=> $this->invoice_id,
            'title'=>'تم اضافة فاتورة جديدة بواسطة :',
            'user'=> Auth::user()->name,
        ];
    }
    /*
    على الرغم من أن الحقول id, title, user 
    ليست أعمدة فعلية في جدول notifications،
    إلا أنها تُخزن ضمن عمود واحد في الجدول يسمى data 
    على شكل JSON.
    */

 

}
