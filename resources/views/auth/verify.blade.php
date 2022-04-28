
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Your Phone Number</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material-icon/css/material-design-iconic-font.min.css') }}">

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="background-image: url('{{ asset('assets/images/signup-bg.jpg') }}');">

    <div class="main">

        <section class="signup">
            <!-- <img src="images/signup-bg.jpg" alt=""> -->
            <div class="container">
                <div class="signup-content">
                    <form method="POST" action="{{ route('verifyphone.perform') }}" id="signup-form" class="signup-form">
                        @csrf
                        <h2 class="form-title">Phone Number Verification</h2>
                        <div class="form-group">
                            <input type="number" class="form-input" name="otp" id="otp" placeholder="Input OTP Number"/>

                        </div>
                        <div class="form-group">
                            <a href="{{ route('new.otp') }}"><button style="cursor: pointer;" class="form-submit" type="button">Request New OTP Number</button></a>
                        </div>
                        <div class="form-group">
                            <input style="cursor: pointer;" type="submit" name="submit" id="submit" class="form-submit" value="Verify Number"/>
                        </div>
                    </form>
                    <p class="loginhere">
                        Return to Login Page ? <a href="{{ route('logout.perform') }}" class="loginhere-link">Login here</a>
                    </p>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
