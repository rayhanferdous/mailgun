<?php

namespace App\Http\Controllers;

use App\Mail\HelloEmail;
use App\Models\SentEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $helloEmail = new HelloEmail($mailData, $attachmentPath);

        try {
            $emails = array_filter($request->emails, fn($email) => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));

            if (count($emails) === 0) {
                return back()->with('error', 'No valid email addresses provided.');
            }

            $email = Mail::to($emails);

            if ($request->cc) {
                $ccEmails = array_filter($request->cc, fn($email) => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
                if (count($ccEmails) > 0) {
                    $email->cc($ccEmails);
                }
            }

            $email->send($helloEmail);

            SentEmail::create([
                'sent_by' => auth()->id(),
                'subject' => $mailData['subject'],
                'body' => $mailData['body'],
                'emails' => json_encode($request->emails),
                'cc' => json_encode($request->cc),
                'attachment_path' => $attachmentPath,
            ]);

            return back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return back()->with('error', 'Oops! There was an error sending the email.');
        }
    }
}