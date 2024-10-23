<?php

namespace App\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Patient;
use App\User;

class PatientSms extends Model
{
  protected $fillable = [
    'from_number',
    'to_number',
    'direction',
    'message_body',
    'user_id', 
    'patient_id',
  ];

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}