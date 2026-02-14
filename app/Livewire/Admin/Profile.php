<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public string $tab;
    public string $tabName = 'personal_details';

    public string $name, $email, $username, $bio;

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
}
