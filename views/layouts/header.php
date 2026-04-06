<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Mueve | Cambios Instantáneos'; ?></title>
    
    <?php if (isset($meta)): echo $meta; endif; ?>

    <!-- Tailwind CSS & Config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#d8e2ff",
                        "on-secondary-container": "#9bb8f6",
                        "on-surface": "#e1e2e7",
                        "inverse-on-surface": "#2e3134",
                        "on-tertiary-fixed-variant": "#7c2e00",
                        "surface-container-highest": "#323538",
                        "tertiary-fixed-dim": "#ffb695",
                        "tertiary": "#ffb695",
                        "tertiary-fixed": "#ffdbcc",
                        "on-secondary-fixed": "#001a41",
                        "on-error-container": "#ffdad6",
                        "surface-bright": "#37393d",
                        "surface": "#111417",
                        "inverse-primary": "#005bc0",
                        "surface-container": "#1d2023",
                        "secondary-fixed-dim": "#adc7ff",
                        "on-primary-container": "#00285b",
                        "on-tertiary": "#571e00",
                        "surface-container-high": "#272a2e",
                        "outline": "#8b90a0",
                        "surface-dim": "#111417",
                        "inverse-surface": "#e1e2e7",
                        "surface-container-low": "#191c1f",
                        "on-tertiary-container": "#4c1a00",
                        "on-secondary-fixed-variant": "#26467c",
                        "outline-variant": "rgba(65, 71, 84, 0.15)",
                        "primary-fixed-dim": "#adc7ff",
                        "surface-tint": "#adc7ff",
                        "primary-fixed": "#d8e2ff",
                        "surface-container-lowest": "#0b0e11",
                        "on-secondary": "#072f64",
                        "on-error": "#690005",
                        "secondary-container": "#29487f",
                        "error-container": "#93000a",
                        "on-background": "#e1e2e7",
                        "on-tertiary-fixed": "#351000",
                        "secondary": "#adc7ff",
                        "on-primary-fixed-variant": "#004493",
                        "surface-variant": "#323538",
                        "on-surface-variant": "#c1c6d7",
                        "on-primary-fixed": "#001a41",
                        "tertiary-container": "#ef6719",
                        "background": "#111417",
                        "primary-container": "#4a8eff",
                        "on-primary": "#002e68",
                        "primary": "#adc7ff",
                        "error": "#ffb4ab"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(50, 53, 56, 0.6);
            backdrop-filter: blur(24px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #ADC7FF 0%, #4A8EFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #ADC7FF 0%, #4A8EFF 100%);
        }
        .active-tab {
            background-color: #4A8EFF !important;
            color: #001a41 !important;
            box-shadow: 0 10px 25px -5px rgba(74, 142, 255, 0.4);
        }
        .notification {
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            background: rgba(50, 53, 56, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: slideIn 0.3s ease-out;
            max-width: 320px;
        }
        .notification.error { border-color: #ffb4ab; color: #ffb4ab; }
        .notification.success { border-color: #adc7ff; color: #adc7ff; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container">
