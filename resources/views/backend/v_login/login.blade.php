<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judul ?? 'Login' }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/images/favicon.png') }}">
    <link href="{{ asset('backend/dist/css/style.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div id="loginform">
                    <div class="text-center pt-3 pb-3">
                        <span class="db">
                            <img src="{{ asset('backend/images/logo.png') }}" alt="logo" />
                        </span>
                    </div>

                    <!-- Session Error Message -->
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ session('error') }}</strong>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row pb-4">
                            <div class="col-12">
                                <!-- Email -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white"><i class="ti-user"></i></span>
                                    <input type="text" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Masukkan Email" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror

                                <!-- Password -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-warning text-white"><i class="ti-pencil"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Masukkan Password" required>
                                </div>
                                @error('password')
                                <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="pt-3">
                                        <button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock me-1"></i> Lupa password?</button>
                                        <button class="btn btn-success float-end text-white" type="submit">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <!-- Recovery Form Placeholder (Optional Implementation) -->
                <div id="recoverform" style="display:none;">
                    <div class="text-center">
                        <span class="text-white">Masukkan email anda, kami akan kirimkan instruksi pemulihan password.</span>
                    </div>
                    <form class="col-12 mt-3" method="POST" action="#">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-danger text-white"><i class="ti-email"></i></span>
                            <input type="email" class="form-control form-control-lg" placeholder="Email Address" required>
                        </div>
                        <div class="row mt-3 pt-3 border-top border-secondary">
                            <div class="col-12">
                                <a class="btn btn-success text-white" href="#" id="to-login">Kembali ke Login</a>
                                <button class="btn btn-info float-end" type="submit">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('backend/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(".preloader").fadeOut();
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        $('#to-login').click(function() {
            $("#recoverform").hide();
            $("#loginform").fadeIn();
        });
    </script>
</body>

</html>