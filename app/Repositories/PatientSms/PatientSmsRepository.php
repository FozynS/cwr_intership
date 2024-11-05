<?php

namespace App\Repositories\PatientSms;

use App\Patient;
use App\Contracts\Models\PatientSms;
use App\Http\Requests\PatientSms\PatientSmsRequest;
use App\Repositories\Provider\PatientSms\PatientSmsRepositoryInterface;
use App\Http\Requests\PatientSms\UpdateSmsStatusRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PatientSmsRepository implements PatientSmsRepositoryInterface
{

  public function getPatientIdByNumber(string $number)
  {
    $patient = Patient::where('cell_phone', $number)
      ->orWhere('home_phone', $number)
      ->orWhere('work_phone', $number)
      ->first();

    return $patient ? $patient->id : null;
  }

  public function getPhoneNumbers(Patient $patient)
  {
    return [
      $patient->cell_phone,
      $patient->home_phone,
      $patient->work_phone,
    ];
  }

  public function getSmsCount(Patient $patient): int
  { 
    return PatientSms::where('patient_id', $patient->id)->count();
  }

  public function getMessages(Patient $patient, int $page = 1)
  {
    $messages = PatientSms::where('patient_id', $patient->id)
      ->orderBy('created_at', 'desc')
      ->paginate(15, ['*'], 'page', $page);


    $formattedMessages = $messages->getCollection()->map(function ($message) {
      return array_merge($message->toArray(), [ 
        'author' => $message->user_id ? $message->user->name : $message->patient->name,
      ]);
    });

    $messages->setCollection($formattedMessages);

    return $messages;
  }

  public function getAllMessages(PatientSmsRequest $request, int $page = 1)
  {
    $unread = $request->input('unread');
    $archived = $request->input('archived');

    $query = PatientSms::orderBy('created_at', 'desc');

    if ($unread !== null) {
      $query->where('is_read', $unread);
    }

    if ($archived !== null) {
      $query->where('is_archived', $archived);
    }

    $messages = $query->paginate(15, ['*'], 'page', $page);

    $formattedMessages = $messages->getCollection()->map(function ($message) {
      return array_merge($message->toArray(), [ 
        'author' => $message->user_id ? $message->user->name : $message->patient->name,
      ]);
    });

    $messages->setCollection($formattedMessages);
    return $messages;
  }

  public function smsUnreadCount() 
  {
    return PatientSms::where('direction', '1')->where('is_read', false)->count();
  }

  public function updateSmsListReadStatus(UpdateSmsStatusRequest $request) 
  {
    $sms = $request->input('sms_id');
    PatientSms::whereIn('id', $sms)->update(['is_read' => true]);
    foreach ($sms as $smsId) {
      $smsData = PatientSms::where("id", $smsId)->first();
    }
    return new JsonResponse(['message' => "you have changed status 'is_read' to read status", 'status' => JsonResponse::HTTP_OK]);
  }
  
  public function updateSmsListUnreadStatus(UpdateSmsStatusRequest $request) 
  {
    $sms = $request->input('sms_id');
    PatientSms::whereIn('id', $sms)->update(['is_read' => false]);
    foreach ($sms as $smsId) {
      $smsData = PatientSms::where("id", $smsId)->first();
    }
    return new JsonResponse(['message' => "you have changed status 'is_read' to unread status", 'status' => JsonResponse::HTTP_OK]);
  }
  
  public function updateSmsListArchivedStatus(UpdateSmsStatusRequest $request) 
  {
    $smsIds = $request->input('sms_id');
    foreach ($smsIds as $smsId) {
      $sms = PatientSms::find($smsId);

      if ($sms) {
        $sms->is_archived = !$sms->is_archived;
        $sms->save();
      }
    }

    return new JsonResponse(['message' => "you have changed status 'is_archived' to differente status", 'status' => JsonResponse::HTTP_OK]);
  }

  public function storeSms(array $data, Patient $patient)
  {
    $smsData = [
      'from_number' => config('sms.company_number'),
      'to_number' => $data['to_number'],
      'direction' => PatientSms::SMS_DIRECTION_OUTBOUND_ID,
      'message_body' => $data['message'],
      'user_id' => auth()->id(),
      'patient_id' => $patient->id,
    ];

    return PatientSms::create($smsData);
  }

  public function sendMessage(array $data, Patient $patient)
  {
    try {
      return $this->storeSms($data, $patient);
    } catch (\Exception $e) {
      Log::error('Error when sending a message: ' . $e->getMessage());
      throw $e;
    }
  }

  public function loadMoreMessages(Patient $patient, int $page)
  {
    return $this->getMessages($patient, $page);
  }
}
