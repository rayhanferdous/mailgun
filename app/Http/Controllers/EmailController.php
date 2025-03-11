<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function showForm()
    {
        return view('mail_form');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
            'cc' => 'nullable|array',
            'cc.*' => 'nullable|email',
            'subject' => 'required|string',
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt',
        ]);

        $mailData = [
            'subject' => $request->subject,
            'body' => $request->body
        ];

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments');
        }

        SendEmailJob::dispatch($mailData, $attachmentPath, $request->emails, $request->cc);


        return back()->with('success', 'Email sent successfully!');


    }



}
