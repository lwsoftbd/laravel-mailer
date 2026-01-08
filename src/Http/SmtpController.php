<?php

namespace LWSoftBD\LaravelMailer\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use LWSoftBD\LaravelMailer\Models\Smtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class SmtpController extends Controller
{
    public function index()
    {
        return view('laravel-mailer::smtp.index', [
            'smtps' => Smtp::latest()->get()
        ]);
    }

    public function create()
    {
        return view('laravel-mailer::smtp.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'host' => 'required',
            'port' => 'required|numeric',
            'username' => 'nullable',
            'password' => 'nullable',
            'encryption' => 'nullable',
            'from_address' => 'required|email',
            'from_name' => 'required',
        ]);

        Smtp::create($data);

        return redirect()
            ->route('mailer.index')
            ->with('success', 'SMTP Added Successfully');
    }

    public function edit(Smtp $smtp)
    {
        return view('laravel-mailer::smtp.edit', compact('smtp'));
    }

    public function update(Request $request, Smtp $smtp)
    {
        $smtp->update($request->all());

        return back()->with('success', 'SMTP Updated');
    }

    public function delete(Smtp $smtp)
    {
        $smtp->delete();

        return back()->with('success', 'SMTP Deleted');
    }

    public function makeDefault(Smtp $smtp)
    {
        $smtp->update(['is_default' => true]);

        Artisan::call('config:clear');

        return back()->with('success', 'Default SMTP Applied');
    }

    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        Mail::raw('SMTP Test Email Successful 🎉', function ($mail) use ($request) {
            $mail->to($request->email)
                 ->subject('SMTP Test');
        });

        return back()->with('success', 'Test Email Sent');
    }
}