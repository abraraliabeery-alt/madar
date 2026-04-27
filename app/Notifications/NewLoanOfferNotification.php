<?php

namespace App\Notifications;

use App\Models\LoanRequest;
use App\Models\LoanOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLoanOfferNotification extends Notification
{
    use Queueable;

    protected LoanRequest $loanRequest;
    protected LoanOffer $loanOffer;

    public function __construct(LoanRequest $loanRequest, LoanOffer $loanOffer)
    {
        $this->loanRequest = $loanRequest;
        $this->loanOffer = $loanOffer;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'loan_offer_created',
            'loan_request_id' => $this->loanRequest->id,
            'loan_offer_id' => $this->loanOffer->id,
            'amount' => $this->loanOffer->amount,
            'profit_rate' => $this->loanOffer->profit_rate,
            'term_months' => $this->loanOffer->term_months,
            'message' => 'تم إضافة عرض تمويل جديد على طلبك.',
        ];
    }
}
