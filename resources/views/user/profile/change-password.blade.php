@include('user.header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            @include('user.profile.profile-nav')
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <form action="{{ route('update-password') }}" method="post">
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
                                            <h5 class="font-weight-bold mt-10 mb-6">Password</h5>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Current
                                            Password</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid"
                                                type="password" name="current_password" value="{{old('current_password')}}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">New Password</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid"
                                                type="password" name="password" value="{{old('password')}}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Confirm
                                            Password</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid"
                                                type="password" name="password_confirmation" value="{{old('password_confirmation')}}" />
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