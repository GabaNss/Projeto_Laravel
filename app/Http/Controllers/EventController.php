<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $eventsQuery = Event::withCount('users')
            ->orderBy('date')
            ->orderBy('created_at');

        if ($search) {
            $eventsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        return view('welcome', [
            'events' => $eventsQuery->get(),
            'search' => $search,
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();

        $events = $user->events()
            ->withCount('users')
            ->orderBy('date')
            ->orderBy('created_at')
            ->get();

        $eventsAsParticipant = $user->eventsAsParticipant()
            ->with('user')
            ->withCount('users')
            ->orderBy('date')
            ->orderBy('created_at')
            ->get();

        return view('dashboard', [
            'events' => $events,
            'eventsAsParticipant' => $eventsAsParticipant,
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
            'private' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'items' => 'nullable|array',
            'items.*' => 'string|max:255',
            'date' => 'required|date',
        ]);

        $imageName = $this->storeImage($request);

        Event::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'city' => $validated['city'],
            'private' => $validated['private'],
            'image' => $imageName,
            'items' => $validated['items'] ?? [],
            'date' => $validated['date'],
        ]);

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $event = Event::with('user')
            ->withCount('users')
            ->findOrFail($id);

        $hasUserJoined = Auth::check()
            ? $event->users()->where('users.id', Auth::id())->exists()
            : false;

        return view('events.show', [
            'event' => $event,
            'hasUserJoined' => $hasUserJoined,
        ]);
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        if (! $this->ownsEvent($event)) {
            return redirect('/dashboard')->with('msg', 'Você não pode editar este evento.');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if (! $this->ownsEvent($event)) {
            return redirect('/dashboard')->with('msg', 'Você não pode editar este evento.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
            'private' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'items' => 'nullable|array',
            'items.*' => 'string|max:255',
            'date' => 'required|date',
        ]);

        $imageName = $this->storeImage($request);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'city' => $validated['city'],
            'private' => $validated['private'],
            'image' => $imageName ?? $event->image,
            'items' => $validated['items'] ?? [],
            'date' => $validated['date'],
        ]);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (! $this->ownsEvent($event)) {
            return redirect('/dashboard')->with('msg', 'Você não pode excluir este evento.');
        }

        $event->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function joinEvent($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        $user->eventsAsParticipant()->syncWithoutDetaching([$event->id]);

        return redirect('/events/' . $event->id)->with('msg', 'Presença confirmada com sucesso!');
    }

    public function leaveEvent($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        $user->eventsAsParticipant()->detach($event->id);

        return redirect('/dashboard')->with('msg', 'Você saiu do evento com sucesso!');
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->extension();
        $destination = public_path('img/events');

        if (! is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $image->move($destination, $imageName);

        return $imageName;
    }

    private function ownsEvent(Event $event): bool
    {
        return (int) $event->user_id === (int) Auth::id();
    }
}
