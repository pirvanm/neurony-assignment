<?php

namespace App\Mail;

use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateContacted extends Mailable
{
    use Queueable, SerializesModels;

    public Company $company;
    public Candidate $candidate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Company $company, Candidate $candidate)
    {
        $this->company = $company;
        $this->candidate = $candidate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.candidate.contacted');
    }
}
