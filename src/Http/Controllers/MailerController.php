<?php

namespace LWSoftBD\LwMailer\Http\Controllers;

use LWSoftBD\LwMailer\Models\Smtp;
use LWSoftBD\LwMailer\Models\Mailer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MailerController extends Controller
{
    // Show all Mailers
    public function index()
    {
        $mailers = Mailer::All();
        return view('LwMailer::mailer.index', compact('mailers'));
    }

    // Create Form
    public function create()
    {
        return view('LwMailer::mailer.create');
    }
    public function edit($id)
    {
        $mailer = Mailer::findOrFail($id); // Fetch the mailer

        return view('LwMailer::mailer.edit', compact('mailer'));
    }

    // Store Mailers
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'mailer' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'encryption' => 'nullable|in:ssl,tls',
            'hostDomain' => 'required|string|max:255',
            'port' => 'required|numeric|max:65535',
            'from_name' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:50|unique:mailers,email_address',
            'password' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'is_default' => 'required|boolean',
            'note' => 'nullable|string',
        ]);

        // Save Mailer
        Mailer::create([
            'mailer' => $request->mailer,
            'name' => $request->name,
            'encryption' => $request->encryption,
            'hostDomain' => $request->hostDomain,
            'port' => $request->port,
            'from_name' => $request->from_name,
            'email_address' => $request->email_address,
            'password' => $request->password,
            'api_key' => $request->api_key,
            'secret_key' => $request->secret_key,
            'is_default' => $request->is_default,
            'note' => $request->note,
        ]);

        return redirect()->route('mailer.create')
                         ->with('success', 'New Mailer Created Successfully!');
    }
    

    public function update(Request $request, $id)
    {
        // Find the mailer
        $mailer = Mailer::findOrFail($id);

        // Validation
        $request->validate([
            'mailer' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'encryption' => 'nullable|in:ssl,tls',
            'hostDomain' => 'required|string|max:255',
            'port' => 'required|numeric|max:65535',
            'from_name' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:50|unique:mailers,email_address,' . $mailer->id,
            'password' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'is_default' => 'required|boolean',
            'note' => 'nullable|string',
        ]);

        // যদি is_default সেট করা হয়, অন্য মেইলারগুলোর is_default false করে দিন
        if ($request->is_default) {
            Mailer::where('id', '!=', $mailer->id)->update(['is_default' => false]);
        }

        // আপডেট
        $mailer->update([
            'mailer' => $request->mailer,
            'name' => $request->name,
            'encryption' => $request->encryption,
            'hostDomain' => $request->hostDomain,
            'port' => $request->port,
            'from_name' => $request->from_name,
            'email_address' => $request->email_address,
            'password' => $request->password,
            'api_key' => $request->api_key,
            'secret_key' => $request->secret_key,
            'is_default' => $request->is_default,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Mailer updated successfully!');
    }



    public function markDefault($id)
    {
        DB::transaction(function () use ($id) {

            // 1️⃣ সব mailer এর default reset
            Mailer::query()->update(['is_default' => 0]);

            // 2️⃣ নির্বাচিত mailer কে default সেট
            $mailer = Mailer::findOrFail($id);
            $mailer->update(['is_default' => 1]);

            // 3️⃣ smtps টেবিলের প্রথম রেকর্ড নাও
            $smtp = Smtp::firstOrFail();

            // 4️⃣ default mailer এর ডাটা smtp তে বসাও
            $smtp->update([
                'mailer'        => $mailer->mailer ?? 'smtp',
                'hostDomain'    => $mailer->hostDomain,
                'port'          => $mailer->port,
                'from_name'     => $mailer->from_name,
                'email_address'=> $mailer->email_address,
                'password'      => $mailer->password,
                'encryption'    => $mailer->encryption,
                'api_key'       => $mailer->api_key,
                'secret_key'    => $mailer->secret_key,
            ]);

        });

        return back()->with('success', 'Mailer marked as default and SMTP updated successfully!');
    }



    public function defaultMailer()
    {
        // শুধুমাত্র default mailer
        $mailer = Mailer::where('is_default', 1)->first();

        return view('LwMailer::mailer.default', compact('mailer'));
    }


}