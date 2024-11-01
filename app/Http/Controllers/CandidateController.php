<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidatesFilterRequest;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(CandidatesFilterRequest $request)
    {
        $user = $request->user()?->load('company.wallet');

        $candidates = Candidate::with(['companies' => function ($q) use ($user) {
            $q->where('company_id', $user?->company?->id)->select('status');
        }])->filters($request->validated())->get();
        $mvpCandidates = Candidate::where('is_mvp', 1)->get();

        // @TODO this should be done using a table
        $strengths = ['PHP', 'Laravel', 'Angular', 'React', 'Python', 'Vue.js', 'TailwindCSS', 'Wordpress'];
        $softSkills = ['Diplomacy', 'Team player', 'Leadership', 'Sales experience', 'Presentation abilities', 'Public speaking', 'Conflict management'];

        return view('candidates.index', [
            'user' => $user,
            'candidates' => $candidates,
            'mvpCandidates' => $mvpCandidates,
            'strengths' => $strengths,
            'skills' => $softSkills,
            'filters' => [
                'strengths' => $request->validated('strengths') ?? [],
                'skills' => $request->validated('skills') ?? [],
            ],
        ]);
    }

    public function contact()
    {
        // @todo
        // Your code goes here...
    }

    public function hire()
    {
        // @todo
        // Your code goes here...
    }
}
