<?php

namespace App\Http\Controllers\Webhooks\PatientSms;

use App\Contracts\Models\PatientSms;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SmsFromPatientWebhookController extends Controller 
{
  protected $twilio;

  public function __construct()
  {
    $this->twilio = new Client(config('sms.twilio.sid'), config('services.twilio.token'));
  }

  public function handleSmsFromPatient(Request $request)
  {
    $fromNumber = $request->input('From');
    $body = $request->input('Body');

    $patientSms = PatientSms::where('from_number', $fromNumber)->first();

    if ($patientSms) {
      $patientId = $patientSms->patient_id;

      $this->savePatientSms($patientId, $fromNumber, $body);

      $therapistIds = PatientSms::where('patient_id', $patientId)
        ->whereNotNull('user_id')
        ->distinct()
        ->pluck('user_id');

      foreach ($therapistIds as $therapistId) {
        $this->sendSmsToTherapist($therapistId);
      }

      return response('Send notification to all therapists', 200);
    }

    return response('Patient not found.', 404);
  }

  private function sendSmsToTherapist($therapistId)
  {
    $therapist = PatientSms::find($therapistId);

    if ($therapist && $therapist->from_number) {
      $this->twilio->messages->create(
        $therapist->from_number,
        [
          'from' => config('sms.twilio.from'),
          'body' => 'You have received a new SMS message from one of your patients. Please log in to CWR EHR to view.'
        ]
      );
    }
  }

  private function savePatientSms($patientId, $fromNumber, $body)
  {
    return PatientSms::create([
      'from_number' => $fromNumber,
      'to_number' => config('sms.twilio.from'),
      'message_body' => $body,
      'patient_id' => $patientId,
      'is_read' => false,
      'is_archived' => false,
    ]);
  }

}