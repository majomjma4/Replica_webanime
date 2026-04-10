<!DOCTYPE html>
<html class="dark" lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/icon3.png" />
    <title>NekoraList - Destacados</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary-dim": "#c0adff",
              "primary-fixed-dim": "#dacdff",
              "background": "#0e0e0e",
              "on-background": "#e7e5e4",
              "surface-bright": "#2c2c2c",
              "on-surface-variant": "#acabaa",
              "tertiary-dim": "#cecfef",
              "on-tertiary-container": "#4c4e68",
              "on-secondary-fixed-variant": "#5d5b5f",
              "on-tertiary-fixed-variant": "#565873",
              "inverse-surface": "#fcf8f8",
              "error-container": "#7f2737",
              "on-primary": "#4800bf",
              "on-secondary-fixed": "#403e42",
              "on-error-container": "#ff97a3",
              "surface-container-highest": "#252626",
              "on-surface": "#e7e5e4",
              "outline": "#767575",
              "on-error": "#490013",
              "tertiary-fixed": "#ddddfe",
              "surface-container-lowest": "#000000",
              "surface-tint": "#cdbdff",
              "surface": "#0e0e0e",
              "tertiary-container": "#ddddfe",
              "surface-variant": "#252626",
              "inverse-on-surface": "#565554",
              "inverse-primary": "#6834eb",
              "surface-container-high": "#1f2020",
              "primary-container": "#4f00d0",
              "primary": "#cdbdff",
              "secondary-fixed": "#e6e1e6",
              "on-primary-fixed-variant": "#652fe7",
              "outline-variant": "#484848",
              "on-secondary-container": "#c2bec3",
              "on-tertiary-fixed": "#3a3c55",
              "secondary-dim": "#a09da1",
              "secondary-container": "#3c3b3e",
              "error": "#ec7c8a",
              "on-primary-container": "#d6c9ff",
              "tertiary": "#edecff",
              "surface-container-low": "#131313",
              "surface-container": "#191a1a",
              "secondary-fixed-dim": "#d8d3d8",
              "surface-dim": "#0e0e0e",
              "on-tertiary": "#555671",
              "on-secondary": "#211f23",
              "tertiary-fixed-dim": "#cecfef",
              "error-dim": "#b95463",
              "secondary": "#a09da1",
              "primary-fixed": "#e8deff",
              "on-primary-fixed": "#4700bd"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .featured-card {
      position: relative;
      isolation: isolate;
      border-radius: 0.9rem;
      overflow: hidden;
      box-shadow: 0 18px 40px rgba(0,0,0,0.35), 0 0 18px rgba(99,102,241,0.45);
      will-change: transform;
      transform: translateZ(0);
      transition: transform 300ms ease, box-shadow 300ms ease;
    }
    .featured-card::before {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: 0.9rem;
      border: 1px solid transparent;
      background:
        linear-gradient(#0b0b0f, #0b0b0f) padding-box,
        linear-gradient(135deg, rgba(59,130,246,0.95), rgba(139,92,246,0.95)) border-box;
      background-size: 200% 200%;
      opacity: 1;
      transition: opacity 300ms ease;
      z-index: 0;
      animation: borderShift 6s ease-in-out infinite;
    }
    .featured-card::after {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(1200px 400px at -10% -20%, rgba(139,92,246,0.25), transparent 60%);
      opacity: 0.4;
      z-index: 0;
      pointer-events: none;
    }
    .featured-card > * { position: relative; z-index: 1; }
    .featured-card:hover {
      transform: translateY(-4px) scale(1.01);
      box-shadow: 0 26px 60px rgba(10,10,10,0.6);
    }
    .featured-card:hover::before { opacity: 1; }
    .featured-card:hover {
      box-shadow: 0 28px 70px rgba(10,10,10,0.7), 0 0 36px rgba(59,130,246,0.85);
      filter: saturate(1.1);
    }
    @keyframes borderShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .hover-preview-modal {
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 220ms ease;
      z-index: 999;
    }
    .hover-preview-modal.is-open {
      opacity: 1;
      pointer-events: auto;
    }
    .hover-preview-backdrop {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(8px);
    }
    .hover-preview-panel {
      position: relative;
      z-index: 1;
      width: min(88vw, 400px);
      min-height: 380px;
      display: flex;
      align-items: stretch;
      gap: 22px;
      padding: 26px;
      border-radius: 1.2rem;
      background: rgba(16, 16, 20, 0.96);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 24px 50px rgba(0, 0, 0, 0.55);
      transform: scale(0.96);
      opacity: 0;
      transition: opacity 220ms ease, transform 220ms ease;
      cursor: pointer;
    }
    .hover-preview-modal.is-open .hover-preview-panel {
      opacity: 1;
      transform: scale(1);
    }
    .hover-preview-poster {
      width: 200px;
      flex: 0 0 200px;
      aspect-ratio: 2 / 3;
      border-radius: 0.9rem;
      overflow: hidden;
      background: #111113;
    }
    .hover-preview-poster img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .hover-preview-info {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 0.5rem;
      min-width: 0;
      flex: 1;
    }
    .hover-preview-title {
      font-weight: 700;
      font-size: 1.25rem;
      line-height: 1.2;
    }
    .hover-preview-meta {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.45rem;
      font-size: 0.75rem;
      color: rgba(229, 231, 235, 0.78);
    }
    .hover-preview-score,
    .hover-preview-year,
    .hover-preview-extra {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      justify-content: center;
      width: 100%;
      min-width: 0;
      text-align: center;
      white-space: nowrap;
      padding: 0.22rem 0.55rem;
      border-radius: 9999px;
      background: linear-gradient(135deg, rgba(99,102,241,0.35), rgba(168,85,247,0.35));
      border: 1px solid rgba(168, 85, 247, 0.45);
      box-shadow: 0 0 10px rgba(168, 85, 247, 0.25);
    }
    .hover-preview-meta > span:only-child,
    .hover-preview-meta > span:last-child:nth-child(odd) {
      grid-column: 1 / -1;
    }
    .hover-preview-score {
      color: #cdbdff;
      font-weight: 700;
    }
    .hover-preview-score .material-symbols-outlined {
      font-size: 0.8rem;
    }
    .hover-preview-synopsis {
      font-size: 0.86rem;
      line-height: 1.45;
      color: rgba(229, 231, 235, 0.75);
      flex: 1;
      display: flex;
      align-items: center;
      text-align: center;
    }
    .hover-preview-genres {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.4rem;
      margin-top: 0.25rem;
    }
    .hover-preview-genres span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      min-width: 0;
      text-align: center;
      white-space: nowrap;
      font-size: 0.7rem;
      padding: 0.18rem 0.55rem;
      border-radius: 9999px;
      background: linear-gradient(135deg, rgba(34,211,238,0.28), rgba(99,102,241,0.25));
      border: 1px solid rgba(59, 130, 246, 0.35);
      color: rgba(240, 249, 255, 0.95);
      box-shadow: 0 0 10px rgba(56, 189, 248, 0.18);
    }
    .hover-preview-genres span:only-child,
    .hover-preview-genres span:last-child:nth-child(odd),
    .hover-preview-genres span.is-wide {
      grid-column: 1 / -1;
    }
    .hover-preview-genres.is-stacked {
      grid-template-columns: 1fr;
    }
    .hover-preview-genres.is-stacked span {
      grid-column: 1 / -1;
    }
    </style>
  </head>
  <body class="bg-zinc-950 text-zinc-100 font-['Inter']">
    <!-- Navbar Component -->
    <div data-layout="header"></div>
    <main class="mx-auto max-w-7xl px-6 md:px-10 pt-28 pb-10">
      <div class="mb-8 flex items-center justify-between gap-4">
        <div>
          <h1 class="font-['Manrope'] text-3xl md:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-violet-400 to-purple-400 drop-shadow-[0_6px_18px_rgba(99,102,241,0.35)]">Destacados</h1>
          <p class="text-zinc-400 mt-2">Animes y pel&iacute;culas m&aacute;s populares.</p>
        </div>
        <div class="flex items-center gap-2">
          <label class="text-xs uppercase tracking-widest text-zinc-400" for="featured-type">Tipo</label>
          <select id="featured-type" class="rounded-full bg-zinc-900 border border-zinc-800 px-4 py-2 text-sm text-zinc-100">
            <option value="all">Todos</option>
            <option value="anime">Anime</option>
            <option value="movie">Película</option>
          </select>
        </div>
      </div>

      <section>
  <div id="featured-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
</section>
<script src="assets/js/load-more.js?v=3"></script>
<script>
(function () {
  const select = document.getElementById("featured-type");
  const grid = document.getElementById("featured-grid");
  if (!grid) return;

  const API = "api/jikan_proxy.php";
  const norm = (v) => (v || "").toString().toLowerCase().replace(/\s+/g, " ").trim();
  const fixText = (s) => {
    return (s || "")
      .replace(/PELÍCULA/gi, "Película")
      .replace(/Película/gi, "Película")
      .replace(/Película/gi, "Película");
  };

  const setBadge = (media, score) => {
    if (!media) return;
    let badge = media.querySelector(".anidex-score-badge");
    if (!badge) {
      badge = document.createElement("div");
      badge.className = "anidex-score-badge absolute top-5 right-4 bg-zinc-900/90 px-3 py-1.5 rounded-full text-sm font-bold text-white flex items-center gap-1 shadow-[0_0_12px_rgba(0,0,0,0.5)]";
      badge.innerHTML = "<span class=\"text-[18px] leading-none text-yellow-400 material-symbols-outlined\" style=\"font-variation-settings: 'FILL' 1;\">star</span><span>--</span>";
      media.appendChild(badge);
    }
    const valueEl = badge.querySelector("span:last-child");
    if (valueEl) valueEl.textContent = score || "--";
  };

  const renderCard = (it) => {
    const article = document.createElement("article");
    article.className = "featured-card group rounded-[0.9rem] overflow-hidden bg-zinc-900 border border-zinc-800 cursor-pointer transition-transform duration-300 ease-[cubic-bezier(0.2,0,0,1)]";
    if (it?.mal_id) article.setAttribute("data-mal-id", String(it.mal_id));
    article.setAttribute("onclick", "window.location.href='detail'");
    const imgSrc = it?.images?.webp?.large_image_url || it?.images?.jpg?.large_image_url || it?.images?.jpg?.image_url || "";
    const title = it?.title || "Anime";
    const genres = (it?.genres || []).map((g) => g?.name).filter(Boolean).slice(0, 2).join(", ");
    const genresText = genres || "Sin categor&iacute;as";
    const type = norm(it?.type).includes("movie") ? "movie" : "anime";
    article.dataset.featuredType = type;
    article.setAttribute("data-type", type);
    article.innerHTML = `
      <div class="relative aspect-[2/3] p-2">
        <img class="w-full h-full object-cover rounded-[0.7rem] transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)]" src="${imgSrc}" alt="${title}" />
        <span class="absolute top-5 left-4 rounded-full bg-violet-500/90 px-4 py-1.5 text-[11px] font-bold uppercase tracking-widest">${type === "movie" ? "Película" : "Anime"}</span>
      </div>
      <div class="p-4">
        <h3 class="text-lg font-bold">${fixText(title)}</h3>
        <p class="text-xs text-zinc-400 mt-1">${fixText(genresText)}</p>
      </div>
    `;
    const media = article.querySelector(".relative");
    const score = typeof it?.score === "number" ? it.score.toFixed(1) : "";
    setBadge(media, score);
    return article;
  };

  const applyFilter = () => {
    const value = select ? select.value : "all";
    Array.from(grid.querySelectorAll("article")).forEach((card) => {
      const t = card.dataset.featuredType || "anime";
      const show = value === "all" || value === t;
      card.style.display = show ? "" : "none";
    });
  };

  if (select) select.addEventListener("change", applyFilter);

  const getActionEls = () => ({
    backHomeBtn: document.getElementById("featured-back-home"),
    reloadBtn: document.getElementById("featured-reload")
  });

  const setActionState = (isOk) => {
    const { backHomeBtn, reloadBtn } = getActionEls();
    if (backHomeBtn) backHomeBtn.classList.toggle("hidden", !isOk);
    if (reloadBtn) reloadBtn.classList.toggle("hidden", isOk);
  };

  document.addEventListener("DOMContentLoaded", () => {
    const { reloadBtn } = getActionEls();
    if (reloadBtn) reloadBtn.addEventListener("click", () => window.location.reload());
  });

  const loadFeatured = async () => {
    grid.innerHTML = "<p class=\"text-zinc-400\">Cargando destacados...</p>";
    try {
      const [resAnime, resMovie] = await Promise.all([
        fetch(`${API}?endpoint=${encodeURIComponent('top/anime?filter=bypopularity&limit=20')}`),
        fetch(`${API}?endpoint=${encodeURIComponent('top/anime?filter=bypopularity&type=movie&limit=20')}`)
      ]);
      if (!resAnime.ok || !resMovie.ok) {
        grid.innerHTML = "<p class=\"text-red-400\">No se pudieron cargar destacados.</p>";
        return false;
      }
      const jsonAnime = await resAnime.json();
      const jsonMovie = await resMovie.json();
      const combined = [...(jsonAnime?.data || []), ...(jsonMovie?.data || [])];
      const seen = new Set();
      const items = [];
      combined.forEach((it) => {
        const key = norm(it?.title) || norm(it?.title_english);
        if (!key || seen.has(key)) return;
        seen.add(key);
        items.push(it);
      });
      if (!items.length) {
        grid.innerHTML = "<p class=\"text-zinc-400\">Sin resultados.</p>";
        return false;
      }
      grid.innerHTML = "";
      items.forEach((it) => grid.appendChild(renderCard(it)));
      if (window.AniDexHoverPreview) window.AniDexHoverPreview.bind(grid);
      applyFilter();
      return true;
    } catch {
      grid.innerHTML = "<p class=\"text-red-400\">No se pudieron cargar destacados.</p>";
      return false;
    }
  };

  loadFeatured().then(setActionState);
})();
</script>
      <div class="mt-12 flex justify-center gap-4" id="featured-actions">
        <a id="featured-back-home" href="<?= route_path('home') ?>" class="rounded-full bg-violet-500/90 px-8 py-4 text-sm font-bold text-white hover:bg-violet-500 transition-colors">Volver al inicio</a>
        <button id="featured-reload" type="button" class="hidden rounded-full bg-sky-500/90 px-8 py-4 text-sm font-bold text-white hover:bg-sky-500 transition-colors">Volver a cargar</button>
      </div>
    </main>
    <!-- Footer Component -->
    <div data-layout="footer"></div>
    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
    <script src="assets/js/i18n.js"></script>
    <script src="assets/js/search.js?v=popular4"></script>
    <script src="assets/js/detail-links.js?v=5"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (window.AniDexI18n) window.AniDexI18n.init();
      if (window.AniDexSearch) window.AniDexSearch.init();
      if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
    });
    </script>
  </body>
</html>









