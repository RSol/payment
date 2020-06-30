<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantLog extends Model
{
    public const STATUS_NEW = 0;
    public const STATUS_DONE = 1;
    public const STATUS_CANCEL = -1;

    public const SYSTEM_YANDEX = 'Yandex';

    protected static function booted()
    {
        static::creating(static function ($model) {
            $model->status = static::STATUS_NEW;
        });
    }

    /**
     * Get the invoice record associated with the log
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
