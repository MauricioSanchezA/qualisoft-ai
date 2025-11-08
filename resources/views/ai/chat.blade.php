@extends('layouts.app')

@section('title', 'Asistente de Calidad de Software')
@section('page-title', 'Asistente de Calidad de Software')

@push('styles')
<style>
/* === CONTENEDOR PRINCIPAL === */
.chat-container {
  max-width: 850px;
  margin: 2.5rem auto;
  background: #bee7f3;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.78);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}

/* === ENCABEZADO === */
.chat-header {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: #fff;
  padding: 1.4rem;
  text-align: center;
  font-weight: 600;
  font-size: 1.2rem;
  letter-spacing: 0.3px;
}
.chat-header small {
  display: block;
  font-weight: normal;
  font-size: 0.85rem;
  color: #dbe4ff;
}

/* === CUERPO DEL CHAT === */
.chat-body {
  height: 480px;
  overflow-y: auto;
  padding: 1.5rem;
  background-color: #f5f7fa;
  scroll-behavior: smooth;
  line-height: 1.6;
}
.chat-body::-webkit-scrollbar { width: 6px; }
.chat-body::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 4px;
}

/* === MENSAJES === */
.chat-message {
  display: flex;
  align-items: flex-end;
  margin-bottom: 1rem;
  gap: 0.8rem;
  animation: fadeInUp 0.3s ease-in;
}
.chat-message.user { justify-content: flex-end; }
.chat-message.ai { justify-content: flex-start; }
.chat-avatar img {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e1e5ea;
}

/* === BURBUJAS === */
.chat-bubble {
  max-width: 70%;
  padding: 0.9rem 1.1rem;
  border-radius: 14px;
  font-size: 0.96rem;
  line-height: 1.6;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.chat-message.user .chat-bubble {
  background-color: #007bff;
  color: #fff;
  border-bottom-right-radius: 4px;
}
.chat-message.ai .chat-bubble {
  background-color: #e9ecef;
  color: #212529;
  border-bottom-left-radius: 4px;
}

/* === PIE DEL CHAT === */
.chat-footer {
  padding: 1rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 10px;
  background-color: #fafafa;
}
.chat-footer input {
  flex: 1;
  border-radius: 8px;
  border: 1px solid #ccc;
  padding: 0.8rem 1rem;
  font-size: 0.95rem;
  outline: none;
}
.chat-footer input:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0,123,255,0.15);
}
.chat-footer button {
  padding: 0.7rem 1.2rem;
  font-weight: 600;
  border-radius: 8px;
  transition: 0.2s;
}
.chat-footer button:hover { transform: scale(1.05); }

/* === ANIMACIÓN DE APARICIÓN === */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === EFECTO DE PUNTOS “ESCRIBIENDO...” === */
.typing-dots {
  display: inline-flex;
  gap: 3px;
  align-items: center;
}
.typing-dots span {
  width: 6px;
  height: 6px;
  background: #888;
  border-radius: 50%;
  animation: blink 1.4s infinite both;
}
.typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-dots span:nth-child(3) { animation-delay: 0.4s; }

@keyframes blink {
  0%, 80%, 100% { opacity: 0; }
  40% { opacity: 1; }
}
</style>

@endpush

@section('content')
<div class="chat-container">
    <div class="chat-header">
        💬 Qualisoft AI — Asistente en Calidad de Software (ISO/IEC 25010 y ISO/IEC 25023)
        <small>Potenciado por <strong>CometAPI</strong></small>
    </div>

    <div id="chatBody" class="chat-body">
        @foreach($history as $item)
            <div class="chat-message user">
                <div class="chat-bubble">
                    <strong>Tú:</strong> {{ $item->user_message }}
                </div>
                <div class="chat-avatar">
                    <img src="{{ asset('images/user-icon.png') }}" alt="Usuario">
                </div>
            </div>
            <div class="chat-message ai">
                <div class="chat-avatar">
                    <img src="{{ asset('images/ai-icon.png') }}" alt="IA">
                </div>
                <div class="chat-bubble">
                    <strong>Qualisoft AI:</strong> {{ $item->ai_reply }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="chat-footer">
        <input id="userMessage" type="text" placeholder="Escribe tu mensaje..." autocomplete="off" />
        <button id="sendBtn" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Enviar
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('userMessage');
  const chatBody = document.getElementById('chatBody');
  const sendBtn = document.getElementById('sendBtn');

  sendBtn.addEventListener('click', sendMessage);
  input.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

  async function sendMessage() {
    const message = input.value.trim();
    if (!message) return;

    appendMessage('user', message);
    input.value = '';
    scrollToBottom();

    // Mostrar animación de "escribiendo..."
    const typingEl = appendTypingIndicator();

    try {
      const response = await fetch("{{ route('ai.chat.send') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
      });

      const data = await response.json();

      // Eliminar el indicador antes de mostrar la respuesta
      typingEl.remove();

      appendMessage('ai', data.reply || '⚠️ No se recibió respuesta del servidor.');
      scrollToBottom();
    } catch (error) {
      typingEl.remove();
      appendMessage('ai', '⚠️ Error al conectar con el servicio.');
    }
  }

  function appendMessage(sender, text) {
    const html = sender === 'user'
      ? `<div class="chat-message user">
           <div class="chat-bubble"><strong>Tú:</strong> ${text}</div>
           <div class="chat-avatar"><img src="{{ asset('images/user-icon.png') }}" alt="Usuario"></div>
         </div>`
      : `<div class="chat-message ai">
           <div class="chat-avatar"><img src="{{ asset('images/ai-icon.png') }}" alt="IA"></div>
           <div class="chat-bubble"><strong>Qualisoft AI:</strong> ${text}</div>
         </div>`;
    chatBody.insertAdjacentHTML('beforeend', html);
  }

  function appendTypingIndicator() {
    const html = `
      <div class="chat-message ai typing">
        <div class="chat-avatar"><img src="{{ asset('images/ai-icon.png') }}" alt="IA"></div>
        <div class="chat-bubble">
          <div class="typing-dots">
            <span></span><span></span><span></span>
          </div>
        </div>
      </div>`;
    chatBody.insertAdjacentHTML('beforeend', html);
    scrollToBottom();
    return chatBody.querySelector('.chat-message.typing');
  }

  function scrollToBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
  }
});
</script>

@endpush
