@extends('layouts.customer')

@section('title', 'Help & Support - QuickDines')

@section('styles')
<style>
    .page-header { margin-bottom: 25px; }
    .page-header h2 { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
    .page-header p { font-size: 14px; color: var(--text-muted); }

    .support-container { display: grid; grid-template-columns: 2fr 1.2fr; gap: 25px; max-width: 1000px; }
    
    /* FAQ Styles */
    .faq-section h3 { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; }
    
    .faq-item { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; overflow: hidden; }
    .faq-question { padding: 15px 20px; background: #fff; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 14px; color: var(--text-main); user-select: none; }
    .faq-question i { color: var(--text-muted); transition: transform 0.3s; }
    .faq-answer { padding: 0 20px; max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out, padding 0.3s ease; background: #f8fafc; font-size: 13px; color: var(--text-muted); line-height: 1.6; }
    
    .faq-item.active .faq-question i { transform: rotate(180deg); }
    .faq-item.active .faq-answer { padding: 15px 20px; max-height: 200px; }
    
    /* Live Chat Styles */
    .chat-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 500px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .chat-header { background: var(--primary); color: white; padding: 15px 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .chat-header i { font-size: 18px; }
    
    .chat-messages { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #f8fafc; }
    
    .message { max-width: 80%; padding: 10px 15px; border-radius: 15px; font-size: 13px; line-height: 1.5; position: relative; }
    .message-user { align-self: flex-end; background: var(--primary); color: white; border-bottom-right-radius: 2px; }
    .message-admin { align-self: flex-start; background: #e2e8f0; color: #0f172a; border-bottom-left-radius: 2px; }
    .message-time { font-size: 10px; opacity: 0.7; margin-top: 5px; text-align: right; }
    
    .chat-input-area { padding: 15px; background: white; border-top: 1px solid #e2e8f0; display: flex; gap: 10px; }
    .chat-input { flex: 1; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 20px; outline: none; font-family: inherit; font-size: 13px; }
    .chat-input:focus { border-color: var(--primary); }
    .btn-send { background: var(--primary); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s; }
    .btn-send:hover { transform: scale(1.05); }

    @media (max-width: 768px) {
        .support-container { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Help & Support</h2>
    <p>How can we help you today?</p>
</div>

<div class="support-container">
    <!-- FAQ Section -->
    <div class="dash-card faq-section">
        <h3>Frequently Asked Questions</h3>
        
        <div class="faq-item">
            <div class="faq-question">
                How do I track my order?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                You can track your order in real-time by navigating to the "Track Order" section in your dashboard. You will see the live status and location of your delivery.
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                Can I cancel my order?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Orders can only be cancelled within 1 minute of placement before the restaurant accepts it. Once the restaurant begins preparing your food, cancellation is not possible.
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                What payment methods do you accept?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                We accept all major credit/debit cards, UPI, Net Banking, and select digital wallets. You can also choose Cash on Delivery (COD) for eligible orders.
            </div>
        </div>
    </div>

    <!-- Live Chat Section -->
    <div class="chat-card">
        <div class="chat-header">
            <i class="fa-regular fa-comments"></i> Live Chat Support
        </div>
        <div class="chat-messages" id="chatMessages">
            <div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 20px;">
                Connecting to support...
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="messageInput" class="chat-input" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button class="btn-send" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    // FAQ Logic
    document.addEventListener('DOMContentLoaded', function() {
        const faqs = document.querySelectorAll('.faq-item');
        faqs.forEach(faq => {
            faq.querySelector('.faq-question').addEventListener('click', () => {
                faqs.forEach(item => {
                    if(item !== faq) item.classList.remove('active');
                });
                faq.classList.toggle('active');
            });
        });
        
        // Start Chat Polling
        fetchMessages();
        setInterval(fetchMessages, 3000);
    });

    // Chat Logic
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    let lastMessageCount = 0;

    function fetchMessages() {
        fetch('{{ route("chat.fetch") }}')
            .then(res => res.json())
            .then(messages => {
                if(messages.length > lastMessageCount || messages.length === 0) {
                    renderMessages(messages);
                    lastMessageCount = messages.length;
                }
            });
    }

    function renderMessages(messages) {
        if(messages.length === 0) {
            chatMessages.innerHTML = '<div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 20px;">Send a message to start chatting with our support team!</div>';
            return;
        }

        chatMessages.innerHTML = '';
        messages.forEach(msg => {
            const date = new Date(msg.created_at);
            const timeStr = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            const div = document.createElement('div');
            div.className = 'message ' + (msg.is_admin ? 'message-admin' : 'message-user');
            div.innerHTML = `
                ${msg.message}
                <div class="message-time">${timeStr}</div>
            `;
            chatMessages.appendChild(div);
        });
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        const text = messageInput.value.trim();
        if(!text) return;

        // Optimistic UI update
        const div = document.createElement('div');
        div.className = 'message message-user';
        div.style.opacity = '0.5';
        div.innerHTML = `
            ${text}
            <div class="message-time">Sending...</div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        messageInput.value = '';

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            fetchMessages(); // refresh completely to get timestamps and real ID
        });
    }
</script>
@endsection
