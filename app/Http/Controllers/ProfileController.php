<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
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
        $user = $request->user();
        $validated = $request->validated();
        $shouldRemovePhoto = (bool) ($validated['hapus_foto_profil'] ?? false);

        unset($validated['hapus_foto_profil']);

        if ($shouldRemovePhoto && $user->foto_profil && File::exists(public_path($user->foto_profil))) {
            File::delete(public_path($user->foto_profil));
        }

        if ($shouldRemovePhoto) {
            $validated['foto_profil'] = null;
        }

        if ($request->hasFile('foto_profil')) {
            $directory = public_path('uploads/profile-photos');

            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $file = $request->file('foto_profil');
            $fileName = 'profile-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $fileName);

            if ($user->foto_profil && File::exists(public_path($user->foto_profil))) {
                File::delete(public_path($user->foto_profil));
            }

            $validated['foto_profil'] = 'uploads/profile-photos/' . $fileName;
        }

        $user->fill($validated);
        $user->save();

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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
