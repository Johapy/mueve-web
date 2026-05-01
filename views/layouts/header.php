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
        /* =========================================
           PREMIUM SELECT (Appearance: base-select)
           Inspirado en el ejemplo del usuario
           ========================================= */
        select,
        ::picker(select) {
            appearance: base-select;
        }

        select {
            background-color: #1d2023; /* surface-container */
            color: #e1e2e7; /* on-surface */
            border: 1px solid rgba(65, 71, 84, 0.15); /* outline-variant */
            border-radius: 1rem;
            padding: 14px 18px;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: 0.4s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        select:hover,
        select:focus {
            background-color: #272a2e; /* surface-container-high */
            border-color: #adc7ff; /* primary */
        }

        select::picker-icon {
            color: #c1c6d7; /* on-surface-variant */
            transition: 0.4s rotate;
        }

        select:open::picker-icon {
            rotate: 180deg;
        }

        ::picker(select) {
            background-color: #111417; /* surface */
            border: 1px solid rgba(65, 71, 84, 0.3);
            border-radius: 1rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            padding: 8px;
            opacity: 0;
            transition: all 0.4s allow-discrete;
            top: calc(anchor(bottom) + 8px);
            left: anchor(left);
            width: anchor-size(width);
        }

        :open::picker(select) {
            opacity: 1;
        }

        @starting-style {
            :open::picker(select) {
                opacity: 0;
            }
        }

        option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 2px;
            border-radius: 0.75rem;
            background-color: transparent;
            color: #c1c6d7;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: 0.4s;
        }

        option:hover,
        option:focus {
            background-color: rgba(255, 255, 255, 0.05);
            color: #e1e2e7;
            outline: none;
        }

        option:checked {
            background-color: rgba(173, 199, 255, 0.1);
            color: #adc7ff;
            font-weight: bold;
        }

        option .icon {
            font-size: 1.6rem;
        }

        option::checkmark {
            content: "☑️";
            margin-left: auto;
        }

        selectedcontent .icon {
            display: none;
        }

        @supports not (appearance: base-select) {
            .select-fallback-msg {
                display: block !important;
                background: #4c1a00;
                color: #ffb695;
                padding: 10px;
                border-radius: 12px;
                font-size: 12px;
                margin-top: 8px;
                border: 1px solid #ef6719;
            }
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container">
