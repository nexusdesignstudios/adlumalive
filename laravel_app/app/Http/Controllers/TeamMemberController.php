<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TeamMember::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'bio' => 'nullable|string',
            'linkedin_url' => 'nullable|url',
        ]);

        $member = TeamMember::create($data);
        return response()->json($member, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamMember $teamMember)
    {
        return response()->json($teamMember);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|string|max:255',
            'image_url' => 'nullable|url',
            'bio' => 'nullable|string',
            'linkedin_url' => 'nullable|url',
        ]);

        $teamMember->update($data);
        return response()->json($teamMember);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return response()->json(null, 204);
    }
}
