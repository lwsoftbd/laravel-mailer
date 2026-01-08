<?php

namespace LWSoftBD\LwMailer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use LWSoftBD\LwMailer\Models\Smtp;
use Illuminate\Support\Facades\Mail;
use LWSoftBD\LwMailer\Models\Mailer;
use LWSoftBD\LwMailer\Models\SmtpLog;


class SmtpController extends Controller
{

    public function index()
    {
        $smtp = Smtp::first();
        $sender= Mailer::where('is_default', 1)->first();
        $mailers = Mailer::orderByDesc('is_default')->get();
        $logs = SmtpLog::latest()->take(5)->get()->sortBy('created_at');
        return view('LwMailer::smtp.index', compact('smtp', 'sender', 'mailers', 'logs'));
    }

    public function update(Request $request)
    {
    $request->validate([ 
        'mailer' => 'nullable|string|max:255',
        'name' => 'required|string|max:255',
        'hostDomain'    => 'nullable|string|max:255', 
        'port'          => 'nullable|numeric',
        'from_name'     => 'nullable|string|max:255',
        'email_address' => 'nullable|email|max:255',
        'password'      => 'nullable|string',
        'encryption'    => 'nullable|in:ssl,tls',
        'api_key'       => 'nullable|string|max:255',
        'secret_key'    => 'nullable|string|max:255',
    ]);

    // ✅ আগে default mailer আছে কিনা চেক করো
    $defaultMailer = Mailer::where('is_default', 1)->first();

    if (!$defaultMailer) {
        return back()->with('error', 'Update Faild. Please set a default mailer first.');
    }

    DB::transaction(function () use ($request, $defaultMailer) {

        // 1️⃣ Update SMTP (first row)
        $smtp = Smtp::firstOrFail();

        $smtp->update([
            'mailer'        => $request->mailer,
            'name'          => $request->name,
            'hostDomain'    => $request->hostDomain,
            'port'          => $request->port,
            'from_name'     => $request->from_name,
            'email_address' => $request->email_address,
            'password'      => $request->password,
            'encryption'    => $request->encryption,
            'api_key'       => $request->api_key,
            'secret_key'    => $request->secret_key,
        ]);

        // 2️⃣ Update default Mailer
        $defaultMailer->update([
            'mailer'        => $request->mailer,
            'name'          => $request->name,
            'hostDomain'    => $request->hostDomain,
            'port'          => $request->port,
            'from_name'     => $request->from_name,
            'email_address' => $request->email_address,
            'password'      => $request->password,
            'encryption'    => $request->encryption,
            'api_key'       => $request->api_key,
            'secret_key'    => $request->secret_key,
            'is_default'   => true
        ]);
    });

    return back()->with('success', 'SMTP & Default Mailer updated successfully!');
}

    public function sendMailTest(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        // Get from email settings
        $fromName  = config('mail.from.name');
        $fromEmail = config('mail.from.address');

        try {
            // Try to send email
            Mail::raw($request->message, function ($msg) use ($request, $fromName, $fromEmail) {
                $msg->from($fromEmail, $fromName);
                $msg->to($request->email)
                    ->subject('Test Email');
            });

            // Save SUCCESS log
            SmtpLog::create([
                'from_name'   => $fromName,
                'from_email'  => $fromEmail,
                'to_email'    => $request->email,
                'message'     => $request->message,
                'status'      => 'success',
                'error_message' => null,
            ]);

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {

            // Save FAILED log
            SmtpLog::create([
                'from_name'   => $fromName,
                'from_email'  => $fromEmail,
                'to_email'    => $request->email,
                'message'     => $request->message,
                'status'      => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }


    // Show logs with filters
    public function log(Request $request)
    {
        $query = SmtpLog::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->orderBy('created_at', 'asc')->paginate(20);

        return view('LwMailer::smtp.logs', compact('logs'));
    }


    // ReTry email
    public function reTry($id)
    {
        $log = SmtpLog::findOrFail($id);

        try {
            Mail::raw($log->message, function ($msg) use ($log) {
                $msg->from($log->from_email, $log->from_name);
                $msg->to($log->to_email)
                    ->subject('Test Email');
            });

            $log->update([
                'status' => 'success',
                'error_message' => null,
                'updated_at'    => now(),
            ]);

            return back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Resend failed: ' . $e->getMessage());
        }
    }


    // Resend email
    public function resend($id)
    {
        $oldLog = SmtpLog::findOrFail($id);

        try {
            // Send email again
            Mail::raw($oldLog->message, function ($msg) use ($oldLog) {
                $msg->from($oldLog->from_email, $oldLog->from_name);
                $msg->to($oldLog->to_email)
                    ->subject('Test Email');
            });

            // Create NEW log entry for resend
            SmtpLog::create([
                'from_name'     => $oldLog->from_name,
                'from_email'    => $oldLog->from_email,
                'to_email'      => $oldLog->to_email,
                'message'       => $oldLog->message,
                'status'        => 'success',
                'error_message' => null,
            ]);

            return back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {

            // Create NEW log entry for failed resend
            SmtpLog::create([
                'from_name'     => $oldLog->from_name,
                'from_email'    => $oldLog->from_email,
                'to_email'      => $oldLog->to_email,
                'message'       => $oldLog->message,
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Resend failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $log = SmtpLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'SMTP log deleted successfully.');
    }

}