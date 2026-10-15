<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-image: url('{{ asset("images/hero-bg.jpg") }}'); background-size: cover;
            background-position: center; background-attachment: fixed; min-height: 100vh;
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .overlay { position: absolute; inset: 0; background: rgba(0,0,0,.4); z-index: 1; }
        .auth-card { background:#fff; width:100%; max-width:550px; border-radius:16px; overflow:hidden; position:relative; z-index:2; box-shadow:0 25px 50px -12px rgba(0,0,0,.25); }
        .auth-header { background:#2563eb; padding:20px 30px; display:flex; justify-content:space-between; align-items:center; }
        .auth-brand { color:#fff; font-size:20px; font-weight:800; display:flex; align-items:center; gap:8px; text-decoration:none; }
        .auth-type { color:rgba(255,255,255,.9); font-size:14px; font-weight:500; }
        .auth-body { padding:40px 30px; }
        .auth-title { text-align:center; font-size:24px; font-weight:700; color:#1e293b; margin-bottom:8px; }
        .auth-subtitle { text-align:center; font-size:14px; color:#64748b; margin-bottom:30px; }
        .role-selector { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:25px; }
        .role-option { border:1px solid #e2e8f0; border-radius:12px; padding:15px 10px; text-align:center; cursor:pointer; transition:all .3s; background:#fff; display:flex; flex-direction:column; align-items:center; gap:8px; }
        .role-option:hover { border-color:#cbd5e1; background:#f8fafc; }
        .role-option.active { border-color:#2563eb; background:#fff5f2; box-shadow:0 0 0 2px rgba(37,99,235,.1); }
        .role-option i { font-size:20px; color:#64748b; }
        .role-option.active i { color:#2563eb; }
        .role-option span { font-size:13px; font-weight:600; color:#475569; }
        .role-option.active span { color:#2563eb; }
        .form-group { margin-bottom:16px; }
        .form-input { width:100%; padding:14px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#1e293b; outline:none; transition:border-color .3s; }
        .form-input:focus { border-color:#2563eb; }
        .btn-submit { width:100%; padding:14px; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:16px; font-weight:600; cursor:pointer; transition:background .3s; margin-top:10px; }
        .btn-submit:hover { background:#1d4ed8; }
        .btn-submit:disabled { opacity:.65; cursor:not-allowed; }
        .doc-group { display:none; background:#f8fafc; padding:15px; border-radius:8px; border:1px dashed #cbd5e1; margin-bottom:16px; }
        .auth-footer { text-align:center; margin-top:20px; font-size:14px; color:#64748b; }
        .auth-footer a { color:#2563eb; font-weight:600; text-decoration:none; }
        .otp-box { display:none; }
        .otp-note { font-size:12px; color:#64748b; margin-top:-5px; margin-bottom:15px; text-align:center; }
        .alert { display:none; padding:12px; border-radius:8px; margin-bottom:18px; font-size:13px; }
        .alert.success { display:block; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; }
        .alert.error { display:block; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
        .change-mobile { margin-top:16px; text-align:center; font-size:13px; color:#2563eb; cursor:pointer; }
        @media(max-width:500px) { .auth-body { padding:30px 20px; } .role-selector { gap:8px; } .role-option { padding:12px 5px; } }
    </style>
</head>
<body>
<div class="overlay"></div>
<div class="auth-card">
    <div class="auth-header">
        <a href="{{ route('home') }}" class="auth-brand"><i class="fa-solid fa-bell-concierge"></i> QuickDines</a>
        <span class="auth-type">Register</span>
    </div>

    <div class="auth-body">
        <h1 class="auth-title" id="pageTitle">Create Account</h1>
        <p class="auth-subtitle" id="pageSubtitle">Join QuickDines and start your journey with us</p>

        <div id="alertBox" class="alert"></div>

        @if($errors->any())
            <div class="alert error" style="display:block;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div style="font-size:14px; font-weight:600; color:#1e293b; margin-bottom:10px;">I am a:</div>
        <div class="role-selector">
            <div class="role-option active" data-role="vendor"><i class="fa-solid fa-store"></i><span>Vendor</span></div>
            <div class="role-option" data-role="driver"><i class="fa-solid fa-bus"></i><span>Bus Driver</span></div>
            <div class="role-option" data-role="customer"><i class="fa-solid fa-user"></i><span>User</span></div>
        </div>

        <!-- Vendor / Driver registration -->
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="staffRegisterForm">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="vendor">

            <div class="form-group" id="nameGroup">
                <input type="text" name="name" id="staffName" class="form-input" placeholder="Full Name / Business Name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group" id="mobileGroup">
                <input type="text" name="mobile" id="staffMobile" class="form-input" placeholder="Mobile Number" value="{{ old('mobile') }}" required>
            </div>
            <div class="form-group" id="passwordGroup" style="position:relative;">
                <input type="password" name="password" class="form-input" placeholder="Password" required style="padding-right:40px;">
                <i class="fa-regular fa-eye" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#64748b;" onclick="togglePassword(this)"></i>
            </div>
            <div class="form-group" id="confirmPasswordGroup" style="position:relative;">
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm Password" required style="padding-right:40px;">
                <i class="fa-regular fa-eye" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#64748b;" onclick="togglePassword(this)"></i>
            </div>

            <div class="doc-group" id="documentUploadGroup" style="display:block;">
                <div style="font-size:13px; font-weight:600; color:#475569; margin-bottom:8px;" id="documentLabel">Upload FSSAI Certificate / Business Registration</div>
                <input type="file" name="document" id="documentInput" accept=".pdf,.jpg,.jpeg,.png" style="width:100%; font-size:14px; color:#64748b;" required>
                <div style="font-size:11px; color:#94a3b8; margin-top:6px;">Max size: 2MB. Accepted formats: PDF, JPG, PNG</div>
            </div>

            <div id="vendorExtraFields">
                <div class="form-group"><label style="font-size:13px; font-weight:600; color:#475569; display:block; margin-bottom:5px;">Restaurant Image</label><input type="file" name="image" class="form-input" accept=".jpg,.jpeg,.png"></div>
                <div class="form-group"><input type="text" name="city" class="form-input" placeholder="City (e.g., Hyderabad, Vizag)" value="{{ old('city') }}" required></div>
                <div class="form-group"><input type="text" name="address" class="form-input" placeholder="Full Address (e.g., RTC Complex, Main Road)" value="{{ old('address') }}" required></div>
                <div class="form-group"><input type="text" name="cuisine" class="form-input" placeholder="Cuisine (e.g., North Indian, Biryani)" value="{{ old('cuisine') }}" required></div>
                <div class="form-group"><input type="text" name="delivery_time" class="form-input" placeholder="Delivery Time (e.g., 25-30 mins)" value="{{ old('delivery_time') }}" required></div>
                <div class="form-group"><input type="text" name="discount" class="form-input" placeholder="Discount (e.g., 20% OFF or Free Delivery)" value="{{ old('discount') }}"></div>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <!-- User registration: name + mobile + OTP -->
        <div id="customerRegisterBox" style="display:none;">
            <div class="form-group">
                <input type="text" id="customerName" class="form-input" placeholder="Full Name" maxlength="255">
            </div>
            <div class="form-group">
                <input type="tel" id="customerMobile" class="form-input" placeholder="Mobile Number" maxlength="15" inputmode="numeric">
            </div>
            <button type="button" class="btn-submit" id="sendOtpBtn" onclick="sendCustomerOtp()">Send OTP</button>

            <div id="customerOtpBox" class="otp-box">
                <div class="form-group" style="margin-top:16px;">
                    <input type="text" id="customerOtp" class="form-input" placeholder="Enter OTP (1234)" maxlength="4" inputmode="numeric">
                </div>
                <div class="otp-note">Test OTP: <strong>1234</strong></div>
                <button type="button" class="btn-submit" id="verifyOtpBtn" onclick="verifyCustomerOtp()">Verify OTP</button>
                <div class="change-mobile" onclick="changeCustomerMobile()">Change Mobile Number</div>
            </div>
        </div>

        <div class="auth-footer">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const options = document.querySelectorAll('.role-option');
    const roleInput = document.getElementById('roleInput');
    const staffForm = document.getElementById('staffRegisterForm');
    const customerBox = document.getElementById('customerRegisterBox');
    const documentGroup = document.getElementById('documentUploadGroup');
    const documentInput = document.getElementById('documentInput');
    const vendorExtra = document.getElementById('vendorExtraFields');
    const pageTitle = document.getElementById('pageTitle');
    const pageSubtitle = document.getElementById('pageSubtitle');
    const alertBox = document.getElementById('alertBox');

    options.forEach(option => option.addEventListener('click', function() {
        options.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        const role = this.dataset.role;
        roleInput.value = role;

        if (role === 'customer') {
            staffForm.style.display = 'none';
            customerBox.style.display = 'block';
            pageTitle.innerText = 'User Registration';
            pageSubtitle.innerText = 'Register with your mobile number using OTP';
            return;
        }

        staffForm.style.display = 'block';
        customerBox.style.display = 'none';
        pageTitle.innerText = 'Create Account';
        pageSubtitle.innerText = 'Join QuickDines and start your journey with us';
        documentGroup.style.display = 'block';
        documentInput.required = true;

        if (role === 'vendor') {
            document.getElementById('documentLabel').innerText = 'Upload FSSAI Certificate / Business Registration';
            vendorExtra.style.display = 'block';
            vendorExtra.querySelectorAll('input').forEach(inp => inp.required = true);
            vendorExtra.querySelector('input[name="discount"]').required = false;
        } else {
            document.getElementById('documentLabel').innerText = 'Upload Driving License / Aadhar';
            vendorExtra.style.display = 'none';
            vendorExtra.querySelectorAll('input').forEach(inp => inp.required = false);
        }
    }));

    function togglePassword(icon) {
        const input = icon.previousElementSibling;
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    function showAlert(message, type) {
        alertBox.className = 'alert ' + type;
        alertBox.innerText = message;
    }

    async function sendCustomerOtp() {
        const name = document.getElementById('customerName').value.trim();
        const mobile = document.getElementById('customerMobile').value.trim();
        const btn = document.getElementById('sendOtpBtn');

        if (!name || !mobile) return showAlert('Please enter your name and mobile number.', 'error');
        btn.disabled = true; btn.innerText = 'Sending OTP...';

        try {
            const res = await fetch('{{ route("customer.send_otp") }}', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
                body: JSON.stringify({name, mobile})
            });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Failed to send OTP.');
            showAlert(data.message, 'success');
            document.getElementById('customerOtpBox').style.display = 'block';
            document.getElementById('customerName').readOnly = true;
            document.getElementById('customerMobile').readOnly = true;
            btn.style.display = 'none';
            document.getElementById('customerOtp').focus();
        } catch (e) {
            showAlert(e.message || 'An error occurred. Please try again.', 'error');
            btn.disabled = false; btn.innerText = 'Send OTP';
        }
    }

    async function verifyCustomerOtp() {
        const name = document.getElementById('customerName').value.trim();
        const mobile = document.getElementById('customerMobile').value.trim();
        const otp = document.getElementById('customerOtp').value.trim();
        const btn = document.getElementById('verifyOtpBtn');
        if (!otp) return showAlert('Please enter the OTP.', 'error');
        btn.disabled = true; btn.innerText = 'Verifying...';

        try {
            const res = await fetch('{{ route("customer.verify_otp") }}', {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
                body:JSON.stringify({name, mobile, otp})
            });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Invalid OTP.');
            showAlert(data.message, 'success');
            btn.innerText = 'Success!';
            window.location.href = data.redirect;
        } catch (e) {
            showAlert(e.message || 'An error occurred. Please try again.', 'error');
            btn.disabled = false; btn.innerText = 'Verify OTP';
        }
    }

    function changeCustomerMobile() {
        document.getElementById('customerName').readOnly = false;
        document.getElementById('customerMobile').readOnly = false;
        document.getElementById('customerOtp').value = '';
        document.getElementById('customerOtpBox').style.display = 'none';
        const btn = document.getElementById('sendOtpBtn');
        btn.style.display = 'block'; btn.disabled = false; btn.innerText = 'Send OTP';
        alertBox.className = 'alert'; alertBox.innerText = '';
    }
</script>
</body>
</html>
