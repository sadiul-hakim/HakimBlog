<div class="pd-20 card-box mb-4">
    <div class="tab">
        <div class="row clearfix">
            <div class="col-md-3 col-sm-12">
                <ul class="nav flex-column vtabs nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a wire:click="selectTab('general_settings')"
                            class="nav-link {{ $tab == 'general_settings' ? 'active' : '' }}" data-toggle="tab"
                            href="#general_settings" role="tab" aria-selected="true">General Settings</a>
                    </li>
                    <li class="nav-item">
                        <a wire:click="selectTab('logo_favicon')"
                            class="nav-link {{ $tab == 'logo_favicon' ? 'active' : '' }}" data-toggle="tab"
                            href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="tab-content">
                    <div class="tab-pane fade {{ $tab == 'general_settings' ? 'active show' : '' }}"
                        id="general_settings" role="tabpanel">
                        <div class="pd-20">
                            <form wire:submit='updateSettingInfo()'>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_title"><b>Site Title</b></label>
                                            <input type="text" name="site_title" id="site_title" class="form-control"
                                                wire:model='site_title' placeholder="Enter Site Title" />
                                            @error('site_title')
                                                <span class="text-danger ml-1">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_email"><b>Site Email</b></label>
                                            <input type="email" name="site_email" id="site_email" class="form-control"
                                                wire:model='site_email' placeholder="Enter Site Email" />
                                            @error('site_email')
                                                <span class="text-danger ml-1">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_phone"><b>Site Phone Number</b>
                                                <small>(Optional)</small></label>
                                            <input type="text" name="site_phone" id="site_phone" class="form-control"
                                                wire:model='site_phone'
                                                placeholder="Eg: ecommerce, free api, laravel" />
                                            @error('site_phone')
                                                <span class="text-danger ml-1">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_meta_keywords"><b>Site Meta
                                                    Keywords</b><small>(Optional)</small></label>
                                            <input type="text" name="site_meta_keywords" id="site_meta_keywords"
                                                class="form-control" wire:model='site_meta_keywords'
                                                placeholder="Enter Site Meta Keywords" />
                                            @error('site_meta_keywords')
                                                <span class="text-danger ml-1">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="site_meta_description">
                                        <b>Site Meta Description</b> <small>(Optional)</small>
                                    </label>
                                    <textarea name="site_meta_description" id="site_meta_description" class="form-control"
                                        placeholder="Type site meta description...." wire:model="site_meta_description"></textarea>
                                    @error('site_meta_description')
                                        <span class="text-danger ml-1">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'active show' : '' }}" id="logo_favicon"
                        role="tabpanel">
                        <div class="pd-20">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Site Logo</h6>
                                    <div class="mb-2 mt-1" style="max-width: 200px">
                                        <img src="{{ $site_logo ? asset('/storage/images/site/' . $site_logo) : '' }}"
                                            wire:ignore alt="" class="image-thumbnail" id="preview_site_logo">
                                    </div>
                                    <form method="POST" action="{{ route('admin.update_logo') }}"
                                        enctype="multipart/form-data" id="updateLogoForm">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="file" name="site_logo" id="site_logo" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg,.webp" />
                                            <span class="text-danger ml-1" id="logo_error"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Change Logo</button>
                                    </form>
                                </div>
                                {{-- Site Logo Ends --}}
                                <div class="col-md-6">
                                    <h6>Site Favicon</h6>
                                    <div class="mb-2 mt-1" style="max-width: 200px">
                                        <img src="{{ $site_favicon ? asset('/storage/images/site/' . $site_favicon) : '' }}"
                                            wire:ignore alt="" class="image-thumbnail"
                                            id="preview_site_favicon">
                                    </div>
                                    <form method="POST" action="{{ route('admin.update_favicon') }}"
                                        enctype="multipart/form-data" id="updateFaviconForm">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="file" name="site_favicon" id="site_favicon"
                                                class="form-control" accept=".png,.jpg,.jpeg,.svg,.webp" />
                                            <span class="text-danger ml-1" id="favicon_error"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Change Favicon</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
