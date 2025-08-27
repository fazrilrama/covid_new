<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\AdvanceNotice;
use App\StockTransport;
use App\StockEntry;
use App\StockDelivery;

class AinAonClosingRule implements Rule
{
    public $message = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'Failed to close AIN/AON';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $an = AdvanceNotice::find($value);

        // validate ain
        if($an->type == 'inbound') {
           return $this->validateAin($an);
        } else {
            return $this->validateAon($an);
        }

        return true;

    }

    public function validateAin($an)
    {
        // search by AIN
        $st = StockTransport::where('advance_notice_id', $an->id)->whereIn('status', ['Pending', 'Processed', 'Completed'])->get();
        if($st->count()) {
            foreach($st as $row) {
                if(in_array($row->status, ['Pending', 'Processed'])) {
                    $this->message = 'GR dalam proses (' . $row->code . ') silahkan selesaikan terlebih dahulu aktifitas tersebut.';
                    return false;
                } elseif (in_array($row->status, ['Completed'])) {
                    // search by GR
                    $se = StockEntry::where('stock_transport_id', $row->id)->whereIn('status', ['Completed', 'Processed'])->first();
                    
                    if($se) {
                        if($se->status == 'Processed') {
                            $this->message = 'PA dalam proses ('. $se->code .') silahkan selesaikan terlebih dahulu aktifitas tersebut.'; return false;
                        }
                    } else {
                        $this->message = 'PA belum dibuat untuk GR ('. $row->code .')'; return false;
                    }
                }
            }
        }

        return true;
    }

    public function validateAon($an)
    {
        $st = StockTransport::where('advance_notice_id', $an->id)->whereIn('status', ['Pending', 'Processed', 'Completed'])->get();
        if($st->count()) {
            foreach($st as $row) {
                if(in_array($row->status, ['Pending', 'Processed'])) {
                    $this->message = 'DP dalam proses (' . $row->code . ') silahkan selesaikan terlebih dahulu aktifitas tersebut.';
                    return false;
                } elseif (in_array($row->status, ['Completed'])) {
                    // search by DP
                    $se = StockEntry::where('stock_transport_id', $row->id)->whereIn('status', ['Completed', 'Processed'])->first();
                    
                    if($se) {
                        if($se->status == 'Processed') {
                            $this->message = 'PP dalam proses ('. $se->code .') silahkan selesaikan terlebih dahulu aktifitas tersebut.'; return false;
                        } elseif($se->status == 'Completed') {
                            $sd = StockDelivery::where('stock_entry_id', $se->id)->whereIn('status', ['Completed', 'Processed'])->first();

                            if($sd) {
                                if($sd->status == 'Processed') {
                                    $this->message = 'GI dalam proses ('. $sd->code .')'; return false;
                                }
                            } else {
                                $this->message = 'GI belum dibuat untuk PP ('. $se->code .')'; return false;
                            }

                        }
                    } else {
                        $this->message = 'PP belum dibuat untuk DP ('. $row->code .')'; return false;
                    }
                }
            }
        }

        return true;   
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
