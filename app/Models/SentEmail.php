<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        "sent_by",
        "subject",
        "body",
        "emails",
        "cc",
        "attachment_path",
    ];

    public function sent()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, H:i A');
    }

}
