<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Provider\PatientSms\PatientSmsRepositoryInterface;
use App\Http\Requests\PatientSms\PatientSmsRequest;
use App\Http\Requests\PatientSms\UpdateSmsStatusRequest;
use App\Patient;

class SmsController extends Controller
{

  /**
   * @var PatientSmsRepositoryInterface
   */
    private $smsRepository;
    
  /**
   * @param PatientSmsRepositoryInterface $smsRepository
   */

  public function __construct(PatientSmsRepositoryInterface $smsRepository)
  {
    $this->smsRepository = $smsRepository;
  }

  public function getPhoneNumbers(Patient $patient): JsonResponse
  {
    $phoneNumbers = $this->smsRepository->getPhoneNumbers($patient);
    return response()->json(['phoneNumbers' => $phoneNumbers]);
  }

  public function getSmsCount(Patient $patient): JsonResponse
  {
    $smsCount = $this->smsRepository->getSmsCount($patient);
    return response()->json(['count' => $smsCount]);
  }

  public function getAllSms(PatientSmsRequest $request) : JsonResponse 
  {
    $allMessages = $this->smsRepository->getAllMessages($request);

    if ($allMessages->isEmpty()) {
      return response()->json(['message' => 'No messages found'], 404);
    }
    
    return response()->json($allMessages);
  }

  public function unreadCount ()
  {
    return $this->smsRepository->smsUnreadCount();
  }

  public function updateReadStatus (UpdateSmsStatusRequest $request)
  {
    return $this->smsRepository->updateSmsListReadStatus($request);
  }

  public function updateUnreadStatus (UpdateSmsStatusRequest $request)
  {
    return $this->smsRepository->updateSmsListUnreadStatus($request);
  }
  public function updateArchivedStatus (UpdateSmsStatusRequest $request)
  {
    return $this->smsRepository->updateSmsListArchivedStatus($request);
  }

  public function index(Patient $patient): JsonResponse
  {
    $messages = $this->smsRepository->getMessages($patient);
    return response()->json($messages);
  }

  public function store(Request $request, Patient $patient): JsonResponse
  {
    $validated = $request->validate([
      'to_number' => 'required|string|max:15',
      'message' => 'required|string|max:1000',
    ]);

    $message = $this->smsRepository->storeSms($validated, $patient);
    return response()->json($message);
  }

  public function sendMessage(Request $request, Patient $patient): JsonResponse
  {
    $validated = $request->validate([
      'to_number' => 'required|string|max:15',
      'message' => 'required|string|max:1000',
    ]);

    try {
      $message = $this->smsRepository->sendMessage($validated, $patient);
      return response()->json($message);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error when sending a message'], 500);
    }
  }

  public function loadMoreMessages(Patient $patient, $page): JsonResponse
  {
    $messages = $this->smsRepository->loadMoreMessages($patient, $page);
    return response()->json($messages);
  }
}