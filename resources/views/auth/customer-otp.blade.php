<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <meta name="csrf-token"
        content="{{ csrf_token() }}">

    <title>Customer Login - QuickDines</title>


    <!-- Font -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {
            width: 100%;
            min-height: 100%;
        }


        body {

            font-family: 'Inter', Arial, sans-serif;

            min-height: 100vh;
            min-height: 100dvh;

            background-image: url('{{ asset("images/formbg.png") }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 18px;

            position: relative;

            overflow-x: hidden;
        }


        /* Background Overlay */

        body::before {

            content: "";

            position: fixed;

            inset: 0;

            background: rgba(15, 23, 42, 0.42);

            z-index: 0;
        }


        /* Main Container */

        .login-wrapper {

            width: 100%;
            max-width: 410px;

            position: relative;

            z-index: 1;
        }


        /* Login Card */

        .login-box {

            width: 100%;

            background: #eef6ff;

            border-radius: 20px;

            padding: 28px 24px 24px;

            box-shadow:
                0 18px 45px rgba(0, 0, 0, 0.25);

            border: 1px solid rgba(255, 255, 255, 0.75);
        }


        /* Logo Area */

        .brand {

            text-align: center;

            margin-bottom: 25px;
        }


        .logo {

            color: #2563eb;

            font-size: 25px;

            font-weight: 800;

            letter-spacing: 0.4px;

            margin-bottom: 8px;
        }


        .logo i {

            margin-right: 5px;

            font-size: 22px;
        }


        .title {

            color: #172033;

            font-size: 21px;

            font-weight: 700;

            margin-bottom: 6px;
        }


        .subtitle {

            color: #64748b;

            font-size: 13px;

            line-height: 1.5;
        }


        /* Form */

        .form-group {

            margin-bottom: 17px;
        }


        label {

            display: block;

            color: #334155;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 7px;
        }


        /* Input */

        .input-box {

            position: relative;

            width: 100%;
        }


        .input-box i {

            position: absolute;

            left: 14px;

            top: 50%;

            transform: translateY(-50%);

            color: #64748b;

            font-size: 14px;

            pointer-events: none;
        }


        input {

            width: 100%;

            height: 50px;

            padding: 0 14px 0 40px;

            border-radius: 10px;

            border: 1px solid #cbdcf3;

            background: #ffffff;

            color: #1e293b;

            font-family: inherit;

            font-size: 14px;

            outline: none;

            -webkit-appearance: none;

            transition: 0.2s ease;
        }


        input::placeholder {

            color: #9aa8b8;
        }


        input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.10);
        }


        /* Continue Button */

        .login-button {

            width: 100%;

            height: 50px;

            margin-top: 4px;

            border: none;

            border-radius: 10px;

            background: #18d41b;

            color: white;

            font-family: inherit;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

            -webkit-tap-highlight-color: transparent;

            transition: 0.2s ease;
        }


        .login-button:active {

            transform: scale(0.98);
        }


        .login-button:hover {

            background: #13bb16;
        }


        .login-button:disabled {

            background: #86efac;

            cursor: not-allowed;

            transform: none;
        }


        /* Message */

        .message {

            min-height: 18px;

            margin-top: 13px;

            text-align: center;

            font-size: 12px;

            font-weight: 500;

            line-height: 1.5;
        }


        /* Security Text */

        .security {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            color: #64748b;

            font-size: 11px;

            margin-top: 18px;

            padding-top: 15px;

            border-top: 1px solid #d8e7f8;
        }


        .security i {

            color: #2563eb;

            font-size: 11px;
        }


        /* Back Home */

        .back-home {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 6px;

            margin-top: 15px;

            color: #2563eb;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            padding: 5px;
        }


        .back-home:active {

            opacity: 0.7;
        }


        /* =========================================
           SMALL MOBILE
        ========================================= */

        @media (max-width: 380px) {

            body {

                padding: 12px;
            }


            .login-box {

                padding: 24px 19px 21px;

                border-radius: 17px;
            }


            .brand {

                margin-bottom: 21px;
            }


            .logo {

                font-size: 23px;
            }


            .title {

                font-size: 20px;
            }


            .subtitle {

                font-size: 12px;
            }


            input {

                height: 48px;

                font-size: 14px;
            }


            .login-button {

                height: 48px;
            }

        }


        /* =========================================
           VERY SMALL MOBILE
        ========================================= */

        @media (max-height: 650px) and (max-width: 480px) {

            body {

                align-items: flex-start;

                padding-top: 12px;

                padding-bottom: 12px;
            }


            .login-box {

                padding-top: 21px;

                padding-bottom: 18px;
            }


            .brand {

                margin-bottom: 18px;
            }


            .form-group {

                margin-bottom: 13px;
            }


            .security {

                margin-top: 13px;

                padding-top: 12px;
            }


            .back-home {

                margin-top: 10px;
            }

        }


        /* =========================================
           TABLET / DESKTOP
        ========================================= */

        @media (min-width: 768px) {

            body {

                padding: 30px;
            }


            .login-box {

                padding: 35px 32px 30px;

                border-radius: 20px;
            }


            .logo {

                font-size: 27px;
            }


            .title {

                font-size: 23px;
            }


            input,
            .login-button {

                height: 52px;
            }

        }
    </style>

</head>


<body>


    <div class="login-wrapper">


        <div class="login-box">


            <!-- Brand -->

            <div class="brand">

                <div class="logo">

                    <i class="fa-solid fa-bell-concierge"></i>

                    QUICKDINES

                </div>


                <div class="title">

                    Customer Login

                </div>


                <div class="subtitle">

                    Enter your details to continue

                </div>

            </div>


            <!-- Full Name -->

            <div class="form-group">

                <label for="name">
                    Full Name
                </label>


                <div class="input-box">

                    <i class="fa-solid fa-user"></i>


                    <input
                        type="text"
                        id="name"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        required>

                </div>

            </div>


            <!-- Mobile -->

            <div class="form-group">

                <label for="mobile">
                    Mobile Number
                </label>


                <div class="input-box">

                    <i class="fa-solid fa-phone"></i>



                    <input
                        type="tel"
                        id="mobile"
                        name="mobile"
                        placeholder="Enter 10-digit mobile number"
                        maxlength="10"
                        minlength="10"
                        autocomplete="tel"
                        inputmode="numeric"
                        pattern="[0-9]{10}"
                        required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">



                </div>

            </div>


            <!-- Continue -->

            <button
                type="button"
                class="login-button"
                id="loginBtn"
                onclick="loginCustomer()">

                <span id="buttonText">
                    Continue
                </span>

                <i class="fa-solid fa-arrow-right"></i>

            </button>


            <!-- Message -->

            <div
                id="message"
                class="message">
            </div>


            <!-- Security -->

            <div class="security">

                <i class="fa-solid fa-shield-halved"></i>

                Secure and protected login

            </div>


            <!-- Back Home -->




        </div>

    </div>



    <script>
        const csrfToken =
            document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');


        async function loginCustomer() {


            const name =
                document
                .getElementById('name')
                .value
                .trim();


            const mobile =
                document
                .getElementById('mobile')
                .value
                .trim();


            /* Name Validation */

            if (!name) {

                showMessage(
                    'Please enter your full name.',
                    false
                );

                document
                    .getElementById('name')
                    .focus();

                return;
            }


            /* Mobile Validation */

            if (!mobile) {

                showMessage(
                    'Please enter your mobile number.',
                    false
                );

                document
                    .getElementById('mobile')
                    .focus();

                return;
            }


            const mobileNumber =
                mobile.replace(/\D/g, '');


            if (mobileNumber.length < 10) {

                showMessage(
                    'Please enter a valid mobile number.',
                    false
                );

                document
                    .getElementById('mobile')
                    .focus();

                return;
            }


            /* Button */

            const button =
                document.getElementById('loginBtn');


            const buttonText =
                document.getElementById('buttonText');


            button.disabled = true;


            buttonText.textContent =
                'Please wait...';


            try {


                const response =
                    await fetch(
                        '{{ route("customer.verify_otp") }}', {

                            method: 'POST',

                            credentials: 'same-origin',

                            headers: {

                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken

                            },

                            body: JSON.stringify({

                                name: name,

                                mobile: mobile,

                                otp: '1234'

                            })

                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    showMessage(
                        data.message ||
                        'Login failed. Please try again.',
                        false
                    );

                    return;
                }


                showMessage(
                    'Login successful. Redirecting...',
                    true
                );


                window.location.href =
                    data.redirect || '/';


            } catch (error) {

                console.error(error);

                showMessage(
                    'Something went wrong. Please try again.',
                    false
                );

            } finally {

                button.disabled = false;

                buttonText.textContent =
                    'Continue';

            }

        }


        /* Message Function */

        function showMessage(message, success = true) {

            const box =
                document.getElementById('message');


            box.textContent =
                message;


            box.style.color =
                success ?
                '#16a34a' :
                '#dc2626';

        }


        /* Enter Key */

        document
            .getElementById('name')
            .addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'Enter') {

                        document
                            .getElementById('mobile')
                            .focus();

                    }

                }
            );


        document
            .getElementById('mobile')
            .addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'Enter') {

                        loginCustomer();

                    }

                }
            );
    </script>


</body>

</html>
