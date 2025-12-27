<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Frotamaster - Offline</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 20px; text-align: center; }
        .container { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); max-width: 400px; width: 100%; }
        h1 { font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937; }
        p { margin-bottom: 1.5rem; color: #4b5563; }
        .icon { width: 80px; height: 80px; margin-bottom: 1rem; fill: #ef4444; }
        .btn { display: inline-block; background-color: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background-color 0.2s; }
        .btn:hover { background-color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3zM8 13h2.55v3h2.9v-3H16l-4-4z"/>
        </svg>
        
        <h1>Sem Conexão</h1>
        <p>Não foi possível contactar o servidor do Frotamaster. Verifique a sua internet ou tente novamente em instantes.</p>
        
        <a href="/" class="btn" onclick="window.location.reload(); return false;">Tentar Novamente</a>
    </div>
</body>
</html>
