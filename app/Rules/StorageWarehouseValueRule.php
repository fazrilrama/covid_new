<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StorageWarehouseValueRule implements Rule
{
    protected $warehouse, $key, $custom_key;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($warehouse, $custom_key = NULL)
    {
        $this->warehouse = $warehouse;
        $this->custom_key = $custom_key;
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
        $this->key = $attribute;

        if(!is_null($this->custom_key)) {
            $this->key = $this->custom_key;
        }
        
        if($value > $this->warehouse->{$this->key}) {
            return false;
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
        return "{$this->key} value storage cant be greater than warehouse {$this->key} itself";
    }
}
