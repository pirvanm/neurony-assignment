<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CandidateAlreadyContacted;
use App\Exceptions\CandidateAlreadyHired;
use App\Exceptions\CandidateNotContacted;
use App\Exceptions\UserCompanyWalletBalanceIsLow;
use App\Exceptions\UserNotAuthorizedToContactCandidates;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatesFilterRequest;
use App\Models\Candidate;
use App\Services\CompanyCandidateService;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    protected CompanyCandidateService $companyService;

    public function __construct(CompanyCandidateService $companyCandidateService)
    {
        $this->companyCandidateService = $companyCandidateService;
    }

    public function filter(CandidatesFilterRequest $request)
    {
        $user = $request->user();

        return Candidate::with(['companies' => function ($q) use ($user) {
            $q->where('company_id', $user?->company?->id)->select('status');

        }])->filters($request->validated())->get();
    }


    public function contact(Request $request, Candidate $candidate)
    {
        try {
            $this->companyCandidateService->verifyUserHasCompany($request->user());
            return $this->companyCandidateService->contactCandidate($request->user()->company, $candidate);
        } catch (UserNotAuthorizedToContactCandidates $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (CandidateAlreadyContacted $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (UserCompanyWalletBalanceIsLow $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'Something went wrong!'], 400);
        }
    }

    public function hire(Request $request, Candidate $candidate)
    {
        try {
            $this->companyCandidateService->verifyUserHasCompany($request->user());
            return $this->companyCandidateService->hireCandidate($request->user()?->company, $candidate);
        } catch (UserNotAuthorizedToContactCandidates $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (CandidateNotContacted $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }  catch (CandidateAlreadyHired $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 400);
        }
    }
}
