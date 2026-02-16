<?php

namespace App\Livewire\Admin;

use App\Models\GeneralSetting;
use Exception;
use Livewire\Component;

class Settings extends Component
{
    public ?string $tab = null;
    public string $default_tab = "general_settings";
    protected $queryString = ['tab' => ['keep' => true]];

    public ?string $site_title = null;
    public ?string $site_email = null;
    public ?string $site_phone = null;
    public ?string $site_meta_keywords = null;
    public ?string $site_meta_description = null;

    public function selectTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = $this->tab ?? $this->default_tab;

        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $this->site_title = $settings->site_title;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_meta_keywords = $settings->site_meta_keywords;
            $this->site_meta_description = $settings->site_meta_description;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }

    public function updateSettingInfo()
    {
        $this->validate([
            'site_title' => 'required',
            'site_email' => 'required|email'
        ]);
        $data = [
            'site_title' => $this->site_title,
            'site_email' => $this->site_email,
            'site_phone' => $this->site_phone,
            'site_meta_keywords' => $this->site_meta_keywords,
            'site_meta_description' => $this->site_meta_description
        ];

        try {
            GeneralSetting::updateOrCreate(['id' => 1], $data);
            $this->dispatch('showAlert', ['type' => 'success', 'message' => 'General Settings have been updated.']);
        } catch (Exception $ex) {
            report($ex);
            $this->dispatch('showAlert', ['type' => 'error', 'message' => 'Failed to update General Settings.']);
        }
    }
}
