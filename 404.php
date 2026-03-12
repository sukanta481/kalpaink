<?php
http_response_code(404);
require_once 'config.php';
$page_title = 'Page Not Found';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | <?php echo SITE_NAME; ?></title>
    <?php 
        $fav404 = SITE_FAVICON ?? getSitePath('assets/images/favicon.png');
        $fav404Ext = strtolower(pathinfo(parse_url($fav404, PHP_URL_PATH), PATHINFO_EXTENSION));
        $fav404Type = ($fav404Ext === 'ico') ? 'image/x-icon' : (($fav404Ext === 'svg') ? 'image/svg+xml' : 'image/png');
        $fav404Version = defined('SITE_FAVICON_VERSION') ? SITE_FAVICON_VERSION : time();
    ?>
    <link rel="icon" type="<?php echo $fav404Type; ?>" href="<?php echo $fav404; ?>?v=<?php echo $fav404Version; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0A0A0A;
            color: #fff;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Floating particles background */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: drift linear infinite;
        }

        @keyframes drift {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }

        .error-container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px 20px;
        }

        /* Logo */
        .error-logo {
            width: 140px;
            margin-bottom: 40px;
            opacity: 0.8;
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Glitch 404 */
        .error-code {
            font-size: clamp(100px, 20vw, 200px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -5px;
            position: relative;
            color: #fff;
            animation: glitchShake 3s ease-in-out infinite;
        }

        .error-code::before,
        .error-code::after {
            content: '404';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .error-code::before {
            color: #ff006e;
            animation: glitchLeft 2s ease-in-out infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }

        .error-code::after {
            color: #00d4ff;
            animation: glitchRight 2.5s ease-in-out infinite;
            clip-path: polygon(0 65%, 100% 65%, 100% 100%, 0 100%);
        }

        @keyframes glitchShake {
            0%, 100% { transform: translate(0); }
            2% { transform: translate(-3px, 2px); }
            4% { transform: translate(3px, -2px); }
            6% { transform: translate(0); }
            50% { transform: translate(0); }
            52% { transform: translate(2px, -3px); }
            54% { transform: translate(-2px, 3px); }
            56% { transform: translate(0); }
        }

        @keyframes glitchLeft {
            0%, 100% { transform: translate(0); }
            2% { transform: translate(-5px, 0); }
            4% { transform: translate(0); }
            50% { transform: translate(0); }
            52% { transform: translate(-4px, 0); }
            54% { transform: translate(0); }
        }

        @keyframes glitchRight {
            0%, 100% { transform: translate(0); }
            3% { transform: translate(5px, 0); }
            5% { transform: translate(0); }
            53% { transform: translate(4px, 0); }
            55% { transform: translate(0); }
        }

        /* Scan line */
        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            animation: scanDown 4s linear infinite;
            z-index: 2;
            pointer-events: none;
        }

        @keyframes scanDown {
            0% { top: -4px; }
            100% { top: 100%; }
        }

        /* Text */
        .error-subtitle {
            font-size: 1.1rem;
            font-weight: 300;
            color: #888;
            margin: 16px 0 8px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .error-message {
            font-size: 1rem;
            color: #555;
            max-width: 400px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* Buttons */
        .error-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: #fff;
            color: #0A0A0A;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            border: 2px solid #fff;
        }

        .btn-home:hover {
            background: transparent;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: transparent;
            color: #888;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 50px;
            border: 1px solid #333;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-back:hover {
            color: #fff;
            border-color: #666;
            transform: translateY(-2px);
        }

        /* Corner accents */
        .corner {
            position: fixed;
            width: 60px;
            height: 60px;
            border-color: rgba(255, 255, 255, 0.06);
            border-style: solid;
            z-index: 1;
        }

        .corner-tl { top: 30px; left: 30px; border-width: 2px 0 0 2px; }
        .corner-tr { top: 30px; right: 30px; border-width: 2px 2px 0 0; }
        .corner-bl { bottom: 30px; left: 30px; border-width: 0 0 2px 2px; }
        .corner-br { bottom: 30px; right: 30px; border-width: 0 2px 2px 0; }

        /* Timer */
        .redirect-hint {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            color: #444;
            letter-spacing: 1px;
        }

        .redirect-hint span {
            color: #888;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .error-logo { width: 100px; margin-bottom: 24px; }
            .error-subtitle { font-size: 0.9rem; letter-spacing: 2px; }
            .corner { width: 30px; height: 30px; }
            .corner-tl { top: 16px; left: 16px; }
            .corner-tr { top: 16px; right: 16px; }
            .corner-bl { bottom: 16px; left: 16px; }
            .corner-br { bottom: 16px; right: 16px; }
        }
    </style>
</head>
<body>

    <!-- Scan line effect -->
    <div class="scanline"></div>

    <!-- Corner brackets -->
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <!-- Floating particles -->
    <div class="particles" id="particles"></div>

    <!-- Main content -->
    <div class="error-container">
        <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" class="error-logo">

        <div class="error-code">404</div>

        <p class="error-subtitle">Lost in the void</p>
        <p class="error-message">The page you're looking for has drifted into another dimension. Let's get you back on track.</p>

        <div class="error-actions">
            <a href="<?php echo SITE_URL; ?>" class="btn-home">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Go Home
            </a>
            <button class="btn-back" onclick="history.back()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Go Back
            </button>
        </div>
    </div>

    <div class="redirect-hint">Auto-redirecting in <span id="countdown">10</span>s</div>

    <script>
        // Generate floating particles
        (function () {
            var container = document.getElementById('particles');
            for (var i = 0; i < 40; i++) {
                var p = document.createElement('div');
                p.className = 'particle';
                p.style.left = Math.random() * 100 + '%';
                p.style.width = p.style.height = (Math.random() * 3 + 2) + 'px';
                p.style.animationDuration = (Math.random() * 8 + 6) + 's';
                p.style.animationDelay = (Math.random() * 6) + 's';
                container.appendChild(p);
            }
        })();

        // Auto-redirect countdown
        (function () {
            var seconds = 10;
            var el = document.getElementById('countdown');
            var timer = setInterval(function () {
                seconds--;
                el.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = '<?php echo SITE_URL; ?>';
                }
            }, 1000);
        })();
    </script>

</body>
</html>
