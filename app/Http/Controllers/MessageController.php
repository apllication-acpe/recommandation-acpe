<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageReceived;

class MessageController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $receivedMessages = $user->receivedMessages()->with('sender')->latest()->paginate(10);
        $sentMessages = $user->sentMessages()->with('receiver')->latest()->paginate(10);

        return view('messagerie.index', compact('receivedMessages', 'sentMessages'));
    }

    public function show(Message $message)
    {
        $this->authorizeAccess($message);

        if (Auth::id() === $message->receiver_id && !$message->isRead()) {
            $message->markAsRead();
        }

        return view('messagerie.show', compact('message'));
    }

    public function create(Request $request)
    {
        $receiverId = $request->query('receiver_id');
        $offreId = $request->query('offre_id');
        $receiver = $receiverId ? User::findOrFail($receiverId) : null;

        return view('messagerie.create', compact('receiver', 'offreId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'contenu' => 'required|string',
            'objet' => 'required|string|max:255',
            'id_offre' => 'nullable|exists:offres,id_offre',
            'piece_jointe' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'contenu' => $request->contenu,
            'objet' => $request->objet,
            'id_offre' => $request->id_offre,
        ];

        if ($request->hasFile('piece_jointe')) {
            $data['piece_jointe_path'] = $request->file('piece_jointe')->store('messages/attachments', 'public');
        }

        $message = Message::create($data);

        // Envoyer la notification par email et plateforme
        $receiver = User::find($request->receiver_id);
        $receiver->notify(new MessageReceived($message));

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('demandeur')) {
            return redirect()->route('candidat.messagerie', ['user_id' => $request->receiver_id])->with('success', 'Message envoyé.');
        }

        return redirect()->route('messagerie.index')->with('success', 'Message envoyé avec succès.');
    }

    public function reply(Message $message, Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
            'piece_jointe' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $message->sender_id,
            'id_offre' => $message->id_offre,
            'objet' => 'RE: ' . $message->objet,
            'contenu' => $request->contenu,
        ];

        if ($request->hasFile('piece_jointe')) {
            $data['piece_jointe_path'] = $request->file('piece_jointe')->store('messages/attachments', 'public');
        }

        $reply = Message::create($data);

        // Notifier le destinataire de la réponse
        $message->sender->notify(new MessageReceived($reply));

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('demandeur')) {
            return redirect()->route('candidat.messagerie', ['user_id' => $message->sender_id])->with('success', 'Réponse envoyée.');
        }

        return redirect()->route('messagerie.index')->with('success', 'Réponse envoyée.');
    }

    private function authorizeAccess(Message $message)
    {
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            abort(403, 'Action non autorisée.');
        }
    }
}
