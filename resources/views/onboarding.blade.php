<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-image: url('{{ asset("images/firstpage.png") }}'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; }
        .overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.65); z-index: 1; backdrop-filter: blur(8px); }
        .app-container { background: #fff; width: 100%; max-width: 420px; height: 100vh; position: relative; z-index: 2; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 30px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1); }
        
        @media (min-width: 480px) {
            .app-container { height: 85vh; max-height: 720px; min-height: unset; border-radius: 28px; }
            body { padding: 20px; }
        }

        .slide { display: none; flex-direction: column; height: 100%; padding: 40px 24px 130px; animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1); flex: 1; overflow-y: auto; scrollbar-width: none; }
        .slide::-webkit-scrollbar { display: none; }
        .slide.active { display: flex; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUpItem { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .slide-header { text-align: center; margin-bottom: 32px; }
        .slide-title { font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 12px; line-height: 1.25; letter-spacing: -0.5px; }
        .slide-title span { color: #2563eb; }
        .slide-subtitle { font-size: 15px; color: #64748b; line-height: 1.6; padding: 0 10px; font-weight: 500; }

        .slide-image { flex: 1; display: flex; align-items: center; justify-content: center; min-height: 220px; margin: 10px 0; }
        .slide-image img { max-width: 100%; max-height: 280px; object-fit: contain; }
        
        /* Custom Icons and List Styles */
        .feature-list { display: flex; flex-direction: column; gap: 16px; margin-top: 5px; }
        .feature-item { display: flex; gap: 20px; align-items: center; padding: 18px 16px; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: transform 0.3s ease, box-shadow 0.3s ease; animation: slideUpItem 0.6s cubic-bezier(0.4, 0, 0.2, 1) backwards; }
        .feature-item:hover { transform: translateY(-4px); box-shadow: 0 12px 25px rgba(0,0,0,0.06); border-color: #e2e8f0; }
        
        .feature-item:nth-child(1) { animation-delay: 0.1s; }
        .feature-item:nth-child(2) { animation-delay: 0.2s; }
        .feature-item:nth-child(3) { animation-delay: 0.3s; }
        .feature-item:nth-child(4) { animation-delay: 0.4s; }

        .feature-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.5), 0 4px 12px rgba(0,0,0,0.05); }
        
        /* Icon Colors */
        .bg-orange-light { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #ef6c00; }
        .bg-green-light { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
        .bg-purple-light { background: linear-gradient(135deg, #f3e5f5, #e1bee7); color: #6a1b9a; }
        .bg-blue-light { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565c0; }
        .bg-red-light { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #c62828; }

        .feature-text { flex: 1; }
        .feature-text h4 { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px; letter-spacing: -0.2px; }
        .feature-text p { font-size: 13.5px; color: #64748b; line-height: 1.5; margin: 0; }

        /* Bottom Controls */
        .bottom-bar { position: absolute; bottom: 0; left: 0; width: 100%; padding: 24px 30px; background: linear-gradient(to top, rgba(255,255,255,1) 80%, rgba(255,255,255,0) 100%); z-index: 10; display: flex; flex-direction: column; gap: 16px; }
        
        .controls-row { display: flex; align-items: center; justify-content: space-between; }
        
        .dots { display: flex; gap: 8px; }
        .dot { width: 8px; height: 8px; border-radius: 4px; background: #e2e8f0; transition: all 0.3s ease; }
        .dot.active { width: 24px; background: #2563eb; }

        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 16px 24px; border-radius: 14px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; border: none; outline: none; }
        .btn-primary { background: #2563eb; color: #fff; width: 100%; flex: 1; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.25); }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3); }
        .btn-secondary { background: transparent; color: #64748b; width: 100%; }
        .btn-secondary:hover { color: #0f172a; background: #f8fafc; }
        
        .btn-icon-right { margin-left: 8px; }
        
        .back-btn { width: 48px; height: 48px; border-radius: 14px; background: #f8fafc; color: #64748b; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; cursor: pointer; font-size: 18px; margin-right: 16px; transition: all 0.2s; }
        .back-btn:hover { background: #f1f5f9; color: #0f172a; }

        /* Premium Illustration Box */
        .illustration-box { position: relative; width: 210px; height: 210px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #eff6ff 0%, #bfdbfe 100%); border-radius: 50%; margin: 0 auto; box-shadow: 0 20px 40px rgba(37, 99, 235, 0.2), inset 0 0 0 8px rgba(255, 255, 255, 0.5); animation: float 6s ease-in-out infinite; }
        .illustration-box i { font-size: 70px; color: #2563eb; text-shadow: 2px 4px 10px rgba(0,0,0,0.1); z-index: 2; margin-bottom: 0; opacity: 1; }
        .illustration-box::before { content: ''; position: absolute; width: 140%; height: 140%; border-radius: 50%; border: 2px dashed rgba(37, 99, 235, 0.2); animation: spin 20s linear infinite; }
        .illustration-box::after { content: ''; position: absolute; width: 110%; height: 110%; border-radius: 50%; background: rgba(37, 99, 235, 0.05); z-index: 1; }
        
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-12px); } 100% { transform: translateY(0px); } }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="app-container">
        
        <!-- Slide 1 -->
        <div class="slide active" id="slide-1">
            <div class="slide-header">
                <div class="slide-title">Welcome to<br><span>QuickDines</span></div>
                <div class="slide-subtitle">Delicious food on your journey,<br>delivered to your seat.</div>
            </div>
            <div class="slide-image">
                <div class="illustration-box">
                    <img src="{{ asset('images/firstbus.jpg') }}" alt="QuickDines Bus" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; z-index: 2;">
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" id="slide-2">
            <div class="slide-header">
                <div class="slide-title">How<br><span>QuickDines</span> Works</div>
            </div>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon bg-orange-light"><i class="fa-solid fa-qrcode"></i></div>
                    <div class="feature-text">
                        <h4>Scan & Discover</h4>
                        <p>Scan the QR code on the bus to explore restaurants on your route.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon bg-green-light"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="feature-text">
                        <h4>Choose & Order</h4>
                        <p>Pick your favorite food from assigned restaurants.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon bg-purple-light"><i class="fa-solid fa-bus"></i></div>
                    <div class="feature-text">
                        <h4>Get it on Your Seat</h4>
                        <p>We deliver your order to your seat on the bus.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" id="slide-3">
            <div class="slide-header">
                <div class="slide-title">Delicious Food,<br><span>Happy Journey!</span></div>
                <div class="slide-subtitle">Let's get started with QuickDines and make your journey delightful.</div>
            </div>
            <div class="slide-image" style="position: relative;">
                <div style="position: absolute; width: 300px; height: 300px; background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; z-index: 1;"></div>
                <img src="{{ asset('images/last.png') }}" alt="Delicious Food" style="position: relative; z-index: 2; border-radius: 50%; box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 0 8px rgba(255,255,255,0.5); width: 320px; height: 320px; object-fit: cover; animation: float 5s ease-in-out infinite;">
            </div>
        </div>

        <!-- Bottom Controls -->
        <div class="bottom-bar">
            <!-- Slide 1 Controls -->
            <div id="controls-1" class="controls-wrapper" style="display: block;">
                <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                    <div class="dots">
                        <div class="dot active"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="nextSlide(2)">Next <i class="fa-solid fa-arrow-right btn-icon-right"></i></button>
                <button class="btn btn-secondary" onclick="finishOnboarding()">Skip</button>
            </div>

            <!-- Slides 2 & 3 Controls -->
            <div id="controls-mid" class="controls-wrapper" style="display: none;">
                <div class="controls-row">
                    <button class="back-btn" onclick="prevSlide()"><i class="fa-solid fa-arrow-left"></i></button>
                    <div class="dots">
                        <div class="dot" id="dot-mid-1"></div>
                        <div class="dot active" id="dot-mid-2"></div>
                        <div class="dot" id="dot-mid-3"></div>
                    </div>
                    <button class="btn btn-primary" style="flex: unset; width: 120px;" onclick="nextSlide()">Next <i class="fa-solid fa-arrow-right btn-icon-right"></i></button>
                </div>
            </div>

            <!-- Slide 3 Controls -->
            <div id="controls-3" class="controls-wrapper" style="display: none;">
                <div class="controls-row">
                    <button class="back-btn" onclick="prevSlide()"><i class="fa-solid fa-arrow-left"></i></button>
                    <div class="dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot active"></div>
                    </div>
                    <button class="btn btn-primary" style="flex: unset; width: auto; padding: 14px 32px;" onclick="finishOnboarding()">Get Started <i class="fa-solid fa-arrow-right btn-icon-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 1;
        const totalSlides = 3;
        const nextUrl = "{{ session('next_url', route('customer.auth')) }}";

        function updateUI() {
            // Hide all slides
            for (let i = 1; i <= totalSlides; i++) {
                document.getElementById('slide-' + i).classList.remove('active');
            }
            // Show current slide
            document.getElementById('slide-' + currentSlide).classList.add('active');

            // Hide all controls
            document.getElementById('controls-1').style.display = 'none';
            document.getElementById('controls-mid').style.display = 'none';
            document.getElementById('controls-3').style.display = 'none';

            // Show appropriate controls
            if (currentSlide === 1) {
                document.getElementById('controls-1').style.display = 'block';
            } else if (currentSlide === totalSlides) {
                document.getElementById('controls-3').style.display = 'block';
            } else {
                document.getElementById('controls-mid').style.display = 'block';
                // Update dots for middle slides
                for(let i=1; i<=totalSlides; i++) {
                    const dot = document.getElementById('dot-mid-' + i);
                    if(dot) {
                        dot.className = i === currentSlide ? 'dot active' : 'dot';
                    }
                }
            }
        }

        function nextSlide(step) {
            if (step) {
                currentSlide = step;
            } else if (currentSlide < totalSlides) {
                currentSlide++;
            }
            updateUI();
        }

        function prevSlide() {
            if (currentSlide > 1) {
                currentSlide--;
                updateUI();
            }
        }

        function finishOnboarding() {
            // Redirect to the next URL (auth or home)
            window.location.href = nextUrl;
        }
    </script>
</body>
</html>
