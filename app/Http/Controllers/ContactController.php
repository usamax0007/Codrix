<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\SiteSetting;

class ContactController extends Controller
{
    public function index(){
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('frontend.pages.contact', compact('settings'));
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10'
            ],[
                'name.required'=>'Name is required',
            'email.email'=>'Email is required',
            'subject.min'=>'Subject is required, at least 5 characters',
            'message.min'=>'Message is required, at least 10 characters',
        ]);

        Contact::create($request->all());

        return redirect()->route('contact')->with('success', 'Thanks for contacting us! We\'ll get back to you within 24 hours.');
    }
}
