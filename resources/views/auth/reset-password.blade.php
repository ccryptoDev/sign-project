@include('auth.header')
    <div class="login-form login-signin pt-11">
        <!--begin::Form-->
        <form class="form" id="kt_login_forgot_form" action={{ route('reset-password') }} method="POST">
            @csrf
            @if(session('success'))
                <div class="alert alert-success">
                    <span>Please verify your email address</span>
                </div>
            @endif
            @if(session('status'))
                <div class="alert alert-info">
                    <span>{{ Session::get('status') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
            <!--begin::Title-->
            <div class="text-center pb-8">
                <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Reset Your Password</h2>
            </div>
            <!--end::Title-->

            <!--begin::Form group-->
            <div class="form-group">
                <input
                    class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6"
                    type="hidden" placeholder="Token" name="token" autocomplete="off" value="{{$token}}"/>
                <input
                    class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6"
                    type="hidden" placeholder="Email" name="email" autocomplete="off" value="{{ request()->get('email')}}"/>
            </div>
            <div class="form-group">
                <input
                    class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6"
                    type="password" placeholder="New Password" name="password" autocomplete="off" />
            </div>
            <div class="form-group">
                <input
                    class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6"
                    type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off" />
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                <button type="submit" id="kt_login_forgot_submit"
                    class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Submit</button>
                <a type="button" id="kt_login_forgot_cancel"
                    href="{{route('login')}}"
                    class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</a>
            </div>
            <!--end::Form group-->
        </form>
        <!--end::Form-->
    </div>
@include('auth.footer')