<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<style>
    /* --- Paleta de Colores Premium (Sin Grises) --- */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --black-bg: #121212;
        --glass-bg: rgba(20, 20, 22, 0.95); /* Fondo de cristal casi opaco para máximo contraste */
        --glass-border: rgba(255, 255, 255, 0.1); /* Borde blanco translúcido */
        --text-primary: #F0F0F0; /* Blanco principal */
        --text-secondary: rgba(240, 240, 240, 0.7); /* Blanco secundario (reemplazo del gris) */
        --glow-color: rgba(212, 175, 55, 0.3);

        --border-radius: 20px;
    }

    /* --- Importación de Fuente y Estilos Base --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    
    body {
        background-color: var(--black-bg);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
        background-image: radial-gradient(circle at 50% 0%, rgba(50, 50, 50, 0.2), transparent 50%);
    }

    /* --- Animación de Entrada --- */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- Contenedor Principal con Efecto Glassmorphism --- */
    .premium-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        animation: fadeIn 0.8s ease-out forwards;
    }

    .premium-card-header {
        padding: 2rem 2.5rem;
        border-bottom: 1px solid var(--glass-border);
        text-align: center;
        color: var(--gold-light);
        font-size: 1.25rem;
        font-weight: 600;
    }
    .premium-card-header i { margin-right: 1rem; }
    .premium-card-body { padding: 2.5rem; }

    /* --- Guía de Pasos Estilo "Timeline" --- */
    .timeline-guide {
        list-style: none;
        padding-left: 0;
        position: relative;
    }
    .timeline-guide::before {
        content: '';
        position: absolute;
        top: 15px; left: 15px; bottom: 15px;
        width: 2px;
        background: var(--glass-border);
    }
    .timeline-guide li {
        margin-bottom: 2rem;
        padding-left: 45px;
        position: relative;
    }
    .timeline-guide li::before {
        content: counter(step);
        counter-increment: step;
        position: absolute;
        left: 0; top: -2px;
        width: 32px; height: 32px;
        background: var(--gold);
        color: var(--black-bg);
        border-radius: 50%;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px var(--glow-color);
    }
    .timeline-guide strong {
        color: var(--text-primary);
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }
    .timeline-guide ul {
        padding-left: 1rem;
        list-style-type: none;
        color: var(--text-secondary);
    }
    .timeline-guide ul li {
        margin-bottom: 0.5rem;
        padding-left: 1.2rem;
        font-size: 0.9rem;
        position: relative;
    }
    .timeline-guide ul li::before {
        content: '→';
        position: absolute;
        left: 0; top: 1px;
        color: var(--gold);
        background: none;
        box-shadow: none;
        width: auto;
        height: auto;
        font-weight: bold;
    }

    /* --- Panel "Importante" --- */
    .important-panel {
        background-color: rgba(212, 175, 55, 0.08);
        border: 1px solid var(--glass-border);
        border-left: 4px solid var(--gold);
        border-radius: 12px;
        padding: 1.5rem;
    }
    .important-panel h5 {
        color: var(--gold-light);
        font-weight: 600;
    }
    .important-panel p { color: var(--text-secondary); }

    /* --- Showcase del Dispositivo con Animación --- */
    .device-showcase { text-align: center; margin: 3rem 0; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 var(--glow-color); }
        70% { box-shadow: 0 0 0 15px rgba(212, 175, 55, 0); }
        100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
    }
    .device-showcase img {
        border-radius: 50%;
        animation: pulse 2.5s infinite;
    }
    .device-showcase h4 { color: var(--text-primary); }
    .device-showcase p { color: var(--text-secondary); }

    /* --- Botones --- */
    .btn-premium {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        text-decoration: none;
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-premium.btn-gold {
        background: var(--gold);
        color: var(--black-bg);
        border: none;
        box-shadow: 0 4px 20px var(--glow-color);
    }
    .btn-premium.btn-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
    }
    .btn-premium.btn-outline {
        background: transparent;
        color: var(--text-secondary);
        border: 2px solid var(--glass-border);
    }
    .btn-premium.btn-outline:hover {
        color: var(--gold-light);
        border-color: var(--gold);
    }
    .btn-premium .icon-span { margin-right: 0.75rem; transition: transform 0.3s ease; }
    .btn-premium:hover .icon-span { transform: scale(1.1); }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="premium-card">
                <div class="premium-card-header">
                    <i class="fas fa-plug"></i>Guía de Configuración de Dispositivo
                </div>
                <div class="premium-card-body">
                    
                    <ol class="timeline-guide" style="counter-reset: step;">
                        <li>
                            <strong>Preparación y Conexión</strong>
                            <ul>
                                <li>Conecta tu EcoVolt y espera el parpadeo del LED.</li>
                                <li>Desde tu teléfono, conéctate a la red WiFi <strong>"EcoVolt-Config"</strong> (contraseña:12345678).</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Configuración de Red</strong>
                            <ul>
                                <li>Una vez conectado, se abrirá un portal en tu navegador,si no es asi, en tu navegador con la red conectada busca https://192.168.4.1 ...
                                <li>Selecciona tu red WiFi de casa e ingresa la contraseña.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Finalización</strong>
                            <ul>
                                <li>El dispositivo se reiniciará para aplicar los cambios.</li>
                                <li>Anota la <strong>dirección MAC</strong> que te proporcionará.</li>
                            </ul>
                        </li>
                    </ol>

                    <div class="device-showcase">
                        <img src="<?= base_url('imagenes/logo.png') ?>" alt="Logo EcoVolt" class="img-fluid" style="max-width: 100px;">
                        <h4 class="mt-3">EcoVolt</h4>
                        <p>Sigue los pasos para configurar tu medidor inteligente.</p>
                    </div>

                    <div class="important-panel mb-4">
                        <h5><i class="fas fa-exclamation-triangle"></i> Paso Final</h5>
                        <p class="mb-0">Con la dirección MAC de tu dispositivo, haz clic en el botón de abajo para añadirlo a tu cuenta y empezar a monitorear.</p>
                    </div>
                    
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('dispositivo/agregar') ?>" class="btn-premium btn-gold">
                            <span class="icon-span"><i class="fas fa-plus"></i></span>
                            Registrar con MAC
                        </a>
                        <a href="<?= base_url('admin/dispositivos') ?>" class="btn-premium btn-outline">
                            <span class="icon-span"><i class="fas fa-arrow-left"></i></span>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
