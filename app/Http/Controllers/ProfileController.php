<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
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
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fullname = $data['fullname'];
        $user->email = $data['email'];
        $user->user_phone = $data['usr_number'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Jika request menginginkan JSON (dari AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'user_phone' => $user->user_phone,
                ]
            ]);
        }

        // Untuk request biasa (non-AJAX)
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = auth()->user();

        // Hapus avatar lama
        if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
            @unlink(storage_path('app/public/' . $user->avatar));
        }

        // Simpan avatar baru
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Photo profile berhasil terpasang",
            "avatar_url" => asset("storage/" . $path)
        ]);
    }

    public function updatePassword(PasswordUpdateRequest $request): JsonResponse
    {
        try {
            $user = $request->user();

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui.',
                'data' => [
                    'updated_at' => $user->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui password: ' . $e->getMessage()
            ], 500);
        }
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
