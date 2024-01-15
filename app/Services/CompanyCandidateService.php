<?php

namespace App\Services;

use App\Exceptions\CandidateAlreadyContacted;
use App\Exceptions\CandidateAlreadyHired;
use App\Exceptions\CandidateNotContacted;
use App\Exceptions\UserCompanyWalletBalanceIsLow;
use App\Exceptions\UserNotAuthorizedToContactCandidates;
use App\Mail\CandidateContacted;
use App\Mail\CandidateHired;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CompanyCandidateService
{
    public function contactCandidate(Company $company, Candidate $candidate): Candidate
    {
        $this->verifyCandidateAlreadyContacted($company, $candidate);
        $this->verifyCandidateAlreadyHired($company, $candidate);

        $this->contactCandidateUpdateBalance($company);
        $company->candidates()->attach($candidate, [
            'status' => 'CONTACTED',
        ]);

        Mail::to($candidate->email)->send(new CandidateContacted($company, $candidate));

        return $company
            ->candidates()
            ->where('candidate_id', $candidate->id)
            ->with(['companies' => function ($q) use ($company) {
                $q->where('company_id', $company->id)->select('status');

            }])
            ->first();
    }

    public function hireCandidate(Company $company, Candidate $candidate): Candidate
    {

        $this->verifyCandidateWasContacted($company, $candidate);
        $this->verifyCandidateAlreadyHired($company, $candidate);

        $company->candidates()->updateExistingPivot($candidate, [
            'status' => 'HIRED',
        ]);
        $this->hireCandidateUpdateBalance($company);

        Mail::to($candidate->email)->send(new CandidateHired($company, $candidate));

        return $company
            ->candidates()
            ->where('candidate_id', $candidate->id)
            ->with(['companies' => function ($q) use ($company) {
                $q->where('company_id', $company->id)->select('status');

            }])
            ->first();
    }

    protected function contactCandidateUpdateBalance(Company $company)
    {
        $walletCoins = $company->wallet->coins;

        if ($walletCoins === 0 || $walletCoins - 5 < 0) {
            throw new UserCompanyWalletBalanceIsLow('Your wallet balance is too low, please buy more credits.');
        }

        $company->wallet()->update([
            'coins' => $walletCoins - 5,
        ]);
    }

    protected function hireCandidateUpdateBalance(Company $company)
    {
        $walletCoins = $company->wallet->coins;

        $company->wallet()->update([
            'coins' => $walletCoins + 5,
        ]);
    }

    protected function verifyCandidateAlreadyContacted(Company $company, Candidate $candidate)
    {
        $candidateContacted = $candidate->companies()
            ->where('company_id', $company->id)
            ->where('status', 'HIRED')
            ->first();

        if ($candidateContacted) {
            throw new CandidateAlreadyContacted('The candidate has already been contacted');
        }
    }

    protected function verifyCandidateWasContacted(Company $company, Candidate $candidate)
    {
        $candidateContacted = $candidate->companies()
            ->where('company_id', $company->id)
            ->where('status', 'CONTACTED')
            ->first();

        if (!$candidateContacted) {
            throw new CandidateNotContacted('The candidate must be contacted first.');
        }
    }

    protected function verifyCandidateAlreadyHired(Company $company, Candidate $candidate)
    {
        $candidateContacted = $candidate->companies()
            ->where('company_id', $company->id)
            ->where('status', 'HIRED')
            ->first();

        if ($candidateContacted) {
            throw new CandidateAlreadyHired('The candidate has already been hired');
        }
    }

    public function verifyUserHasCompany(User $user)
    {
        if (!$user->company) {
            throw new UserNotAuthorizedToContactCandidates('You are not authorized to contact candidates.');
        }
    }
}