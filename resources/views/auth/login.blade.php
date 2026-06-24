<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Medicare Admin &mdash; Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .login-panel-left {
            background: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
            min-height: 450px;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
    </style>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <!-- Left branding panel -->
                            <div class="col-lg-5 d-none d-lg-flex login-panel-left flex-column justify-content-center align-items-center p-5">
                                <i class="fas fa-hospital fa-5x text-white mb-4"></i>
                                <h2 class="text-white font-weight-bold text-center">Medicare Admin</h2>
                                <p class="text-white-50 text-center mt-2">Manage your Medicare platform with ease and confidence.</p>
                            </div>

                            <!-- Right login form -->
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h3 text-gray-900 font-weight-bold">Admin Sign In</h1>
                                        <p class="text-muted">Enter your credentials to access the panel</p>
                                    </div>

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                                            <button type="button" class="close" data-dismiss="alert">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold text-gray-700">Email Address</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope text-gray-400"></i></span>
                                                </div>
                                                <input
                                                    type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email"
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    placeholder="admin@medicare.com"
                                                    required
                                                    autofocus
                                                    autocomplete="email"
                                                >
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="font-weight-bold text-gray-700">Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lock text-gray-400"></i></span>
                                                </div>
                                                <input
                                                    type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password"
                                                    name="password"
                                                    placeholder="Enter your password"
                                                    required
                                                    autocomplete="current-password"
                                                >
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex align-items-center justify-content-between">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                <label class="custom-control-label text-gray-600" for="remember">Remember me</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
</body>
</html>
