<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TaxPaymentSucceeded
{
    use Dispatchable, SerializesModels;

    public $tax;

    /**
     * @param $tax
     */
    public function __construct($tax)
    {
        $this->tax = $tax;
    }
}
