<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\UserSocialLink;
use App\Notifications\PasswordResetNotification;
use Exception;
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
    public ?string $facebook_url = null;
    public ?string $linkedIn_url = null;
    public ?string $twitter_url = null;
    public ?string $youtube_url = null;
    public ?string $github_url = null;

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
        $user->load('socialLink');

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;

        $links = $user->socialLink;
        if (!is_null($links)) {
            $this->facebook_url = $links->facebook_url;
            $this->linkedIn_url = $links->linkedIn_url;
            $this->twitter_url = $links->twitter_url;
            $this->youtube_url = $links->youtube_url;
            $this->github_url = $links->github_url;
        }
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

    public function updateSocialLinks()
    {
        $user = Auth::user();
        $this->validate([
            'facebook_url' => 'nullable|url',
            'linkedIn_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'github_url' => 'nullable|url'
        ]);

        $data = [
            'facebook_url' => $this->facebook_url,
            'linkedIn_url' => $this->linkedIn_url,
            'twitter_url' => $this->twitter_url,
            'youtube_url' => $this->youtube_url,
            'github_url' => $this->github_url
        ];

        try {
            $user->socialLink()->updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'Your social links have been updated.']);
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Failed to update social links.']);
        }
    }
}
