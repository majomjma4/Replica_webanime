<!DOCTYPE html>
<html class="dark" lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/icon3.webp" />
    <title>NekoraList - Personaje</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-background text-on-surface" style="background:#0e0e0e;color:#e7e5e4;">
    <main class="max-w-6xl mx-auto px-6 py-10">
      <a href="javascript:history.back()" class="text-sm text-violet-400">&larr; Volver</a>
      <div class="mt-6 grid md:grid-cols-2 gap-8">
        <div>
          <img id="char-image" class="w-full max-w-md border border-zinc-700 object-cover" alt="Personaje" />
        </div>
        <div class="space-y-4">
          <h1 id="char-title" class="text-4xl font-bold">Personaje</h1>
          <div id="char-info" class="space-y-2 text-sm text-zinc-300"></div>
          <p id="char-about" class="text-zinc-300 leading-relaxed"></p>
        </div>
      </div>
    </main>
    <script src="../assets/js/character-data.js"></script>
    <script>document.addEventListener("DOMContentLoaded",()=>window.AniDexCharacterData&&window.AniDexCharacterData.init());</script>
    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
  </body>
</html>


