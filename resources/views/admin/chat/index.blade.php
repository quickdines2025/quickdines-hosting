@extends(Auth::user()->role === 'support' ? 'layouts.support' : 'layouts.admin')

@section('title', 'Live Chat - Admin Panel')

@section('content')
<style>
    .chat-container { display: flex; height: calc(100vh - 150px); background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-top: 10px; }
    .chat-sidebar { width: 350px; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; background: #f8fafc; flex-shrink: 0; }
    @media (max-width: 768px) {
        .chat-container { flex-direction: column; }
        .chat-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e2e8f0; height: 35%; flex-shrink: 1; }
    }
    .sidebar-header { padding: 20px; font-weight: 800; font-size: 18px; border-bottom: 1px solid #e2e8f0; background: white; }
    
    .user-list { flex: 1; overflow-y: auto; }
    .user-item { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 15px; }
    .user-item:hover { background: #f1f5f9; }
    .user-item.active { background: #e2e8f0; }
    
    .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
    .user-info { flex: 1; overflow: hidden; }
    .user-name { font-weight: 700; font-size: 15px; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;}
    .user-role { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; }
    
    .unread-badge { background: #ef4444; color: white; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 12px; }

    .chat-main { flex: 1; display: flex; flex-direction: column; background: #fff; min-height: 0; overflow: hidden; }
    .chat-header { padding: 20px; font-weight: 700; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px; background: white; flex-shrink: 0; }
    
    .chat-messages { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; background: #f8fafc; min-height: 0; }
    
    .message { max-width: 70%; padding: 12px 18px; border-radius: 18px; font-size: 14px; line-height: 1.5; position: relative; }
    .message-user { align-self: flex-start; background: #e2e8f0; color: #0f172a; border-bottom-left-radius: 4px; }
    .message-admin { align-self: flex-end; background: #6366f1; color: white; border-bottom-right-radius: 4px; }
    .message-time { font-size: 11px; opacity: 0.7; margin-top: 5px; text-align: right; }
    
    .chat-input-area { padding: 20px; background: white; border-top: 1px solid #e2e8f0; display: flex; gap: 15px; align-items: center; }
    .chat-input { flex: 1; padding: 12px 20px; border: 1px solid #cbd5e1; border-radius: 24px; outline: none; font-family: inherit; font-size: 14px; transition: border-color 0.2s; }
    .chat-input:focus { border-color: #6366f1; }
    .btn-send { background: #6366f1; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s; }
    .btn-send:hover { transform: scale(1.05); }

    .no-chat-selected { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b; font-size: 15px; font-weight: 600; background: #fff;}
    .no-chat-selected i { font-size: 40px; margin-bottom: 15px; opacity: 0.5; }
</style>

<div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar">
        <div class="sidebar-header">
            Active Chats
        </div>
        <div class="user-list" id="userList">
            <!-- Users populated by JS -->
            <div style="padding: 20px; text-align: center; color: #64748b; font-size: 13px;">Loading chats...</div>
        </div>
    </div>

    <!-- Main Chat Window -->
    <div class="chat-main" id="chatMain">
        <div class="no-chat-selected">
            <i class="fa-regular fa-comments"></i>
            Select a conversation to start chatting
        </div>
    </div>
</div>

<script>
    const rolePrefix = '{{ Auth::user()->role === "support" ? "support-panel/chat" : "admin/chat" }}';
    const usersRoute = '{{ Auth::user()->role === "support" ? route("support.chat.users") : route("admin.chat.users") }}';
    
    const userList = document.getElementById('userList');
    const chatMain = document.getElementById('chatMain');
    
    let currentUserId = null;
    let currentUserData = null;
    let lastMessageCount = 0;

    // Start polling users list
    fetchUsers();
    setInterval(fetchUsers, 5000);
    
    // Start polling messages if a chat is active
    setInterval(() => {
        if(currentUserId) fetchMessages(currentUserId, true);
    }, 3000);

    function fetchUsers() {
        fetch(usersRoute)
            .then(res => res.json())
            .then(users => {
                renderUsers(users);
            });
    }

    function renderUsers(users) {
        if(users.length === 0) {
            userList.innerHTML = '<div style="padding: 20px; text-align: center; color: #64748b; font-size: 13px;">No active chats found.</div>';
            return;
        }

        userList.innerHTML = '';
        users.forEach(user => {
            const div = document.createElement('div');
            div.className = 'user-item ' + (currentUserId === user.id ? 'active' : '');
            div.onclick = () => openChat(user);
            
            const badgeHtml = user.unread_count > 0 ? `<div class="unread-badge">${user.unread_count}</div>` : '';
            
            div.innerHTML = `
                <img src="${user.avatar}" class="user-avatar" alt="Avatar">
                <div class="user-info">
                    <div class="user-name">${user.name}</div>
                    <div class="user-role">${user.role}</div>
                </div>
                ${badgeHtml}
            `;
            userList.appendChild(div);
        });
    }

    function openChat(user) {
        currentUserId = user.id;
        currentUserData = user;
        lastMessageCount = 0; // reset

        // Hide the top bar message badge since we are now reading the message
        const topBadge = document.getElementById('msgBadge');
        if (topBadge) topBadge.style.display = 'none';
        
        // Re-render users to update active state and clear unread badge instantly on UI
        fetchUsers();
        
        // Render basic chat window structure
        chatMain.innerHTML = `
            <div class="chat-header">
                <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                    <img src="${user.avatar}" class="user-avatar" style="width: 40px; height: 40px;" alt="Avatar">
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">${user.name}</div>
                        <div style="font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600;">${user.role}</div>
                    </div>
                </div>
                <button onclick="deleteChat(${user.id})" style="background: #fee2e2; color: #ef4444; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; transition: 0.2s;">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 20px;">Loading messages...</div>
            </div>
            <div class="chat-input-area">
                <input type="text" id="messageInput" class="chat-input" placeholder="Type your reply..." onkeypress="handleKeyPress(event)">
                <button class="btn-send" onclick="sendMessage()"><i class="fa-solid fa-paper-plane" style="color: white;"></i></button>
            </div>
        `;
        
        fetchMessages(user.id);
    }

    function fetchMessages(userId, silent = false) {
        fetch(`{{ url('') }}/${rolePrefix}/${userId}/messages`)
            .then(res => res.json())
            .then(messages => {
                if(!silent || messages.length > lastMessageCount) {
                    // Play sound if new messages arrived while polling silently
                    if (silent && messages.length > lastMessageCount) {
                        if (typeof window.playNotificationSound === 'function') {
                            window.playNotificationSound();
                        }
                    }
                    
                    renderMessages(messages);
                    lastMessageCount = messages.length;
                }
            });
    }

    function renderMessages(messages) {
        const chatMessages = document.getElementById('chatMessages');
        if(!chatMessages) return; // User navigated away
        
        if(messages.length === 0) {
            chatMessages.innerHTML = '<div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 20px;">No messages yet.</div>';
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
        
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        if(!currentUserId) return;
        const messageInput = document.getElementById('messageInput');
        const text = messageInput.value.trim();
        if(!text) return;

        // Optimistic update
        const chatMessages = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = 'message message-admin';
        div.style.opacity = '0.5';
        div.innerHTML = `
            ${text}
            <div class="message-time">Sending...</div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        messageInput.value = '';

        fetch(`{{ url('') }}/${rolePrefix}/${currentUserId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            fetchMessages(currentUserId);
        });
    }

    function deleteChat(userId) {
        if(!confirm("Are you sure you want to completely delete this conversation? This action cannot be undone.")) return;
        
        fetch(`{{ url('') }}/${rolePrefix}/${userId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            currentUserId = null;
            currentUserData = null;
            
            // Reset main window
            chatMain.innerHTML = `
                <div class="no-chat-selected">
                    <i class="fa-regular fa-comments"></i>
                    Select a conversation to start chatting
                </div>
            `;
            
            // Re-fetch users sidebar to remove them
            fetchUsers();
            
            // Show toast if available
            if (typeof Toastify === 'function') {
                Toastify({
                    text: "Chat deleted successfully",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: { background: "#ef4444", borderRadius: "8px", fontWeight: "600" }
                }).showToast();
            }
        });
    }
</script>
@endsection
