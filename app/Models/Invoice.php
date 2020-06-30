<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * Class Invoice
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property float $amount
 * @property int $status
 * @property string $description
 */
class Invoice extends Model
{
    use Notifiable;

    public const STATUS_SENT = 0;
    public const STATUS_RECEIVED = 1;

    protected static function booted()
    {
        static::creating(static function ($model) {
            $model->status = static::STATUS_SENT;
        });
    }

    protected $fillable = [
        'name', 'email', 'amount', 'status', 'description',
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @param  Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return [$this->email => $this->name];
    }

    /**
     * Get the log records associated with the invoice
     */
    public function logs()
    {
        return $this->hasMany(MerchantLog::class);
    }
}
