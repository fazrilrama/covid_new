<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Warehouse;
use App\Storage;

class StorageCodeValidationRule implements Rule
{
    protected $warehouse_id, $ignore_id, $new_code;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($warehouse_id, $ignore_id = NULL)
    {
        $this->warehouse_id = $warehouse_id;
        $this->ignore_id = $ignore_id;
        $this->new_code = NULL;
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
        $wh = Warehouse::find($this->warehouse_id);
        
        $storage = Storage::where('code', $value)->where('warehouse_id', $wh->id);

        if($this->ignore_id) {
            $storage = $storage->where('id', '!=', $this->ignore_id)->where('code', $value)->where('warehouse_id', $wh->id);
        }

        $storage = $storage->count();

        if($storage > 0) {
            return false;
        }

        $this->new_code = $value;

        return true;
        // code and warehouse code
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry this storage code is already taken.';
    }

    public function getNewCode()
    {
        return $this->new_code;
    }
}
