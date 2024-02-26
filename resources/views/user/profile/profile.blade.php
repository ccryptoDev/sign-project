@include('user.header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            @include('user.profile.profile-nav')
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <form action="{{ route('update-profile') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-7 my-2">
                                    @if (Session::has('success'))
                                    <div class="alert alert-success">
                                        <span>Success</span>
                                    </div>
                                    @endif
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <!-- <ul> -->
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                        <!-- </ul> -->
                                    </div>
                                    @endif
                                    <div class="row">
                                        <label class="col-xl-3"></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <h5 class="font-weight-bold mt-10 mb-6">Profile Overview</h5>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Avatar</label>
                                        <div class="col-9">
                                            <div class="image-input image-input-empty image-input-outline"
                                                id="kt_user_edit_avatar"
                                                style="background-image: url({{Auth()->user()->profile_photo_url==null?'/assets/media/users/blank.png':Auth()->user()->profile_photo_url}})">
                                                <div class="image-input-wrapper"></div>
                                                <label
                                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                    data-action="change" data-toggle="tooltip" title=""
                                                    data-original-title="Change avatar">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="profile_avatar"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span
                                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                    data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span
                                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                    data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">First Name</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text"
                                                value="{{old('first_name')?old('first_name'):Auth()->user()->first_name}}"
                                                name="first_name" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Last Name</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text"
                                                value="{{old('last_name')?old('last_name'):Auth()->user()->last_name}}"
                                                name="last_name" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Business Name</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text"
                                                value="{{old('business_name')?old('business_name'):Auth()->user()->business_name}}"
                                                name="business_name" />
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Phone number</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text"
                                                value="{{old('phone_number')?old('phone_number'):Auth()->user()->phone_number}}"
                                                name="phone_number" />
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Email
                                            Address</label>
                                        <div class="col-9">
                                            <div class="input-group input-group-lg input-group-solid">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                            class="la la-at"></i></span></div>
                                                <input type="email"
                                                    class="form-control form-control-lg form-control-solid" name="email"
                                                    value="{{old('email')?old('email'):Auth()->user()->email}}"
                                                    placeholder="Email" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row float-right mr-2">
                                        <button class="btn btn-danger text-right font-weight-bold" type="submit">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.footer')