<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatesFilterRequest;
use App\Models\Candidate;

class CandidateController extends Controller
{
    public function filter(CandidatesFilterRequest $request)
    {
        $user = $request->user();

        return Candidate::with(['companies' => function ($q) use ($user) {
            $q->where('company_id', $user?->company?->id)->select('status');

        }])->filters($request->validated())->get();
    }
}
