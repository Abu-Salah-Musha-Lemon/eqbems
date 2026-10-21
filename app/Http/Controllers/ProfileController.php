<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\OnlineExamAttempt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Preserve access to the system and prevent foreign-key failures or
        // loss of student academic history. Perform these checks before logout.
        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            return back()->withErrors([
                'password' => 'The last Super Admin account cannot be deleted.',
            ], 'userDeletion');
        }
        if ($user->results()->exists() || OnlineExamAttempt::where('student_id', $user->id)->exists()) {
            return back()->withErrors([
                'password' => 'This account has academic records. Contact an administrator to manage access.',
            ], 'userDeletion');
        }

        $user->delete();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
