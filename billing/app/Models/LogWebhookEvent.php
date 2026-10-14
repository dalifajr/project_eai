<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogWebhookEvent extends Model
{
    protected $table = 'log_webhook_event';

    protected $fillable = [
        'event_name',
        'target_url',
        'payload',
        'response_code',
        'response_body',
        'is_success',
    ];
}
