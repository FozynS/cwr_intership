<?php

namespace Tests\Feature;

use App\Contracts\Models\PatientSms;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientSmsWebhookTest extends TestCase
{
  /**
   * A basic test example.
   *
   * @return void
   */
  public function testExample()
  {
    $this->assertTrue(true);
  }

  public function testHandleSmsFromPatientCreatesNewPatientSmsRecord()
  {
    $existingPatientId = 2582;
    $patient = PatientSms::findOrFail($existingPatientId);

    $response = $this->post('/webhook/twilio/sms-to-therapist', [
      'From' => $patient->from_number,
      'Body' => 'Test message from patient'
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('patient_sms', [
      'from_number' => $existingPatientPhone,
      'message_body' => 'Test message from patient',
      'patient_id' => $existingPatientId
    ]);
  }

}
