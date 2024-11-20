<?php

namespace App\Http\Controllers\Webhooks\PatientSms;

use App\Patient;
use App\Contracts\Models\PatientSms;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use App\Events\SmsReceived;
use Illuminate\Http\Request;

class SmsFromPatientWebhookController extends Controller 
{
  protected $twilio;

  public function __construct()
  {
    $this->twilio = new Client(config('sms.twilio.sid'), config('sms.twilio.token'));
  }

  public function handleSmsFromPatient(Request $request)
  {
    $data = $request->all();

    $fromNumber = $data['From'];
    $body = $data['Body'];

    $patientSms = Patient::where('cell_phone', $fromNumber)->first();
    if ($patientSms) {
      $patientId = $patientSms->id;

      $patientSmsData = $this->savePatientSms($patientId, $fromNumber, $body);

      event(new SmsReceived(
        $patientId,
        $fromNumber,
        $body,
        $patientSmsData->to_number,
        $patientSmsData->direction,
        $patientSmsData->is_read,
        $patientSmsData->is_archived,
        $patientSmsData->created_at
      ));

      $allMessageByPatientId = PatientSms::where('patient_id', $patientId)
        ->whereNotNull('user_id')
        ->distinct()
        ->pluck('user_id');

    foreach ($allMessageByPatientId as $userId) {
        $this->sendSmsToTherapist($userId);
  }

      return response('Send notification to all therapists', 200);
    }

    return response('Patient not found.', 404);
  }

  private function sendSmsToTherapist($userId)
  {
    $user = PatientSms::find($userId);
    if ($user && $user->from_number) {
      $this->twilio->messages->create(
        $user->from_number,
        [
          'from' => config('sms.twilio.from'),
          'body' => 'You have received a new SMS message from one of your patients. Please log in to CWR EHR to view.'
        ]
      );
    }
  }

  private function savePatientSms($patientId, $fromNumber, $body)
  {
    $createdAt = now()->format('Y-m-d H:i:s');

    return PatientSms::create([
      'from_number' => $fromNumber,
      'to_number' => config('sms.twilio.from'),
      'direction' => PatientSms::SMS_DIRECTION_INBOUND_ID,
      'message_body' => $body,
      'patient_id' => $patientId,
      'is_read' => false,
      'is_archived' => false,
      'created_at' => $createdAt,
    ]);
  }
}
