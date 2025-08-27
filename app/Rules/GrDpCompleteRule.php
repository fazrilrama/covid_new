<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\AdvanceNotice;
use App\StockTransport;

class GrDpCompleteRule implements Rule
{
    public $message = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'Failed to create Stock Transport';
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
        return $this->validate($an);

    }

    public function validate($an)
    {
        // search by AIN
        $st = StockTransport::where('advance_notice_id', $an->id)->whereIn('status', ['Pending', 'Processed'])->get();
        if($st->count()) {
            foreach($st as $row) {
                if($an->type == 'inbound') {
                    $this->message = 'ada GR dengan nomor ('.$row->code.') yang belum di complete. silahkan selesaikan terlebih dahulu aktifitas tersebut.';

                    return false;
                }
                else{
                    $this->message = 'ada DP dengan nomor ('.$row->code.') yang belum di complete. silahkan selesaikan terlebih dahulu aktifitas tersebut.';

                    return false;
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
