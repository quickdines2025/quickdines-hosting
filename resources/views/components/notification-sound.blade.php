<!-- Global Notification System -->
<audio id="global-notification-sound" src="{{ asset('notification.mp3') }}" preload="auto"></audio>
<script>
    if (!window.notificationSystemInitialized) {
        window.notificationSystemInitialized = true;
        
        window.playNotificationSound = () => {
            try {
                const audio = document.getElementById('global-notification-sound');
                if (audio) {
                    audio.muted = false; // Ensure not muted
                    audio.currentTime = 0;
                    let playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.log("Audio playback was prevented by the browser. Awaiting user interaction.");
                        });
                    }
                }
            } catch (e) {
                console.error(e);
            }
        };

        // Unlock audio context on first user interaction
        document.addEventListener('click', function unlockAudio() {
            const audio = document.getElementById('global-notification-sound');
            if(audio) {
                audio.muted = true;
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.muted = false;
                }).catch(e => {});
            }
            document.removeEventListener('click', unlockAudio);
        }, { once: true });
        
        let lastNotificationCount = 0;
        
        function pollNotifications() {
            fetch('{{ $checkRoute }}')
                .then(res => res.json())
                .then(data => {
                    let total = data.total !== undefined ? data.total : (data.messages || 0);
                    
                    if (total > lastNotificationCount) {
                        window.playNotificationSound();
                        
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: "New notification received!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#6366f1", borderRadius: "8px", fontWeight: "600" }
                            }).showToast();
                        }
                    }
                    
                    lastNotificationCount = total;
                })
                .catch(e => console.error("Notification poll error", e));
        }
        
        // Initial poll to set baseline
        fetch('{{ $checkRoute }}').then(res => res.json()).then(data => {
            lastNotificationCount = data.total !== undefined ? data.total : (data.messages || 0);
            setInterval(pollNotifications, 5000);
        }).catch(e => {});
    }
</script>
