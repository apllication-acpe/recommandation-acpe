<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagerieController extends Controller
{
    /**
     * Affiche la messagerie avec les conversations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeUserId = $request->query('user_id');

        // Récupérer toutes les conversations (uniques par interlocuteur)
        $sentMessages = \App\Models\Message::where('sender_id', $user->id)->get();
        $receivedMessages = \App\Models\Message::where('receiver_id', $user->id)->get();
        
        $interlocuteursIds = $sentMessages->pluck('receiver_id')
            ->merge($receivedMessages->pluck('sender_id'))
            ->unique()
            ->filter(fn($id) => $id != $user->id);

        $chats = [];
        foreach ($interlocuteursIds as $id) {
            $interlocuteur = \App\Models\User::find($id);
            if (!$interlocuteur) continue;

            $lastMessage = \App\Models\Message::where(function($q) use ($user, $id) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $id);
                })->orWhere(function($q) use ($user, $id) {
                    $q->where('sender_id', $id)->where('receiver_id', $user->id);
                })->latest()->first();

            $unreadCount = \App\Models\Message::where('sender_id', $id)
                ->where('receiver_id', $user->id)
                ->whereNull('lu_at')
                ->count();

            $chats[$id] = [
                'user' => $interlocuteur,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount
            ];
        }

        // Trier les chats par date du dernier message
        uasort($chats, function($a, $b) {
            $dateA = $a['last_message'] ? $a['last_message']->created_at : 0;
            $dateB = $b['last_message'] ? $b['last_message']->created_at : 0;
            return $dateB <=> $dateA;
        });

        $activeChat = null;
        $activeMessages = [];

        if ($activeUserId && isset($chats[$activeUserId])) {
            $activeChat = $chats[$activeUserId];
            $activeMessages = \App\Models\Message::where(function($q) use ($user, $activeUserId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $activeUserId);
                })->orWhere(function($q) use ($user, $activeUserId) {
                    $q->where('sender_id', $activeUserId)->where('receiver_id', $user->id);
                })->orderBy('created_at', 'asc')->get();

            // Marquer comme lu
            \App\Models\Message::where('sender_id', $activeUserId)
                ->where('receiver_id', $user->id)
                ->whereNull('lu_at')
                ->update(['lu_at' => now()]);
        }

        // Détecter la vue à retourner selon le rôle
        $viewPath = Auth::user()->hasRole('admin') ? 'admin.messagerie.index' : 'candidat.messagerie.index';

        return view($viewPath, compact('chats', 'activeChat', 'activeMessages', 'activeUserId'));
    }

    /**
     * Envoie un message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'contenu' => 'required|string',
            'objet' => 'nullable|string',
            'piece_jointe' => 'nullable|file|max:5120', // 5MB max
        ]);

        $data = $request->only(['receiver_id', 'contenu', 'objet']);
        $data['sender_id'] = Auth::id();

        if ($request->hasFile('piece_jointe')) {
            $data['piece_jointe_path'] = $request->file('piece_jointe')->store('messages/attachments', 'public');
        }

        \App\Models\Message::create($data);

        return redirect()->route('candidat.messagerie', ['user_id' => $request->receiver_id])
            ->with('success', 'Message envoyé');
    }
}
