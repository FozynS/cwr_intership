<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Models\PatientSms;
use App\Patient;
use Illuminate\Support\Facades\Log;


class SmsController extends Controller
{
  public function processSms(array $data)
  {
    $patientId = $this->getPatientIdByNumber($data['From']);

    if (!$patientId) {
      \Log::warning('Patient not found for SMS from: ' . $data['From']);
      return; 
    }

    $smsData = [
      'from_number' => $data['From'],  
      'to_number' => $data['To'],      
      'direction' => 'Inbound',        
      'message_body' => $data['Body'],
      'patient_id' => $patientId, 
    ];

    PatientSms::create($smsData);
  }

  private function getPatientIdByNumber($number)
  {
    $patient = Patient::where('cell_phone', $number)
      ->orWhere('home_phone', $number)
      ->orWhere('work_phone', $number)
      ->first();
    
    return $patient ? $patient->id : null;
  }

  public function getPhoneNumbers(Patient $patient)
  {
    return response()->json([
      'phoneNumbers' => [
        $patient->cell_phone,
        $patient->home_phone,
        $patient->work_phone,
      ],
    ]);
  }

  public function getSmsCount(Patient $patient): JsonResponse
  {
    if (!$patient) {
      return response()->json(['error' => 'Patient not found'], 404);
    }

    $smsCount = PatientSms::where('patient_id', $patient->id)->count();
    return response()->json(['count' => $smsCount]);
  }

  public function index(Patient $patient)
  {
    $messages = PatientSms::where('patient_id', $patient->id)
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    $formattedMessages = $messages->getCollection()->map(function ($message) {
      return array_merge($message->toArray(), [ 
        'author' => $message->user_id ? $message->user->name : $message->patient->name,
      ]);
    });

    $messages->setCollection($formattedMessages);

    return response()->json([
      'data' => $messages,
      'pagination' => [
        'current_page' => $messages->currentPage(),
        'last_page' => $messages->lastPage(),
      ],
    ]);
  }
  
  public function store(Request $request, Patient $patient)
  {
    $validated = $request->validate([
      'to_number' => 'required|string|max:15',
      'message' => 'required|string|max:1000',
    ]);

    $message = PatientSms::create([
      'from_number' => config('sms.company_number'),
      'to_number' => $validated['to_number'],
      'direction' => 'Outbound',
      'message_body' => $validated['message'],
      'user_id' => auth()->id(),
      'patient_id' => $patient->id,
    ]);
    
    return response()->json($message);
  }

  public function sendMessage(Request $request, Patient $patient)
  {
    $validated = $request->validate([
      'to_number' => 'required|string|max:15',
      'message' => 'required|string|max:1000',
    ]);

    try {
      $message = PatientSms::create([
        'from_number' => config('sms.company_number'),
        'to_number' => $validated['to_number'],
        'direction' => 'outbound',
        'message_body' => $validated['message'],
        'user_id' => auth()->id(),
        'patient_id' => $patient->id,
      ]);

      return response()->json($message);
    } catch (\Exception $e) {
      \Log::error('Error when sending a message: ' . $e->getMessage());
      return response()->json(['error' => 'Error when sending a message'], 500);
    }
  }

  public function loadMoreMessages(Patient $patient, $page)
  {
    if (!$patient) {
      return response()->json(['error' => 'Patient not found'], 404);
    }

    $messages = PatientSms::where('patient_id', $patient->id)
      ->orderBy('created_at', 'desc')
      ->paginate(15, ['*'], 'page', $page);

    return response()->json($messages);
  }
}
