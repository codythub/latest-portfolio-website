<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    /**
     * Display the public contact page.
     */
    public function show(): View
    {
        $profile = User::query()->firstOrFail();

        return view('contact', compact('profile'));
    }

    /**
     * Store a new public contact message.
     */
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return back()->with('success', 'Thank you. Your message has been sent.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['nullable', 'max:0'],
        ]);

        unset($validated['website']);

        $contactMessage = ContactMessage::create($validated);

        $adminEmail = User::query()->value('email')
            ?: config('mail.from.address');

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(
                    new ContactMessageReceived($contactMessage)
                );
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you. Your message has been sent.');
    }
}
