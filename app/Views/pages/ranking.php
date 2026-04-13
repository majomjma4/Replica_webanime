<!DOCTYPE html>
<html class="dark" lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NekoraList - Ranking</title>
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
          borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
        },
      },
    }
  </script>
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
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
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.35), rgba(168, 85, 247, 0.35));
      border: 1px solid rgba(168, 85, 247, 0.45);
      box-shadow: 0 0 10px rgba(168, 85, 247, 0.25);
    }

    .hover-preview-meta>span:only-child,
    .hover-preview-meta>span:last-child:nth-child(odd) {
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
      background: linear-gradient(135deg, rgba(34, 211, 238, 0.28), rgba(99, 102, 241, 0.25));
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
  <link rel="icon" href="img/icon3.png" />
</head>

<body class="bg-zinc-950 text-zinc-100 font-['Inter']">
  <div class="min-h-screen">
    <!-- Navbar Component -->
    <div data-layout="header"></div>

    <main class="mx-auto max-w-7xl px-6 md:px-10 pt-28 pb-10">
      <div class="mb-8 flex items-center justify-between gap-4">
        <div>
          <h1
            class="font-['Manrope'] text-3xl md:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-violet-300 to-blue-300 drop-shadow-[0_6px_18px_rgba(251,191,36,0.3)]">
            Ranking Top 50</h1>
          <p class="text-zinc-400 mt-2">Los 50 animes y pel&iacute;culas mejor posicionados.</p>
        </div>
      </div>

      <section class="mt-10">
        <div id="ranking-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-8">
          <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6 text-zinc-400">Cargando ranking...</div>
        </div>
      </section>
      <div class="mt-12 flex justify-center gap-4" id="ranking-actions">
        <a id="ranking-back-home" href="<?= route_path('home') ?>"
          class="rounded-full bg-violet-500/90 px-8 py-4 text-sm font-bold text-white hover:bg-violet-500 transition-colors">Volver
          al inicio</a>
        <button id="ranking-reload" type="button"
          class="hidden rounded-full bg-sky-500/90 px-8 py-4 text-sm font-bold text-white hover:bg-sky-500 transition-colors">Volver
          a cargar</button>
      </div>
    </main>

    <!-- Footer Component -->
    <div data-layout="footer"></div>
    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
  </div>

  <script src="assets/js/i18n.js"></script>
  <script src="assets/js/search.js?v=popular7"></script>
  <script src="assets/js/detail-links.js?v=5"></script>
  <script src="assets/js/load-more.js?v=3"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (window.AniDexI18n) window.AniDexI18n.init();
      if (window.AniDexSearch) window.AniDexSearch.init();
    });
  </script>
  <script>
    (function () {
      var grid = document.getElementById('ranking-grid');
      var backHomeBtn = document.getElementById('ranking-back-home');
      var reloadBtn = document.getElementById('ranking-reload');

      function setRankingActionState(isOk) {
        if (backHomeBtn) backHomeBtn.classList.toggle('hidden', !isOk);
        if (reloadBtn) reloadBtn.classList.toggle('hidden', isOk);
      }
      if (reloadBtn) {
        reloadBtn.addEventListener('click', function () { window.location.reload(); });
      }

      if (!grid) return;

      function setBadge(media, score, highlight) {
        if (!media) return;
        var badge = media.querySelector('.anidex-score-badge');
        if (!badge) {
          badge = document.createElement('div');
          badge.className = 'anidex-score-badge absolute top-3 right-3 bg-black/70 px-2 py-1 rounded text-xs font-bold text-white flex items-center gap-1';
          badge.innerHTML = "<span class='material-symbols-outlined text-[16px] leading-none' style=\"font-variation-settings: 'FILL' 1;\">star</span><span>--</span>";
          media.appendChild(badge);
        }
        if (highlight) {
          badge.className = 'anidex-score-badge absolute top-3 right-3 bg-black/70 px-3 py-1.5 rounded-full text-sm font-extrabold text-amber-100 flex items-center gap-1 ring-2 ring-amber-300/80 shadow-[0_0_18px_rgba(251,191,36,0.6)]';
        }
        var valueEl = badge.querySelector('span:last-child');
        if (valueEl) valueEl.textContent = score || '--';
      }

      function formatGenres(list) {
        var items = (list || []).map(function (g) { return g && g.name; }).filter(Boolean).slice(0, 2);
        return items.length ? items.join(', ') : 'Sin categor&iacute;as';
      }

      function renderCard(it, idx) {
        var imgSrc = (it && it.images && it.images.webp && it.images.webp.large_image_url) ||
          (it && it.images && it.images.jpg && it.images.jpg.large_image_url) ||
          (it && it.images && it.images.jpg && it.images.jpg.image_url) || '';
        var title = (it && (it.title_english || it.title)) || 'Anime';
        var genres = formatGenres(it && it.genres);
        var type = (it && it.type) || 'TV';
        var year = (it && (it.year || (it.aired && it.aired.prop && it.aired.prop.from && it.aired.prop.from.year))) || 'N/A';
        var score = (it && typeof it.score === 'number') ? it.score.toFixed(1) : '--';

        var article = document.createElement('article');
        article.className = 'group rounded-2xl overflow-hidden bg-zinc-900 border border-zinc-800 shadow-[0_18px_40px_rgba(0,0,0,0.35)] hover:-translate-y-1 hover:border-violet-500/40 transition-all sm:flex sm:gap-6 cursor-pointer';
        if (it && it.mal_id) article.setAttribute('data-mal-id', String(it.mal_id));
        article.setAttribute('data-title', title);
        article.setAttribute('data-anime-card', '1');
        article.setAttribute('data-type', type);
        article.onclick = function () {
          if (window.AniDexShared && typeof window.AniDexShared.buildDetailUrl === 'function') {
            window.location.href = window.AniDexShared.buildDetailUrl(it.mal_id, title);
          } else {
            window.location.href = 'detail/' + (it.mal_id || (it.title ? encodeURIComponent(it.title) : 'anime'));
          }
        };
        if (idx < 3) {
          article.className = 'group rounded-2xl overflow-hidden bg-gradient-to-br from-zinc-900 via-zinc-900 to-amber-900/30 border border-amber-400/60 shadow-[0_24px_60px_rgba(251,191,36,0.35)] hover:-translate-y-1 hover:border-amber-300/90 transition-all sm:flex sm:gap-6';
        } else if (idx < 10) {
          article.className = 'group rounded-2xl overflow-hidden bg-gradient-to-br from-zinc-900 via-zinc-900 to-violet-900/25 border border-violet-400/45 shadow-[0_22px_54px_rgba(139,92,246,0.22)] hover:-translate-y-1 hover:border-violet-300/70 hover:shadow-[0_28px_64px_rgba(139,92,246,0.28)] transition-all sm:flex sm:gap-6';
        }

        var media = document.createElement('div');
        media.className = 'relative';
        var imgWrap = document.createElement('div');
        imgWrap.className = 'relative w-40 sm:w-44 aspect-[2/3] overflow-hidden rounded-t-2xl sm:rounded-l-2xl sm:rounded-tr-none flex-none';
        var img = document.createElement('img');
        img.className = 'w-full h-full object-cover';
        img.loading = 'lazy';
        img.src = imgSrc;
        img.alt = title;
        imgWrap.appendChild(img);

        var rank = document.createElement('span');
        rank.className = 'absolute top-3 left-3 rounded-full bg-violet-500/90 px-3 py-1 text-[12px] font-bold uppercase tracking-widest';
        if (idx < 3) {
          rank.className = 'absolute top-3 left-3 rounded-full bg-black/70 p-2 shadow-[0_0_40px_rgba(251,191,36,0.9)] ring-2 ring-amber-300/90';
        } else if (idx < 10) {
          rank.className = 'absolute top-3 left-3 rounded-full bg-violet-400/90 text-black px-5 py-2 text-sm font-bold uppercase tracking-widest shadow-[0_12px_24px_rgba(139,92,246,0.35)]';
        }
        if (idx < 3) {
          rank.innerHTML = '';
          var medal = document.createElement('img');
          medal.src = 'img/' + (idx + 1) + '.png';
          medal.alt = 'Top ' + (idx + 1);
          medal.className = 'block h-16 w-16 object-contain';
          rank.appendChild(medal);
        } else {
          rank.textContent = '#' + (idx + 1);
        }

        var typeBadge = document.createElement('span');
        typeBadge.className = 'absolute bottom-3 left-3 rounded-full bg-violet-500 border border-violet-400 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.6)]';
        typeBadge.textContent = (type && type.toLowerCase().includes('movie')) ? 'Película' : 'Anime';

        media.appendChild(imgWrap);
        media.appendChild(rank);
        media.appendChild(typeBadge);

        var body = document.createElement('div');
        body.className = 'p-5 sm:p-6';
        var h3 = document.createElement('h3');
        h3.className = 'text-lg font-bold leading-snug';
        h3.textContent = title;
        var p = document.createElement('p');
        p.className = 'text-xs text-zinc-400 mt-1';
        p.textContent = genres;
        var meta = document.createElement('div');
        meta.className = 'mt-3 flex flex-wrap gap-2 text-xs text-zinc-200';
        var spanType = document.createElement('span');
        spanType.className = 'rounded-full bg-zinc-800/80 px-3 py-1';
        spanType.textContent = 'Tipo: ' + type;
        var spanYear = document.createElement('span');
        spanYear.className = 'rounded-full bg-zinc-800/80 px-3 py-1';
        spanYear.textContent = 'Año: ' + year;
        meta.appendChild(spanType);
        meta.appendChild(spanYear);

        body.appendChild(h3);
        body.appendChild(p);
        body.appendChild(meta);

        article.appendChild(media);
        article.appendChild(body);
        setBadge(media, score, idx < 3);
        return article;
      }

      function fetchRankingData(endpoint) {
        if (window.AniDexShared && typeof window.AniDexShared.smartFetchJikan === 'function') {
           return window.AniDexShared.smartFetchJikan(endpoint)
             .then(function(json) { return json && Array.isArray(json.data) ? json.data : null; });
        }
        return fetch(endpoint)
          .then(function(res) { return res && res.ok ? res.json() : null; })
          .then(function(json) { return json && Array.isArray(json.data) ? json.data : null; });
      }

      function loadRanking() {
        grid.innerHTML = Array.from({length: 6}, function(_, idx) {
           var rankClass = 'absolute top-3 left-3 rounded-full bg-zinc-800 w-12 h-6';
           if (idx < 3) rankClass = 'absolute top-3 left-3 rounded-full bg-zinc-800 w-16 h-16';
          return `
          <article class="group rounded-2xl overflow-hidden bg-zinc-900 border border-zinc-800 animate-pulse sm:flex sm:gap-6">
            <div class="relative w-40 sm:w-44 aspect-[2/3] flex-none">
              <div class="w-full h-full bg-zinc-800 rounded-t-2xl sm:rounded-l-2xl sm:rounded-tr-none"></div>
              <div class="${rankClass}"></div>
            </div>
            <div class="p-5 sm:p-6 w-full space-y-3">
              <div class="h-6 bg-zinc-800 rounded w-3/4"></div>
              <div class="h-4 bg-zinc-800 rounded w-1/2"></div>
              <div class="flex gap-2 mt-4">
                <div class="h-6 bg-zinc-800 rounded-full w-24"></div>
                <div class="h-6 bg-zinc-800 rounded-full w-20"></div>
              </div>
            </div>
          </article>
        `;
        }).join('');
        return fetchRankingData('top/anime?limit=50')
          .then(function (items) {
            if (items && items.length) return items.slice(0, 50);
            return Promise.all([
              fetchRankingData('top/anime?page=1&limit=25'),
              fetchRankingData('top/anime?page=2&limit=25')
            ]).then(function (parts) {
              var merged = [].concat(parts[0] || [], parts[1] || []);
              return merged.slice(0, 50);
            });
          });
      }

      loadRanking()
        .then(function (items) {
          if (!items || !items.length) {
            grid.innerHTML = '<p class="text-red-400">No se pudo cargar el ranking.</p>';
            setRankingActionState(false);
            return;
          }
          grid.innerHTML = '';
          setRankingActionState(true);
          items.forEach(function (it, idx) {
            grid.appendChild(renderCard(it, idx));
          });
          if (window.AniDexHoverPreview) window.AniDexHoverPreview.bind(grid);
        })
        .catch(function () {
          grid.innerHTML = '<p class="text-red-400">No se pudo cargar el ranking.</p>';
          setRankingActionState(false);
        });
    })();
  </script>
</body>

</html>
