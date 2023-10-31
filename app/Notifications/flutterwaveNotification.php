<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\Admin\Currency;
use App\Models\ParlourBooking;
use App\Models\Admin\ParlourList;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use Illuminate\Notifications\Notification;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Notifications\Messages\MailMessage;

class flutterwaveNotification extends Notification
{
    use Queueable;
    public $user;
    public $data;
    public $trx_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $data, $trx_id)
    {
        $this->user = $user;
        $this->data = $data;
        $this->trx_id = $trx_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user                   = $this->user;
        $data                   = $this->data;
        $user_data              = ParlourBooking::where('slug',$data['tempData']['data']->user_record->slug ?? "")->first();
        $parlour_data           = ParlourList::where('id',$user_data->parlour_id)->first();
        $schedule_data          = ParlourListHasSchedule::where('id',$user_data->schedule_id)->first();
        $payment_method         = PaymentGatewayCurrency::where('id',$user_data->payment_gateway_currency_id)->first();
        $trx_id                 = $this->trx_id;
        $basic_settings         = BasicSettingsProvider::get();
        $contact_section_slug   = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_section_slug)->first();
        $currency               = Currency::where('default',true)->first();

        return (new MailMessage)
            ->subject("Your Parlour Booking - MTCN: ". $trx_id)
            ->view('frontend.email.confirmation', [
                'user_data'         => $user_data,
                'parlour_data'      => $parlour_data,
                'schedule_data'     => $schedule_data,
                'payment_method'    => $payment_method,
                'data'              => $data,
                'user'              => $user,
                'trx_id'            => $trx_id,
                'contact'           => $contact,
                'basic_settings'    => $basic_settings,
                'currency'          => $currency,

            ]);
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
