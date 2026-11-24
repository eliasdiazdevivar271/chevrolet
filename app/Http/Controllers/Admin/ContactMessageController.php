<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.messages.index', ['messages' => ContactMessage::orderByDesc('id')->paginate(20)]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        return view('admin.messages.show', ['message' => $contactMessage]);
    }
}
