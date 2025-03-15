<?php

namespace App\Jobs;

use App\Mail\HelloEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Models\SentEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public $mailData;
    public $attachmentPath;
    public $emails;
    public $cc;

    public $tries = 3;
    public $timeout = 120;
    public $retryAfter = 90;

    public function __construct($mailData, $attachmentPath, $emails, $cc = null)
    {
        $this->mailData = $mailData;
        $this->attachmentPath = $attachmentPath;
        $this->emails = $emails;
        $this->cc = $cc;
    }

    public function handle()
    {

        try {
            if (is_null($this->emails) || !is_array($this->emails)) {
                throw new \Exception('Emails must be an array and cannot be null.');
            }

            $helloEmail = new HelloEmail($this->mailData, $this->attachmentPath);

            $email = Mail::to($this->emails);

            if (!empty($this->cc)) {
                $email->cc($this->cc);
            }

            $email->send($helloEmail);

            SentEmail::create([
                'sent_by' => auth()->id(),
                'subject' => $this->mailData['subject'],
                'body' => $this->mailData['body'],
                'emails' => json_encode($this->emails),
                'cc' => json_encode($this->cc),
                'attachment_path' => $this->attachmentPath,
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Email sending failed: ' . $exception->getMessage());
    }
}