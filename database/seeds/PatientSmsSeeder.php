<?php

use Illuminate\Database\Seeder;
use App\Patient;
use App\Contracts\Models\PatientSms;
use App\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PatientSmsSeeder extends Seeder
{
  public function run()
  {
    $faker = Faker::create();
    $companyNumber = config('sms.company_number');
    $patient = Patient::find(2582);
    $users = User::inRandomOrder()->take(3)->get();

    $messagesPerDay = [
      'five_days_ago' => 4,
      'four_days_ago' => 4,
      'three_days_ago' => 6,
      'day_before_yesterday' => 4,
      'yesterday' => 2,
    ];

    $dates = [
      Carbon::now()->subDays(5),
      Carbon::now()->subDays(4),
      Carbon::now()->subDays(3),
      Carbon::now()->subDays(2),
      Carbon::now()->subDays(1),
    ];

    foreach ($messagesPerDay as $daysAgo => $count) {
      $dateIndex = array_search($daysAgo, array_keys($messagesPerDay));
      $date = $dates[$dateIndex];

      $outboundCount = ceil($count / 2);
      $inboundCount = floor($count / 2);

      for ($i = 0; $i < $outboundCount; $i++) {
        PatientSms::create([
          'from_number' => $companyNumber,
          'to_number' => $patient->cell_phone,
          'direction' => 'Outbound',
          'message_body' => $faker->paragraph(rand(1, 3)),
          'user_id' => $users->random()->id,
          'patient_id' => $patient->id,
          'created_at' => $date,
          'updated_at' => $date,
        ]);
      }

      for ($i = 0; $i < $inboundCount; $i++) {
        PatientSms::create([
          'from_number' => $patient->cell_phone,
          'to_number' => $companyNumber,
          'direction' => 'Inbound',
          'message_body' => $faker->sentence(rand(5, 15)),
          'user_id' => null,
          'patient_id' => $patient->id,
          'created_at' => $date,
          'updated_at' => $date,
        ]);
      }
    }
  }
}
