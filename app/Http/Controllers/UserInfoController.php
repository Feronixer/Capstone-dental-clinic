<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userInfos = UserInfo::all();
        return view('user_infos.index', compact('userInfos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user_infos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'age'        => 'nullable|integer|min:0',
            'gender'     => 'nullable|string|max:10',
        ]);

        UserInfo::create($validated);

        return redirect()->route('user-infos.index')->with('success', 'User added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInfo $userInfo)
    {
        return view('user_infos.show', compact('userInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInfo $userInfo)
    {
        return view('user_infos.edit', compact('userInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserInfo $userInfo)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'age'        => 'nullable|integer|min:0',
            'gender'     => 'nullable|string|max:10',
        ]);

        $userInfo->update($validated);

        return redirect()->route('user-infos.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInfo $userInfo)
    {
        $userInfo->delete();
        return redirect()->route('user-infos.index')->with('success', 'User deleted successfully!');
    }
}
