<?php /* AMI Grocery — Floating Chatbot Widget (include on any page) */ ?>
<style>
#ami-chat-btn {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #28a745, #1a6b30);
    color: white; border: none; cursor: pointer;
    box-shadow: 0 4px 16px rgba(40,167,69,0.45);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; transition: transform 0.2s, box-shadow 0.2s;
}
#ami-chat-btn:hover { transform: scale(1.1); box-shadow: 0 6px 22px rgba(40,167,69,0.55); }

#ami-chat-window {
    position: fixed; bottom: 90px; right: 24px; z-index: 9998;
    width: 330px; max-height: 480px;
    background: #fff; border-radius: 18px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    display: none; flex-direction: column; overflow: hidden;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    border: 1.5px solid #e8f5e9;
}
#ami-chat-header {
    background: linear-gradient(135deg, #28a745, #1a6b30);
    color: white; padding: 14px 16px;
    display: flex; align-items: center; gap: 10px;
}
#ami-chat-header .ami-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
#ami-chat-header .ami-info { flex: 1; }
#ami-chat-header .ami-name { font-weight: 700; font-size: 0.95rem; line-height: 1.2; }
#ami-chat-header .ami-status { font-size: 0.75rem; opacity: 0.85; }
#ami-chat-close {
    background: none; border: none; color: white; font-size: 1.1rem;
    cursor: pointer; padding: 2px 6px; border-radius: 50%; opacity: 0.8;
}
#ami-chat-close:hover { opacity: 1; background: rgba(255,255,255,0.15); }

#ami-chat-messages {
    flex: 1; overflow-y: auto; padding: 14px 12px;
    display: flex; flex-direction: column; gap: 10px;
    min-height: 200px; max-height: 300px;
    background: #f9fdf9;
}
.ami-msg { display: flex; gap: 8px; align-items: flex-end; max-width: 85%; }
.ami-msg.bot { align-self: flex-start; }
.ami-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.ami-msg .bubble {
    padding: 9px 13px; border-radius: 16px; font-size: 0.85rem;
    line-height: 1.45; word-break: break-word;
}
.ami-msg.bot .bubble {
    background: white; color: #1a2a3a;
    border: 1.5px solid #e0f0e3;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.ami-msg.user .bubble {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white; border-bottom-right-radius: 4px;
}
.ami-bot-icon {
    width: 28px; height: 28px; border-radius: 50%;
    background: #e8f5e9; color: #28a745;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; flex-shrink: 0;
}
.ami-typing { display: flex; align-items: center; gap: 4px; padding: 10px 13px; }
.ami-typing span {
    width: 7px; height: 7px; border-radius: 50%; background: #28a745;
    animation: ami-bounce 1s infinite;
}
.ami-typing span:nth-child(2) { animation-delay: 0.15s; }
.ami-typing span:nth-child(3) { animation-delay: 0.3s; }
@keyframes ami-bounce { 0%,80%,100%{transform:translateY(0);opacity:0.5} 40%{transform:translateY(-6px);opacity:1} }

#ami-chat-input-row {
    display: flex; gap: 8px; padding: 10px 12px;
    border-top: 1.5px solid #e8f5e9; background: white;
}
#ami-chat-input {
    flex: 1; border: 1.5px solid #e0e0e0; border-radius: 20px;
    padding: 8px 14px; font-size: 0.85rem; outline: none;
    transition: border-color 0.2s;
}
#ami-chat-input:focus { border-color: #28a745; }
#ami-chat-send {
    width: 36px; height: 36px; border-radius: 50%;
    background: #28a745; color: white; border: none;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0; transition: background 0.2s;
}
#ami-chat-send:hover { background: #1e7e34; }

.ami-quick-replies { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 12px 10px; }
.ami-quick-reply {
    background: #e8f5e9; color: #1a6b30; border: 1px solid #c8e6c9;
    border-radius: 16px; padding: 5px 12px; font-size: 0.78rem;
    cursor: pointer; transition: background 0.15s;
}
.ami-quick-reply:hover { background: #c8e6c9; }
</style>

<!-- Chat toggle button -->
<button id="ami-chat-btn" title="Chat with us" aria-label="Open chat">
    <i class="fas fa-comments"></i>
</button>

<!-- Chat window -->
<div id="ami-chat-window">
    <div id="ami-chat-header">
        <div class="ami-avatar"><i class="fas fa-store"></i></div>
        <div class="ami-info">
            <div class="ami-name">AMI Assistant</div>
            <div class="ami-status"><span style="color:#a8e6b0;">●</span> Online</div>
        </div>
        <button id="ami-chat-close" aria-label="Close chat"><i class="fas fa-times"></i></button>
    </div>

    <div id="ami-chat-messages">
        <!-- Messages injected by JS -->
    </div>

    <div class="ami-quick-replies" id="ami-quick-replies">
        <button class="ami-quick-reply">🚚 Delivery info</button>
        <button class="ami-quick-reply">↩️ Return policy</button>
        <button class="ami-quick-reply">💳 Payment options</button>
        <button class="ami-quick-reply">🕐 Opening hours</button>
    </div>

    <div id="ami-chat-input-row">
        <input type="text" id="ami-chat-input" placeholder="Ask me anything…" autocomplete="off" maxlength="300">
        <button id="ami-chat-send"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
(function() {
    const btn       = document.getElementById('ami-chat-btn');
    const win       = document.getElementById('ami-chat-window');
    const closeBtn  = document.getElementById('ami-chat-close');
    const messages  = document.getElementById('ami-chat-messages');
    const input     = document.getElementById('ami-chat-input');
    const sendBtn   = document.getElementById('ami-chat-send');
    const quickWrap = document.getElementById('ami-quick-replies');

    let opened = false;

    function toggleChat() {
        opened = !opened;
        win.style.display = opened ? 'flex' : 'none';
        btn.innerHTML = opened ? '<i class="fas fa-times"></i>' : '<i class="fas fa-comments"></i>';
        if (opened && messages.children.length === 0) {
            addBotMsg('Hi there! 👋 I\'m the AMI Grocery assistant. Ask me about delivery, returns, payments, or anything else!');
        }
        if (opened) input.focus();
    }

    function addBotMsg(text) {
        const div = document.createElement('div');
        div.className = 'ami-msg bot';
        div.innerHTML = '<div class="ami-bot-icon"><i class="fas fa-store"></i></div><div class="bubble">' + text + '</div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function addUserMsg(text) {
        quickWrap.style.display = 'none';
        const div = document.createElement('div');
        div.className = 'ami-msg user';
        div.innerHTML = '<div class="bubble">' + escHtml(text) + '</div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() {
        const div = document.createElement('div');
        div.className = 'ami-msg bot'; div.id = 'ami-typing';
        div.innerHTML = '<div class="ami-bot-icon"><i class="fas fa-store"></i></div><div class="bubble ami-typing"><span></span><span></span><span></span></div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTyping() {
        const t = document.getElementById('ami-typing');
        if (t) t.remove();
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function sendMessage(text) {
        text = text.trim();
        if (!text) return;
        input.value = '';
        addUserMsg(text);
        showTyping();

        const fd = new FormData();
        fd.append('message', text);
        fetch('/grocery_mngmnt/lib/routes/chat/chat.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => { hideTyping(); addBotMsg(data.reply || 'Sorry, I couldn\'t get a response.'); })
            .catch(() => { hideTyping(); addBotMsg('Oops! Something went wrong. Please try again.'); });
    }

    btn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);
    sendBtn.addEventListener('click', function() { sendMessage(input.value); });
    input.addEventListener('keypress', function(e) { if (e.key === 'Enter') sendMessage(input.value); });

    quickWrap.querySelectorAll('.ami-quick-reply').forEach(function(qr) {
        qr.addEventListener('click', function() { sendMessage(this.textContent.replace(/^[^\w]+/, '')); });
    });
})();
</script>
