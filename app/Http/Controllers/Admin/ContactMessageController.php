<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    /**
     * Display contact messages with basic search and filters.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'filter' => ['nullable', Rule::in(['inbox', 'unread', 'archived'])],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $filter = $validated['filter'] ?? 'inbox';
        $search = trim($validated['search'] ?? '');
        $selectedMessageId = $request->integer('message');
        $skipAutoReadMessageId = (int) $request->session()
            ->get('skip_contact_auto_read', 0);

        $messages = ContactMessage::query()
            ->when($filter === 'inbox', fn ($query) => $query
                ->where('is_archived', false))
            ->when($filter === 'unread', fn ($query) => $query
                ->where('is_archived', false)
                ->where('is_read', false))
            ->when($filter === 'archived', fn ($query) => $query
                ->where('is_archived', true))
            ->when($search !== '', function ($query) use ($search) {
                $term = '%' . Str::lower($search) . '%';

                $query->where(function ($searchQuery) use ($term) {
                    $searchQuery
                        ->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(company) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(message) LIKE ?', [$term]);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if ($selectedMessageId) {
            $selectedMessage = $messages
                ->getCollection()
                ->firstWhere('id', $selectedMessageId);

            if (
                $selectedMessage &&
                ! $selectedMessage->is_read &&
                $selectedMessage->id !== $skipAutoReadMessageId
            ) {
                $selectedMessage->update(['is_read' => true]);
            }
        }

        $unreadCount = ContactMessage::query()
            ->where('is_archived', false)
            ->where('is_read', false)
            ->count();

        return view('admin.contact-messages.index', compact(
            'messages',
            'filter',
            'search',
            'unreadCount'
        ));
    }

    /**
     * Display a contact message and mark it as read.
     */
    public function show(ContactMessage $contactMessage): View
    {
        $skipAutoReadMessageId = (int) request()
            ->session()
            ->get('skip_contact_auto_read', 0);

        if (
            ! $contactMessage->is_read &&
            $contactMessage->id !== $skipAutoReadMessageId
        ) {
            $contactMessage->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Mark a message as read.
     */
    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark a message as unread.
     */
    public function markUnread(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_read' => false]);

        return back()
            ->with('skip_contact_auto_read', $contactMessage->id)
            ->with('success', 'Message marked as unread.');
    }

    /**
     * Archive a message.
     */
    public function archive(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_archived' => true]);

        return back()->with('success', 'Message archived.');
    }

    /**
     * Restore an archived message.
     */
    public function unarchive(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_archived' => false]);

        return back()->with('success', 'Message moved to inbox.');
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted.');
    }
}
