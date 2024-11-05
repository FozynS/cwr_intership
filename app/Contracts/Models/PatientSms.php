<?php

namespace App\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Patient;
use App\User;

class PatientSms extends Model
{
  const SMS_DIRECTION_INBOUND_ID = 1;
  const SMS_DIRECTION_OUTBOUND_ID = 2;

  protected $fillable = [
    'from_number',
    'to_number',
    'direction',
    'message_body',
    'user_id', 
    'patient_id',
    'is_read',
    'is_archived',
  ];

  public static function countUnread() 
  {
    return self::where('direction', '1')->where('is_read', false)->count();
  }

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}