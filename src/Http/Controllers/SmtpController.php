<?php

namespace Lwsoftbd\LaravelMailer\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Lwsoftbd\LaravelMailer\Models\SmtpSetting;

class SmtpController extends Controller
{
    public function index()
    {
        return view('laravel-mailer::smtp.index', [
            'smtps' => SmtpSetting::orderBy('priority')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'host'=>'required',
            'port'=>'required|numeric',
            'username'=>'nullable',
            'password'=>'nullable',
            'encryption'=>'nullable',
            'from_address'=>'required|email',
            'from_name'=>'required',
            'queue'=>'nullable',
            'priority'=>'required|numeric'
        ]);

        SmtpSetting::updateOrCreate(
            ['id'=>$request->id],
            $data + ['active'=>true]
        );

        cache()->flush();
        return back()->with('success','SMTP saved');
    }

    public function toggle($id)
    {
        $smtp = SmtpSetting::findOrFail($id);
        $smtp->update(['active'=>!$smtp->active]);
        cache()->flush();
        return back();
    }
}
