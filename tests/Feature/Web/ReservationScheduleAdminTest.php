<?php

namespace Tests\Feature\Web;

use App\Models\ReservationSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationScheduleAdminTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::create([
            'email' => fake()->unique()->safeEmail(),
            'password_recovery_id' => 1,
            'password' => bcrypt('password'),
        ]);
    }

    private function nextWeekdayDate(): string
    {
        $date = Carbon::now()->addDay();
        while (! in_array($date->dayOfWeekIso, [1, 2, 4, 5])) {
            $date->addDay();
        }

        return $date->format('Y-m-d');
    }

    public function test_admin_can_view_reservation_create_page(): void
    {
        $response = $this->actingAs($this->createAdminUser())
            ->get(route('admin.reservation.create'));

        $response->assertStatus(200);
        $response->assertSee('Form Penambahan Reservasi');
    }

    public function test_admin_can_store_reservation_schedule(): void
    {
        $date = $this->nextWeekdayDate();

        $response = $this->actingAs($this->createAdminUser())
            ->post(route('admin.reservation.store'), [
                'date' => $date,
                'shift' => '09:00',
                'requested_by' => 'Budi Santoso',
                'meeting_room' => 'Ruang Rapat 1',
                'study_program' => 'S1 Informatika',
                'participants' => '5 Orang',
                'agenda' => 'Koordinasi Kurikulum',
                'city' => 'Bandung',
            ]);

        $response->assertRedirect(route('admin.reservation'));
        $response->assertSessionHas('success', 'Reservasi berhasil dibuat.');

        $this->assertDatabaseHas('reservation_schedules', [
            'date' => $date,
            'shift' => '09:00:00',
            'requested_by' => 'Budi Santoso',
        ]);
    }

    public function test_admin_can_view_reservation_edit_page(): void
    {
        $date = $this->nextWeekdayDate();
        $schedule = ReservationSchedule::create([
            'date' => $date,
            'shift' => '13:00:00',
            'requested_by' => 'Siti Nurhaliza',
        ]);

        $response = $this->actingAs($this->createAdminUser())
            ->get(route('admin.reservation.edit', $schedule->id));

        $response->assertStatus(200);
        $response->assertSee('Form Pengeditan Reservasi');
        $response->assertSee('Siti Nurhaliza');
    }

    public function test_admin_can_update_reservation_schedule_without_validation_method_error(): void
    {
        $date = $this->nextWeekdayDate();
        $schedule = ReservationSchedule::create([
            'date' => $date,
            'shift' => '13:00:00',
            'requested_by' => 'Siti Nurhaliza',
            'meeting_room' => 'Ruang 101',
            'study_program' => 'S1 Informatika',
            'agenda' => 'Diskusi Awal',
        ]);

        $response = $this->actingAs($this->createAdminUser())
            ->put(route('admin.reservation.update', $schedule->id), [
                'date' => $date,
                'shift' => '13:00',
                'requested_by' => 'Siti Nurhaliza Updated',
                'meeting_room' => 'Ruang 102',
                'study_program' => 'S1 Informatika',
                'agenda' => 'Diskusi Lanjutan',
            ]);

        $response->assertRedirect(route('admin.reservation'));
        $response->assertSessionHas('success', 'Reservasi berhasil diperbarui.');

        $schedule->refresh();
        $this->assertSame('Siti Nurhaliza Updated', $schedule->requested_by);
        $this->assertSame('Ruang 102', $schedule->meeting_room);
        $this->assertSame('Diskusi Lanjutan', $schedule->agenda);
    }

    public function test_admin_can_delete_reservation_schedule(): void
    {
        $date = $this->nextWeekdayDate();
        $schedule = ReservationSchedule::create([
            'date' => $date,
            'shift' => '15:00:00',
            'requested_by' => 'Ahmad Dahlan',
        ]);

        $response = $this->actingAs($this->createAdminUser())
            ->delete(route('admin.reservation.destroy', $schedule->id));

        $response->assertRedirect(route('admin.reservation'));
        $response->assertSessionHas('success', 'Reservasi berhasil dihapus.');

        $this->assertDatabaseMissing('reservation_schedules', [
            'id' => $schedule->id,
        ]);
    }
}
