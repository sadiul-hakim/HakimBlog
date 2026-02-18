<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $data = [
            'pageTitle' => 'Dashboard'
        ];
        return view('back.pages.dashboard', compact('data'));
    }

    public function logoutHandle(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('info', 'You are now logged out!');
    }

    public function profileView()
    {
        $data = [
            'pageTitle' => 'Profile'
        ];

        return view('back.pages.profile', $data);
    }

    public function generalSettings()
    {
        $data = [
            'pageTitle' => 'General Settings'
        ];

        return view('back.pages.settings', $data);
    }

    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('profilePictureFile');

        // Define the path relative to the "public" disk
        $folderPath = 'images/users/';
        $extension = '.' . $file->extension();
        $fileName = 'IMG_' . uniqid() . $extension;
        $fullPath = $folderPath . $fileName;

        // Upload the file using Laravel's Storage
        // This puts it in storage/app/public/images/users/
        $uploaded = Storage::disk('public')->put($fullPath, File::get($file));

        if ($uploaded) {
            // Handle Old File Deletion
            $old_picture = $user->getAttributes()['picture'] ?? null;
            if ($old_picture && Storage::disk('public')->exists($folderPath . $old_picture)) {
                Storage::disk('public')->delete($folderPath . $old_picture);
            }

            // Update Database
            $user->update(['picture' => $fileName]);

            return response()->json(['status' => 1, 'message' => 'Profile picture updated.'], 201);
        }

        return response()->json(['status' => 0, 'message' => 'Upload failed.'], 500);
    }

    public function updateLogo(Request $request)
    {

        $setting = GeneralSetting::take(1)->first();
        if (is_null($setting)) {
            return response()->json(['status' => 0, 'message' => 'Make sure you update general settings first.'], 500);
        }

        $file = $request->file('site_logo');

        // Define the path relative to the "public" disk
        $folderPath = 'images/site/';
        $extension = '.' . $file->extension();
        $fileName = 'IMG_' . uniqid() . $extension;
        $fullPath = $folderPath . $fileName;
        $uploaded = Storage::disk('public')->put($fullPath, File::get($file));

        if ($uploaded) {
            // Handle Old File Deletion
            $old_picture = $setting->site_logo ?? null;
            if ($old_picture && Storage::disk('public')->exists($folderPath . $old_picture)) {
                Storage::disk('public')->delete($folderPath . $old_picture);
            }

            // Update Database
            $setting->update(['site_logo' => $fileName]);

            return response()->json(['status' => 1, 'message' => 'Site Logo has been uploaded.'], 200);
        }

        return response()->json(['status' => 0, 'message' => 'Failed to upload site logo.'], 500);
    }

    public function updateFavicon(Request $request)
    {

        $setting = GeneralSetting::take(1)->first();
        if (is_null($setting)) {
            return response()->json(['status' => 0, 'message' => 'Make sure you update general settings first.'], 500);
        }

        $file = $request->file('site_favicon');

        // Define the path relative to the "public" disk
        $folderPath = 'images/site/';
        $extension = '.' . $file->extension();
        $fileName = 'IMG_' . uniqid() . $extension;
        $fullPath = $folderPath . $fileName;
        $uploaded = Storage::disk('public')->put($fullPath, File::get($file));

        if ($uploaded) {
            // Handle Old File Deletion
            $old_picture = $setting->site_favicon ?? null;
            if ($old_picture && Storage::disk('public')->exists($folderPath . $old_picture)) {
                Storage::disk('public')->delete($folderPath . $old_picture);
            }

            // Update Database
            $setting->update(['site_favicon' => $fileName]);

            return response()->json(['status' => 1, 'message' => 'Site Favicon has been uploaded.'], 200);
        }

        return response()->json(['status' => 0, 'message' => 'Failed to upload site favicon.'], 500);
    }
}
