<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EventWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_owned_event(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/events', [
            'title' => 'Feira de Tecnologia',
            'description' => 'Evento aberto para a comunidade.',
            'city' => 'Piracicaba',
            'private' => '0',
            'items' => ['Cadeiras', 'Palco'],
            'date' => '2026-07-10',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('events', [
            'title' => 'Feira de Tecnologia',
            'user_id' => $user->id,
        ]);
    }

    public function test_dashboard_lists_owned_and_joined_events(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownedEvent = $this->createEvent([
            'user_id' => $user->id,
            'title' => 'Meu Evento',
        ]);

        $joinedEvent = $this->createEvent([
            'user_id' => $otherUser->id,
            'title' => 'Evento Inscrito',
        ]);

        $user->eventsAsParticipant()->attach($joinedEvent->id);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($ownedEvent->title)
            ->assertSee($joinedEvent->title);
    }

    public function test_user_can_join_once_and_leave_event(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $event = $this->createEvent(['user_id' => $owner->id]);

        $this->actingAs($user)->post(route('events.join', $event->id))->assertRedirect(route('events.show', $event->id, false));
        $this->actingAs($user)->post(route('events.join', $event->id))->assertRedirect(route('events.show', $event->id, false));

        $this->assertSame(1, DB::table('event_user')
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->count());

        $this->actingAs($user)->delete(route('events.leave', $event->id))->assertRedirect('/dashboard');

        $this->assertDatabaseMissing('event_user', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_non_owner_cannot_edit_update_or_delete_event(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = $this->createEvent([
            'user_id' => $owner->id,
            'title' => 'Evento Protegido',
        ]);

        $this->actingAs($otherUser)
            ->get(route('events.edit', $event->id))
            ->assertRedirect('/dashboard');

        $this->actingAs($otherUser)->put(route('events.update', $event->id), [
            'title' => 'Evento Alterado',
            'description' => 'Tentativa de alteração.',
            'city' => 'Campinas',
            'private' => '1',
            'date' => '2026-08-12',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Evento Protegido',
        ]);

        $this->actingAs($otherUser)
            ->delete(route('events.destroy', $event->id))
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

    private function createEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Evento Teste',
            'description' => 'Descrição do evento de teste.',
            'city' => 'Piracicaba',
            'private' => false,
            'items' => ['Cadeiras'],
            'date' => '2026-07-10',
        ], $overrides));
    }
}
