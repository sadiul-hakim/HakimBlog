<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Profile extends Component
{
    public string $tab;
    public string $tabName = 'personal_details';

    public string $name, $email, $username, $bio;
    public string $current_password, $new_password, $new_password_confirmation;

    protected $listeners = [
        'updateProfile' => '$refresh'
    ];

    // Synchronize the $tab public property with the browser URL query string.
    // keep => true means -> Keep the query parameter in the URL even if it equals the default value.
    protected $queryString = ['tab' => ['keep' => true]]; // Magic line

    public function selectTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        // $this->tab = Request('tab') ? Request('tab') : $this->tabName;
        $this->tab = $this->tab ?? $this->tabName;
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfileDetails()
    {
        $user = Auth::user();
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id
        ]);

        $user->name = $this->name;
        $user->username = $this->username;
        $user->bio = $this->bio;
        $updated = $user->save();

        if ($updated) {
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'You profile details have been updated.']);
            $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Failed to update profile details.']);
        }
    }

    public function updatePassword()
    {
        $user = Auth::user();
        $this->validate([
            'current_password' => ['required', 'min:5', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('Your current password does not match our records.'));
                }
            }],
            'new_password' => 'required|min:5|confirmed'
        ]);

        $updated = $user->update(['password' => Hash::make($this->new_password)]);
        if ($updated) {
            $user->notify(new PasswordResetNotification());
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();
            Session::flash('info', 'Your password has been updated. Please login with new password.');
            $this->redirectRoute('admin.login');
        } else {
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Failed to update password.']);
        }
    }
}
