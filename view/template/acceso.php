<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | MiEscuela</title>
    <link rel="stylesheet" href="view/css/admin.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0e1b38 0%, #1e3a6e 100%);
            padding: 32px 16px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 48px 44px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .login-logo { font-size: 3rem; margin-bottom: 8px; }
        .login-card h2 {
            color: #0e1b38;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .login-card > p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 32px;
        }
        .login-group {
            text-align: left;
            margin-bottom: 18px;
        }
        .login-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 7px;
        }
        .login-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .login-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #0e1b38;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-login:hover { background: #3b82f6; transform: translateY(-1px); }
        .error-msg {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 18px;
            text-align: left;
        }
        .volver-link {
            display: inline-block;
            margin-top: 24px;
            color: #64748b;
            font-size: 0.88rem;
            text-decoration: none;
        }
        .volver-link:hover { color: #0e1b38; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Iniciar Sesión</h2>
        <p>Ingresa tu usuario o correo y contraseña</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">❌ Usuario o contraseña incorrectos.</div>
        <?php endif; ?>

        <form action="index.php?ruta=procesar-login" method="POST">
            <div class="login-group">
                <label for="login_usuario">Usuario o correo electrónico</label>
                <input type="text" id="login_usuario" name="usuario"
                    placeholder="admin o tucorreo@escuela.com"
                    value="<?php echo isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : ''; ?>"
                    required autofocus>
            </div>
            <div class="login-group">
                <label for="login_password">Contraseña</label>
                <input type="password" id="login_password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>

        <a href="index.php" class="volver-link">← Volver al sitio</a>
    </div>
</body>
</html>