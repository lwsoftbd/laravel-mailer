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

    // public function test(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email'
    //     ]);

    //     try {
    //         Mail::raw("This is a test email from Laravel Mailer\n\nPowered by: LW Soft BD", function ($mail) use ($request) {
    //         $mail->to($request->email)
    //             ->subject('SMTP Test Email');
    //         });

    //         return back()->with('test_success', 'Test email sent successfully!');
    //     } catch (\Exception $e) {
    //         return back()->with('test_error', 'Failed to send test email: ' . $e->getMessage());
    //     }
    // }
    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $html = '
                <div style="padding-left:20px;padding-top:20px;">
                    <p>This is a test email from Laravel Mailer</p>
                    <br/>
                    <p> - </p>
                    <p> Powered by: <a href="http://lwsoftbd.com" style="text-decoration-line:none;"><strong>LW Soft BD</strong></a> </p>
                </div>
            ';

            Mail::html($html, function ($mail) use ($request) {
                $mail->to($request->email)
                    ->subject('SMTP Test Email');
            });

            return back()->with('test_success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('test_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

}