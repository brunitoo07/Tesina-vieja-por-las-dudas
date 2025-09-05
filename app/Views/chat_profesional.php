<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistente Virtual Profesional - EcoVolt</title>
    <style>
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            display: none;
            flex-direction: column;
            z-index: 1000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        
        .chat-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            border-radius: 20px 20px 0 0;
        }
        
        .chat-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .bot-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            backdrop-filter: blur(10px);
        }
        
        .chat-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .chat-status {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 2px;
        }
        
        .close-chat {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .close-chat:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
            scrollbar-width: thin;
            scrollbar-color: #667eea #f1f1f1;
        }
        
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 3px;
        }
        
        .message {
            margin-bottom: 20px;
            padding: 15px 20px;
            border-radius: 20px;
            max-width: 85%;
            word-wrap: break-word;
            position: relative;
            animation: messageSlide 0.3s ease-out;
        }
        
        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
            text-align: right;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .bot-message {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .bot-message::before {
            content: '🤖';
            position: absolute;
            left: -15px;
            top: 15px;
            font-size: 20px;
        }
        
        .chat-input {
            padding: 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 15px;
            background: white;
            border-radius: 0 0 20px 20px;
        }
        
        .chat-input input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .chat-input input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .chat-input button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .chat-input button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .chat-input button:active {
            transform: translateY(0);
        }
        
        .chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            z-index: 1001;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .chat-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }
        
        .chat-toggle:active {
            transform: scale(0.95);
        }
        
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .quick-btn {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            color: #495057;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .quick-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .loading {
            display: none;
            text-align: center;
            color: #667eea;
            font-style: italic;
            padding: 20px;
        }
        
        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #667eea;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .timestamp {
            font-size: 10px;
            opacity: 0.6;
            margin-top: 5px;
        }
        
        .typing-indicator {
            display: none;
            padding: 15px 20px;
            background: white;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .typing-dots {
            display: flex;
            gap: 4px;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        
        .message-actions {
            margin-top: 10px;
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.3);
            color: #667eea;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Botón para abrir chat -->
    <button class="chat-toggle" onclick="toggleChat()" id="chatToggle">
        💬
    </button>
    
    <!-- Contenedor del chat -->
    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <div class="chat-header-content">
                <div class="bot-avatar">🤖</div>
                <div>
                    <h3>EcoVolt Assistant</h3>
                    <div class="chat-status">En línea</div>
                </div>
            </div>
            <button class="close-chat" onclick="toggleChat()">×</button>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message bot-message">
                <strong>👋 ¡Hola! Soy tu asistente virtual de EcoVolt</strong><br><br>
                ¿En qué puedo ayudarte? Puedo:
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Mostrar datos de dispositivos</li>
                    <li>Buscar por MAC</li>
                    <li>Consultar consumo de energía</li>
                    <li>Ver estado del sistema</li>
                    <li>Información del proyecto</li>
                </ul>
                
                <div class="quick-actions">
                    <button class="quick-btn" onclick="sendQuickMessage('Estado del sistema')">📊 Estado</button>
                    <button class="quick-btn" onclick="sendQuickMessage('Mostrar dispositivos')">🔌 Dispositivos</button>
                    <button class="quick-btn" onclick="sendQuickMessage('Ver consumo')">⚡ Consumo</button>
                    <button class="quick-btn" onclick="sendQuickMessage('Proyecto')">🚀 Proyecto</button>
                    <button class="quick-btn" onclick="sendQuickMessage('Ayuda')">❓ Ayuda</button>
                </div>
                
                <div class="timestamp"><?= date('H:i') ?></div>
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
        
        <div class="loading" id="loading">🤖 Asistente escribiendo...</div>
        
        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Escribe tu mensaje..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <script>
        let chatOpen = false;
        let messageCount = 0;
        
        function toggleChat() {
            const chatContainer = document.getElementById('chatContainer');
            const chatToggle = document.getElementById('chatToggle');
            
            chatOpen = !chatOpen;
            chatContainer.style.display = chatOpen ? 'flex' : 'none';
            
            if (chatOpen) {
                document.getElementById('messageInput').focus();
                chatToggle.innerHTML = '✕';
                chatToggle.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
            } else {
                chatToggle.innerHTML = '💬';
                chatToggle.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            }
        }
        
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }
        
        function sendQuickMessage(message) {
            document.getElementById('messageInput').value = message;
            sendMessage();
        }
        
        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            // Mostrar mensaje del usuario
            addMessage(message, 'user');
            input.value = '';
            
            // Mostrar indicador de escritura
            showTypingIndicator(true);
            
            // Enviar al servidor
            fetch('<?= base_url('chat/process') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                showTypingIndicator(false);
                addMessage(data.response, 'bot');
            })
            .catch(error => {
                showTypingIndicator(false);
                addMessage('❌ Error al procesar tu mensaje. Intenta de nuevo.', 'bot');
                console.error('Error:', error);
            });
        }
        
        function addMessage(text, type) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}-message`;
            
            // Formatear el texto
            let formattedText = text.replace(/\n/g, '<br>');
            
            // Agregar botones rápidos si es un mensaje del bot
            if (type === 'bot' && text.includes('Puedo ayudarte')) {
                formattedText += '<div class="quick-actions" style="margin-top: 15px;">' +
                    '<button class="quick-btn" onclick="sendQuickMessage(\'Estado del sistema\')">📊 Estado</button>' +
                    '<button class="quick-btn" onclick="sendQuickMessage(\'Mostrar dispositivos\')">🔌 Dispositivos</button>' +
                    '<button class="quick-btn" onclick="sendQuickMessage(\'Ver consumo\')">⚡ Consumo</button>' +
                    '<button class="quick-btn" onclick="sendQuickMessage(\'Proyecto\')">🚀 Proyecto</button>' +
                    '<button class="quick-btn" onclick="sendQuickMessage(\'Ayuda\')">❓ Ayuda</button>' +
                    '</div>';
            }
            
            // Agregar timestamp
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            formattedText += `<div class="timestamp">${timeString}</div>`;
            
            messageDiv.innerHTML = formattedText;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            messageCount++;
        }
        
        function showTypingIndicator(show) {
            document.getElementById('typingIndicator').style.display = show ? 'block' : 'none';
            if (show) {
                document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
            }
        }
        
        // Auto-abrir chat si hay parámetro en URL
        if (window.location.search.includes('chat=open')) {
            setTimeout(toggleChat, 1000);
        }
        
        // Efecto de partículas en el botón
        function createParticle() {
            const toggle = document.getElementById('chatToggle');
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.background = 'rgba(255,255,255,0.8)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.animation = 'particleFloat 2s ease-out forwards';
            
            const rect = toggle.getBoundingClientRect();
            particle.style.left = rect.left + rect.width / 2 + 'px';
            particle.style.top = rect.top + rect.height / 2 + 'px';
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 2000);
        }
        
        // Agregar CSS para las partículas
        const style = document.createElement('style');
        style.textContent = `
            @keyframes particleFloat {
                0% {
                    transform: translate(0, 0) scale(1);
                    opacity: 1;
                }
                100% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) scale(0);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Crear partículas cada 3 segundos
        setInterval(createParticle, 3000);
    </script>
</body>
</html>
