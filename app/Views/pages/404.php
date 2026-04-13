<!DOCTYPE html>
<html lang="es" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NekoraList - 404</title>
  <link rel="icon" href="img/icon3.webp">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      background:
        radial-gradient(circle at top, rgba(56,189,248,0.18), transparent 30%),
        radial-gradient(circle at bottom right, rgba(168,85,247,0.16), transparent 28%),
        linear-gradient(180deg, #09090f 0%, #0f172a 100%);
      color: #e5eefb;
      font-family: Inter, system-ui, sans-serif;
    }
  </style>
</head>
<body class="flex items-center justify-center px-6 py-16">
  <main class="w-full max-w-3xl rounded-[32px] border border-white/10 bg-slate-950/75 p-8 shadow-[0_30px_80px_rgba(0,0,0,0.45)] backdrop-blur-xl md:p-12">
    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/25 bg-cyan-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200">Error 404</div>
    <h1 class="mt-6 text-4xl font-black tracking-tight text-white md:text-6xl">La ruta no existe</h1>
    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 md:text-base">
      La página que intentaste abrir no fue encontrada. Si agregaste texto extra al enlace o la URL está mal escrita, te enviamos aquí para evitar mostrar contenido incorrecto.
    </p>

    <?php if (!empty($requestedPath)): ?>
      <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
        Ruta solicitada: <span class="font-semibold text-cyan-200">/<?= e($requestedPath) ?></span>
      </div>
    <?php endif; ?>

    <div class="mt-8 flex flex-wrap gap-4">
      <a href="<?= route_path('home') ?>" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-6 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">Volver al inicio</a>
      <a href="<?= route_path('series') ?>" class="inline-flex items-center justify-center rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/8">Ir a series</a>
      <a href="<?= route_path('movies') ?>" class="inline-flex items-center justify-center rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/8">Ir a películas</a>
    </div>
  </main>
</body>
</html>
