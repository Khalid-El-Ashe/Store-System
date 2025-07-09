<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

    // تستخدم هذه الدالة لتحديد القنواتي التي نريد ان نرسل لها الاشعار
    // in here add your channels
    public function via(object $notifiable): array
    {
        // you can to return more or some channels
        return ['mail', 'database', 'broadcast'];

        $channels = ['database']; // array of channels is a default database channel

        if ($notifiable->notification_preferances['order_created']['sms'] ?? false) {
            $channels[] = 'vonage';
        }
        if ($notifiable->notification_preferances['order_created']['mail'] ?? false) {
            $channels[] = 'mail';
        }
        if ($notifiable->notification_preferances['order_created']['broadcast'] ?? false) {
            $channels[] = 'broadcast';
        }

        return $channels; // i need to return my channels
    }

    /**
     * Get the mail representation of the notification.
     */
    // this is a build method channel
    // هنا يتم تكوين الرسالة المردودة او الراجعة
    // هذه عبارة عن اشعار
    public function toMail(object $notifiable): MailMessage
    {
        // i need to get the user information from relation
        $addr = $this->order->billingAddress;


        $name = $addr?->name ?? 'Unknown';
        $country = $addr?->country_name ?? 'Unknown Country';

        return (new MailMessage)
            ->subject("New Order #{$this->order->number}")
            ->from("developerKhalid@gmail.com", "Store System") // in here you can to add the sender notification هنا يتم تحديد المرسل
            ->greeting("Hi {$notifiable->name},") // دالة الترحيب
            ->line("Create new Order #{$this->order->number} by. by {$name} from {$country}") // the name and country_name is the accessoure function from the OrderAddress Model class
            ->action('View Order', url('/dashboard'))
            ->line('Thank you for using our application!');
            // ->view(''); // هان طبعا ممكن تستدعي التصميم او الواجهة تعتك اللي انت راح تعملها
    }

    // in here i need to build my channels method
    public function toVonage(object $notifiable) // this method for sms
    {}
    public function toDatabase(object $notifiable) {
        $addr = $this->order->billingAddress;

        $name = $addr?->name ?? 'Unknown';
        $country = $addr?->country_name ?? 'Unknown Country';
        // i need to return my database
        return [
            "body" => "Create new Order #{$this->order->number} by. by {$name} from {$country}",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ];
    }
    public function toBroadcast(object $notifiable) {
        $addr = $this->order->billingAddress;

        $name = $addr?->name ?? 'Unknown';
        $country = $addr?->country_name ?? 'Unknown Country';
        // i need to return my data
        return new BroadcastMessage( [
            "body" => "Create new Order #{$this->order->number} by. by {$name} from {$country}",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ]);
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
