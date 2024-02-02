<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendOtpBtn = document.getElementById('sendOtp');
            const verifyOtpBtn = document.getElementById('verifyOtp');
            const otpInputDiv = document.getElementById('otpInputDiv');
            const resendTimer = document.createElement('p');
            resendTimer.id = 'resendTimer';
            otpInputDiv.appendChild(resendTimer); // Append the timer paragraph below the OTP input

            function startResendTimer(duration, display) {
                var timer = duration,
                    minutes, seconds;
                var resendInterval = setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = 'Resend OTP in ' + minutes + ":" + seconds;

                    if (--timer < 0) {
                        clearInterval(resendInterval);
                        display.textContent = ''; // Clear the timer text
                        sendOtpBtn.disabled = false; // Enable the send OTP button
                        sendOtpBtn.innerText = 'Resend OTP'; // Change button text to indicate resending
                    }
                }, 1000);
            }

            sendOtpBtn.addEventListener('click', function() {
                const email = document.getElementById('email').value;
                // AJAX request to send OTP
                fetch('{{ route('send.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('OTP sent to your email.');
                        otpInputDiv.style.display = 'block'; // Show OTP input field
                        sendOtpBtn.disabled = true; // Disable the send OTP button
                        startResendTimer(60, document.querySelector(
                            '#resendTimer')); // Start the countdown
                    })
                    .catch(error => console.error('Error:', error));
            });

            verifyOtpBtn.addEventListener('click', function() {
                const otp = document.getElementById('otp').value;
                // AJAX request to verify OTP
                fetch('{{ route('verify.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            otp: otp
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('OTP verified.');
                            // You can now allow form submission or do additional steps
                        } else {
                            alert('Invalid OTP.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>
