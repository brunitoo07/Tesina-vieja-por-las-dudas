<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Control de Focos</title>
  <style>
    body {
      background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
      font-family: 'Segoe UI', Arial, sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }
    .volver-btn {
      display: inline-block;
      margin-top: 32px;
      margin-bottom: 0;
      background: linear-gradient(90deg, #64748b 0%, #38bdf8 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 28px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(100,116,139,0.10);
      transition: background 0.2s, transform 0.1s;
      text-decoration: none;
    }
    .volver-btn:hover {
      background: linear-gradient(90deg, #334155 0%, #0ea5e9 100%);
      transform: translateY(-2px) scale(1.04);
      color: #fff;
    }
    .container {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
      padding: 40px 32px 32px 32px;
      max-width: 430px;
      width: 100%;
      text-align: center;
      margin-top: 32px;
    }
    h2 {
      color: #1e293b;
      margin-bottom: 10px;
      font-size: 2rem;
      letter-spacing: 1px;
    }
    .mac {
      background: #f1f5f9;
      color: #2563eb;
      font-weight: bold;
      border-radius: 8px;
      padding: 10px 0;
      margin-bottom: 24px;
      font-family: 'Fira Mono', monospace;
      font-size: 1.1rem;
      letter-spacing: 2px;
      box-shadow: 0 2px 8px rgba(37,99,235,0.07);
    }
    .foco-control {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 32px;
      margin-top: 10px;
      position: relative;
    }
    .foco-svg {
      width: 70px;
      height: 110px;
      margin-bottom: 10px;
      transition: filter 0.3s, opacity 0.3s;
      filter: drop-shadow(0 0 0px #facc15);
      opacity: 0.7;
      z-index: 2;
    }
    .foco-encendido {
      filter: drop-shadow(0 0 32px #fde047) drop-shadow(0 0 16px #facc15);
      opacity: 1;
      animation: focoGlow 1.2s infinite alternate;
    }
    .foco-apagado {
      filter: grayscale(0.7) brightness(0.7);
      opacity: 0.5;
      animation: none;
    }
    @keyframes focoGlow {
      0% { filter: drop-shadow(0 0 16px #fde047) drop-shadow(0 0 8px #facc15); }
      100% { filter: drop-shadow(0 0 48px #fde047) drop-shadow(0 0 32px #facc15); }
    }
    .haz-luz {
      position: absolute;
      left: 50%;
      top: 80px;
      transform: translateX(-50%);
      width: 80px;
      height: 60px;
      z-index: 1;
      pointer-events: none;
      opacity: 0;
      transition: opacity 0.4s;
    }
    .haz-encendido {
      opacity: 0.7;
      animation: hazAnim 1.2s infinite alternate;
    }
    @keyframes hazAnim {
      0% { opacity: 0.5; }
      100% { opacity: 0.85; }
    }
    .estado {
      margin-bottom: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      letter-spacing: 1px;
      min-height: 32px;
      transition: color 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .estado-encendido {
      color: #facc15;
      animation: popIn 0.4s;
    }
    .estado-apagado {
      color: #64748b;
      animation: popIn 0.4s;
    }
    @keyframes popIn {
      0% { transform: scale(0.7); opacity: 0; }
      80% { transform: scale(1.1); opacity: 1; }
      100% { transform: scale(1); }
    }
    .btn-row {
      display: flex;
      gap: 16px;
      justify-content: center;
      margin-bottom: 8px;
    }
    .foco-btn {
      border: none;
      border-radius: 8px;
      padding: 14px 28px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(37,99,235,0.10);
      transition: background 0.2s, transform 0.1s;
      color: #fff;
    }
    .foco-on {
      background: linear-gradient(90deg, #22c55e 0%, #facc15 100%);
    }
    .foco-on:hover {
      background: linear-gradient(90deg, #16a34a 0%, #fde047 100%);
      transform: translateY(-2px) scale(1.04);
    }
    .foco-off {
      background: linear-gradient(90deg, #ef4444 0%, #64748b 100%);
    }
    .foco-off:hover {
      background: linear-gradient(90deg, #b91c1c 0%, #334155 100%);
      transform: translateY(-2px) scale(1.04);
    }
    @media (max-width: 500px) {
      .container { padding: 20px 5px; }
      .foco-btn { padding: 12px 10px; font-size: 1rem; }
      .volver-btn { padding: 10px 8px; font-size: 0.95rem; }
      .foco-svg { width: 50px; height: 80px; }
      .haz-luz { width: 60px; height: 40px; top: 60px; }
    }
  </style>
</head>
<body>
  <a href="<?= base_url('dispositivo') ?>" class="volver-btn">&larr; Volver a Dispositivos</a>
  <div class="container">
    <h2>Control de Focos desde Web</h2>
    <div class="mac">MAC: <?= htmlspecialchars(isset($mac) ? $mac : '') ?></div>
    <div class="foco-control">
      <svg id="svg-foco-1" class="foco-svg foco-apagado" viewBox="0 0 64 96" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="32" cy="40" rx="22" ry="28" fill="#facc15" fill-opacity="0.7"/>
        <ellipse cx="32" cy="40" rx="18" ry="24" fill="#fffde4" fill-opacity="0.7"/>
        <rect x="24" y="68" width="16" height="16" rx="6" fill="#64748b"/>
        <rect x="28" y="84" width="8" height="8" rx="3" fill="#334155"/>
        <ellipse cx="32" cy="40" rx="12" ry="16" fill="#fff" fill-opacity="0.9"/>
      </svg>
      <svg id="haz-luz-1" class="haz-luz" viewBox="0 0 80 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="40" cy="10" rx="30" ry="10" fill="#fde047" fill-opacity="0.5"/>
        <ellipse cx="40" cy="30" rx="36" ry="18" fill="#fde047" fill-opacity="0.25"/>
        <ellipse cx="40" cy="50" rx="40" ry="10" fill="#fde047" fill-opacity="0.13"/>
      </svg>
      <div id="estadoFoco1" class="estado estado-apagado">LUZ APAGADA</div>
      <div class="btn-row">
        <button id="btn-on-1" class="foco-btn foco-on" onclick="controlarFoco(1, 'on')">Encender Foco 1</button>
        <button id="btn-off-1" class="foco-btn foco-off" onclick="controlarFoco(1, 'off')">Apagar Foco 1</button>
      </div>
    </div>
    <div class="foco-control">
      <svg id="svg-foco-2" class="foco-svg foco-apagado" viewBox="0 0 64 96" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="32" cy="40" rx="22" ry="28" fill="#facc15" fill-opacity="0.7"/>
        <ellipse cx="32" cy="40" rx="18" ry="24" fill="#fffde4" fill-opacity="0.7"/>
        <rect x="24" y="68" width="16" height="16" rx="6" fill="#64748b"/>
        <rect x="28" y="84" width="8" height="8" rx="3" fill="#334155"/>
        <ellipse cx="32" cy="40" rx="12" ry="16" fill="#fff" fill-opacity="0.9"/>
      </svg>
      <svg id="haz-luz-2" class="haz-luz" viewBox="0 0 80 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="40" cy="10" rx="30" ry="10" fill="#fde047" fill-opacity="0.5"/>
        <ellipse cx="40" cy="30" rx="36" ry="18" fill="#fde047" fill-opacity="0.25"/>
        <ellipse cx="40" cy="50" rx="40" ry="10" fill="#fde047" fill-opacity="0.13"/>
      </svg>
      <div id="estadoFoco2" class="estado estado-apagado">LUZ APAGADA</div>
      <div class="btn-row">
        <button id="btn-on-2" class="foco-btn foco-on" onclick="controlarFoco(2, 'on')">Encender Foco 2</button>
        <button id="btn-off-2" class="foco-btn foco-off" onclick="controlarFoco(2, 'off')">Apagar Foco 2</button>
      </div>
    </div>
  </div>
  <script>
    // Estado por foco
    let estadoFocos = {1: false, 2: false};
    function controlarFoco(foco, estado) {
      fetch(`http://192.168.2.109/rele?foco=${foco}&estado=${estado}`)
        .then(response => response.text())
        .then(data => {
          estadoFocos[foco] = (estado === 'on');
          actualizarFoco(foco);
        });
    }
    function actualizarFoco(foco) {
      const svg = document.getElementById('svg-foco-' + foco);
      const haz = document.getElementById('haz-luz-' + foco);
      const estadoDiv = document.getElementById('estadoFoco' + foco);
      if (estadoFocos[foco]) {
        svg.classList.add('foco-encendido');
        svg.classList.remove('foco-apagado');
        haz.classList.add('haz-encendido');
        estadoDiv.textContent = 'LUZ ENCENDIDA';
        estadoDiv.classList.add('estado-encendido');
        estadoDiv.classList.remove('estado-apagado');
      } else {
        svg.classList.remove('foco-encendido');
        svg.classList.add('foco-apagado');
        haz.classList.remove('haz-encendido');
        estadoDiv.textContent = 'LUZ APAGADA';
        estadoDiv.classList.remove('estado-encendido');
        estadoDiv.classList.add('estado-apagado');
      }
    }

    
    // Inicializar estados
    actualizarFoco(1);
    actualizarFoco(2);
    
    function obtenerEstado() {
  fetch('http://192.168.2.109/estado')
    .then(response => response.json())
    .then(data => {
      estadoFocos[1] = data.foco1;
      estadoFocos[2] = data.foco2;
      actualizarFoco(1);
      actualizarFoco(2);
    })
    .catch(err => console.log('Error al obtener estado:', err));
}

// Consultar al cargar
obtenerEstado();

// Consultar cada 5 segundos (opcional para tiempo real)
setInterval(obtenerEstado, 5000);

  </script>
</body>
</html>
