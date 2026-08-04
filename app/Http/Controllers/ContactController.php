<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.pages.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|min:5|max:255',
            'message' => 'required|string|min:10',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'subject.min' => 'Subject must be at least 5 characters',
            'message.min' => 'Message must be at least 10 characters',
        ]);

        Contact::create($data);

        return redirect()->route('contact')->with('success', 'Thanks for contacting us! We\'ll get back to you within 24 hours.');
    }
}
