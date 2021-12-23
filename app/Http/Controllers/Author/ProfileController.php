<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        $validated = $request->validated();

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        }
        
        auth()->user()->update($validated);

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
