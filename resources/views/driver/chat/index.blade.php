@extends('layouts.driver')

@section('title', 'Live Chat Support')

@push('styles')
<style>
    /* Live Chat Styles */
    .chat-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; height: calc(100vh - 180px); min-height: 500px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 24px;}
    .chat-header { background: #0f172a; color: white; padding: 15px 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .chat-header i { font-size: 18px; }
    
    .chat-messages { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #f8fafc; }
    
    .message { max-width: 80%; padding: 10px 15px; border-radius: 15px; font-size: 13px; line-height: 1.5; position: relative; }
    .message-user { align-self: flex-end; background: #0f172a; color: white; border-bottom-right-radius: 2px; }
    .message-admin { align-self: flex-start; background: #e2e8f0; color: #0f172a; border-bottom-left-radius: 2px; }
    .message-time { font-size: 10px; opacity: 0.7; margin-top: 5px; text-align: right; }
    
    .chat-input-area { padding: 15px; background: white; border-top: 1px solid #e2e8f0; display: flex; gap: 10px; }
    .chat-input { flex: 1; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 20px; outline: none; font-family: inherit; font-size: 13px; }
    .chat-input:focus { border-color: #0f172a; }
    .btn-send { background: #0f172a; color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s; }
    .btn-send:hover { transform: scale(1.05); }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="font-size: 24px; font-weight: 800;">Live Chat Support <span style="font-size: 13px; font-weight: 500; color: #64748b;">Get assistance with your driver account</span></h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="chat-card">
            <div class="chat-header">
                <i class="fa-regular fa-comments"></i> Admin Support Team
            </div>
            <div class="chat-messages" id="chatMessages">
                <div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 20px;">
                    Connecting to support...
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" id="messageInput" class="chat-input" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
                <button class="btn-send" onclick="sendMessage()"><i class="fa-solid fa-paper-plane" style="color: white;"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    let lastMessageCount = 0;

    document.addEventListener('DOMContentLoaded', function() {
        // Start Chat Polling
        fetchMessages();
        setInterval(fetchMessages, 3000);
    });

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
@endpush
