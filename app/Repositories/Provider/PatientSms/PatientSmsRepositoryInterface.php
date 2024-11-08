<?php

namespace App\Repositories\Provider\PatientSms;

use App\Patient;
use App\Http\Requests\PatientSms\PatientSmsRequest;
use App\Http\Requests\PatientSms\UpdateSmsStatusRequest;

interface PatientSmsRepositoryInterface
{
  public function getPhoneNumbers(Patient $patient);

  public function getSmsCount(Patient $patient): int;

  public function getAllMessages(PatientSmsRequest $request, int $page = 1);

  public function smsUnreadCount();

  public function updateSmsListReadStatus(UpdateSmsStatusRequest $request);

  public function updateSmsListUnreadStatus(UpdateSmsStatusRequest $request);

  public function updateSmsListArchivedStatus(UpdateSmsStatusRequest $request);

  public function getMessages(Patient $patient, int $page = 1);

  public function storeSms(array $data, Patient $patient);

  public function sendMessage(array $data, Patient $patient);

  public function loadMoreMessages(Patient $patient, int $page);
}
