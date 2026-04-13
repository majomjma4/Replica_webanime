(function () {
  const appUrl = window.AniDexShared?.buildAppUrl || ((path = "") => String(path || ""));
  const API_BASE = appUrl("api/jikan_proxy");
  const path = window.location.pathname.toLowerCase();
  const isCatalog = path.includes("peliculas");
  const hideCardYears = path.includes("series") || path.includes("peliculas");
  const previewDelayMs = path.includes("destacados") ? 1500 : 800;
  const isSeriesPage = path.includes("series");
  const grid = document.querySelector("section[aria-label='Grid de anime']");
  const loadBtn = Array.from(document.querySelectorAll("button"))
    .find((b) => (b.textContent || "").toLowerCase().includes("cargar"));
  const hasLoadMore = false;

  const cols = 6;
  const batchRows = 2;
  const batchSize = cols * batchRows;

  let page = 1;
  let lastPage = null;
  let loading = false;
  const seenTitles = new Set();
  const seenIds = new Set();
  const DEFAULT_FALLBACK = "img/fondoanime.png";
  const hoverCapable = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  const previewCache = new Map();
  const previewPending = new Map();
  let activePreviewCard = null;
  let activePreviewUrl = "";
  const synopsisCache = new Map();
  let fallbackIndex = 0;
  const previewPrefetchDelay = 90;
  const previewPrefetchConcurrency = 4;
  const fallbackPool = isCatalog
    ? [
        { title: "Millennium Actress", year: 2001, type: "Movie", status: "Finished Airing", score: 7.9, genres: ["Drama", "Romance"], image: "https://cdn.myanimelist.net/images/anime/2/11232l.jpg" },
        { title: "The Tale of the Princess Kaguya", year: 2013, type: "Movie", status: "Finished Airing", score: 8.2, genres: ["Drama", "Historical"], image: "https://cdn.myanimelist.net/images/anime/2/61237l.jpg" },
        { title: "Perfect Blue", year: 1998, type: "Movie", status: "Finished Airing", score: 8.5, genres: ["Thriller", "Psychological"], image: "https://cdn.myanimelist.net/images/anime/6/20936l.jpg" },
        { title: "Akira", year: 1988, type: "Movie", status: "Finished Airing", score: 8.2, genres: ["Action", "Sci-Fi"], image: "https://cdn.myanimelist.net/images/anime/13/11606l.jpg" },
        { title: "The Girl Who Leapt Through Time", year: 2006, type: "Movie", status: "Finished Airing", score: 8.1, genres: ["Drama", "Romance"], image: "https://cdn.myanimelist.net/images/anime/5/30651l.jpg" },
        { title: "Wolf Children", year: 2012, type: "Movie", status: "Finished Airing", score: 8.6, genres: ["Drama", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/9/35721l.jpg" },
        { title: "Castle in the Sky", year: 1986, type: "Movie", status: "Finished Airing", score: 8.2, genres: ["Adventure", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/4/19644l.jpg" },
        { title: "Howl's Moving Castle", year: 2004, type: "Movie", status: "Finished Airing", score: 8.6, genres: ["Adventure", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/5/75810l.jpg" },
        { title: "Paprika", year: 2006, type: "Movie", status: "Finished Airing", score: 8.0, genres: ["Sci-Fi", "Psychological"], image: "https://cdn.myanimelist.net/images/anime/12/23809l.jpg" },
        { title: "Tokyo Godfathers", year: 2003, type: "Movie", status: "Finished Airing", score: 7.9, genres: ["Drama", "Comedy"], image: "https://cdn.myanimelist.net/images/anime/2/16571l.jpg" }
      ]
    : [
        { title: "One Punch Man", year: 2015, type: "TV", status: "Finished Airing", score: 8.5, genres: ["Action", "Comedy"], image: "https://cdn.myanimelist.net/images/anime/12/76049l.jpg" },
        { title: "Hunter x Hunter", year: 2011, type: "TV", status: "Finished Airing", score: 9.0, genres: ["Action", "Adventure"], image: "https://cdn.myanimelist.net/images/anime/11/33657l.jpg" },
        { title: "Steins;Gate", year: 2011, type: "TV", status: "Finished Airing", score: 9.1, genres: ["Sci-Fi", "Thriller"], image: "https://cdn.myanimelist.net/images/anime/5/73199l.jpg" },
        { title: "Mob Psycho 100", year: 2016, type: "TV", status: "Finished Airing", score: 8.5, genres: ["Action", "Comedy"], image: "https://cdn.myanimelist.net/images/anime/8/80356l.jpg" },
        { title: "Gintama", year: 2006, type: "TV", status: "Finished Airing", score: 8.9, genres: ["Action", "Comedy"], image: "https://cdn.myanimelist.net/images/anime/10/73274l.jpg" },
        { title: "Re:Zero - Starting Life in Another World", year: 2016, type: "TV", status: "Finished Airing", score: 8.2, genres: ["Drama", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/11/79410l.jpg" },
        { title: "Made in Abyss", year: 2017, type: "TV", status: "Finished Airing", score: 8.7, genres: ["Adventure", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/6/86733l.jpg" },
        { title: "Kaguya-sama: Love is War", year: 2019, type: "TV", status: "Finished Airing", score: 8.4, genres: ["Comedy", "Romance"], image: "https://cdn.myanimelist.net/images/anime/1295/106551l.jpg" },
        { title: "Dr. Stone", year: 2019, type: "TV", status: "Finished Airing", score: 8.3, genres: ["Adventure", "Sci-Fi"], image: "https://cdn.myanimelist.net/images/anime/1613/102576l.jpg" },
        { title: "Black Clover", year: 2017, type: "TV", status: "Finished Airing", score: 8.1, genres: ["Action", "Fantasy"], image: "https://cdn.myanimelist.net/images/anime/2/88336l.jpg" },
        { title: "Code Geass: Lelouch of the Rebellion", year: 2006, type: "TV", status: "Finished Airing", score: 8.7, genres: ["Action", "Sci-Fi"], image: "https://cdn.myanimelist.net/images/anime/5/50331l.jpg" }
      ];

  function norm(v) {
    return window.AniDexShared?.normalizeText ? window.AniDexShared.normalizeText(v) : String(v || "").toLowerCase().trim();
  }


  function isBlockedMovieTitle(value) {
    const title = norm(value);
    return title.includes("does it count if you lose your innocence to an android") || title.includes("does it count if") || title.includes("futanari");
  }

  function pretty(text) {
    const value = String(text || "").trim();
    if (!value) return "";
    return value.charAt(0).toUpperCase() + value.slice(1);
  }

  function translateGenre(label) {
    const map = {
      "Action": "Acción",
      "Adventure": "Aventura",
      "Comedy": "Comedia",
      "Drama": "Drama",
      "Fantasy": "Fantasía",
      "Sci-Fi": "Ciencia ficción",
      "Slice of Life": "Recuentos de la vida",
      "Supernatural": "Sobrenatural",
      "Romance": "Romance",
      "Mystery": "Misterio",
      "Thriller": "Suspenso",
      "Psychological": "Psicolgico",
      "Horror": "Terror",
      "Sports": "Deportes",
      "Music": "Msica",
      "School": "Escolar",
      "Historical": "Histrico",
      "Military": "Militar",
      "Space": "Espacio",
      "Mecha": "Mecha",
      "Game": "Juegos",
      "Vampire": "Vampiros",
      "Demons": "Demonios",
      "Samurai": "Samuri",
      "Police": "Policial",
      "Super Power": "Superpoderes",
      "Harem": "Harem",
      "Reverse Harem": "Harem inverso",
      "Magical Girl": "Magical Girl",
      "Kids": "Infantil",
      "Seinen": "Seinen",
      "Shounen": "Shounen",
      "Shoujo": "Shoujo",
      "Josei": "Josei",
      "Ecchi": "Ecchi"
    };
    const cleaned = String(label || "").trim();
    return map[cleaned] || cleaned;
  }

  function shortSynopsis(text, limit = 20) {
    const clean = String(text || "").replace(/\s+/g, " ").trim();
    if (!clean) return "Sinopsis no disponible.";
    const sentences = clean.match(/[^.!?]+[.!?]/g) || [clean];
    const firstSentence = String(sentences[0]).trim();
    const base = firstSentence || clean;
    const words = base.split(" ").filter(Boolean);
    let trimmed = words.slice(0, limit).join(" ").trim();
    if (!trimmed) return "Sinopsis no disponible.";
    const tail = trimmed.split(" ");
    const badEndings = new Set([
      "de","del","la","el","los","las","y","o","que","en","con","por","para","a","un","una","unos","unas","al","lo"
    ]);
    while (tail.length > 4 && badEndings.has(tail[tail.length - 1].toLowerCase())) {
      tail.pop();
    }
    trimmed = tail.join(" ").trim();
    const endsWell = /[.!?]$/.test(trimmed);
    return endsWell ? trimmed : `${trimmed}.`;
  }

  async function fetchJson(url) {
    if (window.AniDexShared?.fetchJson) return window.AniDexShared.fetchJson(url);
    try {
      let res = await fetch(url);
      if (res.status === 429) {
        await new Promise((r) => setTimeout(r, 700));
        res = await fetch(url);
      }
      if (!res.ok) return null;
      return await res.json();
    } catch {
      return null;
    }
  }

  async function fetchById(id) {
    if (!id) return null;
    const json = await fetchJson(`${API_BASE}?endpoint=${encodeURIComponent('anime/' + id + '/full')}`);
    return json?.data || null;
  }

  async function searchByTitle(title, yearHint) {
    if (!title) return null;
    const type = isCatalog ? "movie" : "tv";
    const url = `${API_BASE}?endpoint=${encodeURIComponent("anime?q=" + title + "&type=" + type + "&limit=5&order_by=popularity&sort=asc")}`;
    const json = await fetchJson(url);
    const list = json?.data || [];
    if (!list.length) return null;
    if (yearHint) {
      const match = list.find((item) => {
        const y = item?.year || item?.aired?.prop?.from?.year;
        return y && String(y) === String(yearHint);
      });
      if (match) return match;
    }
    return list[0] || null;
  }

  async function spanishSynopsis(raw) {
    const text = String(raw || "").trim();
    if (!text) return "Sinopsis no disponible.";
    if (synopsisCache.has(text)) return synopsisCache.get(text);
    let translated = text;
    try {
      const out = window.AniDexShared?.translateAutoToEs
        ? await window.AniDexShared.translateAutoToEs(text)
        : text;
      if (out) translated = out;
    } catch {
      translated = text;
    }
    const summary = shortSynopsis(translated, 20);
    synopsisCache.set(text, summary);
    return summary;
  }

  function buildPreviewData(card, item, synopsisText) {
    const title =
      item?.title ||
      card.querySelector("h3,h4,h5")?.textContent?.trim() ||
      card.getAttribute("data-title") ||
      "Anime";
    const image =
      item?.images?.webp?.large_image_url ||
      item?.images?.jpg?.large_image_url ||
      item?.images?.jpg?.image_url ||
      card.querySelector("img")?.getAttribute("src") ||
      DEFAULT_FALLBACK;
    const scoreRaw = item?.score || card.getAttribute("data-score") || "";
    const score = scoreRaw ? Number(scoreRaw).toFixed(1) : "--";
    const year =
      item?.year ||
      item?.aired?.prop?.from?.year ||
      card.getAttribute("data-year") ||
      "";
    const episodes = item?.episodes || "";
    const duration = item?.duration || "";
    const synopsis = synopsisText || shortSynopsis(item?.synopsis || "", 20);
    const genresRaw = Array.isArray(item?.genres) && item.genres.length
      ? item.genres.map((g) => g?.name || g).filter(Boolean)
      : String(card.getAttribute("data-genres") || "")
          .split(",")
          .map((g) => g.trim())
          .filter(Boolean);
    const genres = genresRaw.map(translateGenre).map(pretty).slice(0, 3);
    const typeHint =
      item?.type ||
      card?.getAttribute("data-type") ||
      card?.dataset?.featuredType ||
      "";
    return { title, image, score, year, episodes, duration, synopsis, genres, type: typeHint };
  }

  async function getPreviewData(card) {
    const malId = card.getAttribute("data-mal-id") || "";
    const title =
      card.querySelector("h3,h4,h5")?.textContent?.trim() ||
      card.getAttribute("data-title") ||
      "";
    const yearHint = card.getAttribute("data-year") || "";
    const key = malId ? `id:${malId}` : `title:${norm(title)}`;
    if (previewCache.has(key)) return previewCache.get(key);
    if (previewPending.has(key)) return previewPending.get(key);
    const promise = (async () => {
      let item = null;
      if (malId) item = await fetchById(malId);
      if (!item) item = await searchByTitle(title, yearHint);
      const synopsisText = await spanishSynopsis(item?.synopsis || "");
      const data = buildPreviewData(card, item, synopsisText);
      previewCache.set(key, data);
      previewPending.delete(key);
      return data;
    })();
    previewPending.set(key, promise);
    return promise;
  }

  function buildDetailUrl(card, data) {
    const malId =
      card?.getAttribute("data-mal-id") ||
      card?.querySelector("[data-mal-id]")?.getAttribute("data-mal-id") ||
      "";
    const rawTitle =
      data?.title ||
      card?.querySelector("h3,h4,h5")?.textContent?.trim() ||
      card?.getAttribute("data-title") ||
      "anime";
    const title = String(rawTitle || "anime").trim();
    if (malId) {
      return appUrl(`detail?mal_id=${encodeURIComponent(String(malId))}&q=${encodeURIComponent(title)}`);
    }
    return appUrl(`detail?q=${encodeURIComponent(title)}`);
  }

  let previewModal = null;
  let previewPanel = null;
  let previewArmed = false;
  let previewCloseTimer = null;

  function ensurePreviewModal() {
    if (previewModal && previewPanel) return { modal: previewModal, panel: previewPanel };
    const modal = document.createElement("div");
    modal.id = "hover-preview-modal";
    modal.className = "hover-preview-modal";
    modal.innerHTML = `
      <div class="hover-preview-backdrop"></div>
      <div class="hover-preview-panel" role="dialog" aria-modal="true">
        <div class="hover-preview-poster"><img alt="" /></div>
        <div class="hover-preview-info">
          <div class="hover-preview-title"></div>
          <div class="hover-preview-meta">
            <span class="hover-preview-score"><span class="material-symbols-outlined">star</span><span data-score></span></span>
            <span class="hover-preview-year" data-year></span>
            <span class="hover-preview-extra" data-extra></span>
          </div>
          <p class="hover-preview-synopsis" data-synopsis></p>
          <div class="hover-preview-genres" data-genres></div>
        </div>
      </div>`;
    document.body.appendChild(modal);
    previewModal = modal;
    previewPanel = modal.querySelector(".hover-preview-panel");
    if (!previewModal.dataset.bound) {
      const shouldKeep = () => {
        if (!activePreviewCard || !previewPanel) return false;
        if (previewPanel.matches(":hover")) return true;
        return activePreviewCard.matches(":hover");
      };
      document.addEventListener("mousemove", () => {
        if (!activePreviewCard) return;
        if (shouldKeep()) return;
        closePreviewModal();
      });
      previewModal.dataset.bound = "1";
    }
    previewPanel.addEventListener("click", () => {
      if (activePreviewUrl) {
        window.location.href = activePreviewUrl;
      }
    });
    previewPanel.addEventListener("mouseenter", () => {
      previewArmed = true;
      if (previewCloseTimer) clearTimeout(previewCloseTimer);
    });
    previewPanel.addEventListener("mouseleave", () => {
      if (!previewArmed) return;
      closePreviewModal();
    });
    return { modal: previewModal, panel: previewPanel };
  }

  function fillPreviewModal(card, data) {
    ensurePreviewModal();
    const panel = previewPanel;
    const img = panel.querySelector("img");
    const titleEl = panel.querySelector(".hover-preview-title");
    const scoreEl = panel.querySelector("[data-score]");
    const yearEl = panel.querySelector("[data-year]");
    const extraEl = panel.querySelector("[data-extra]");
    const synopsisEl = panel.querySelector("[data-synopsis]");
    const genresWrap = panel.querySelector("[data-genres]");
    if (img) {
      img.src = data.image || DEFAULT_FALLBACK;
      img.alt = data.title || "Anime";
    }
    if (titleEl) titleEl.textContent = data.title || "Anime";
    if (scoreEl) scoreEl.textContent = data.score || "--";
    if (yearEl) {
      yearEl.textContent = data.year ? `${data.year}` : "";
      yearEl.style.display = data.year ? "inline-flex" : "none";
    }
    const typeLabel = String(data.type || "").toLowerCase();
    const isMovieCard =
      typeLabel.includes("movie") ||
      typeLabel.includes("pelicula") ||
      typeLabel.includes("película") ||
      card?.dataset?.featuredType === "movie";
    const showDuration = isMovieCard && data.duration;
    const showEpisodes = !isMovieCard && data.episodes;
    if (extraEl) {
      if (showDuration) extraEl.textContent = String(data.duration);
      else if (showEpisodes) extraEl.textContent = `${data.episodes} ep`;
      else extraEl.textContent = "";
      extraEl.style.display = showDuration || showEpisodes ? "inline-flex" : "none";
    }
    if (synopsisEl) synopsisEl.textContent = data.synopsis || "Sinopsis no disponible.";
    if (genresWrap) {
      genresWrap.innerHTML = "";
      const spans = [];
      (data.genres || []).forEach((genre) => {
        const span = document.createElement("span");
        span.textContent = genre;
        genresWrap.appendChild(span);
        spans.push(span);
      });
      if (spans.length > 1) {
        requestAnimationFrame(() => {
          let hasWide = false;
          spans.forEach((span) => {
            const overflowed = span.scrollWidth > span.clientWidth + 1;
            span.classList.toggle("is-wide", overflowed);
            if (overflowed) hasWide = true;
          });
          genresWrap.classList.toggle("is-stacked", hasWide);
        });
      } else {
        genresWrap.classList.remove("is-stacked");
      }
    }
  }

  function openPreviewModal(card, data) {
    ensurePreviewModal();
    if (activePreviewCard && activePreviewCard !== card) {
      activePreviewCard = card;
    }
    activePreviewCard = card;
    activePreviewUrl = buildDetailUrl(card, data);
    previewArmed = false;
    fillPreviewModal(card, data);
    previewModal.classList.add("is-open");
  }

  function closePreviewModal() {
    if (!previewModal) return;
    previewModal.classList.remove("is-open");
    previewArmed = false;
    activePreviewCard = null;
    activePreviewUrl = "";
  }

  function bindHoverPreview(card) {
    if (!hoverCapable || card.dataset.previewBound) return;
    card.dataset.previewBound = "1";
    let openTimer = null;
    let token = 0;
    card.addEventListener("mouseenter", () => {
      if (previewCloseTimer) clearTimeout(previewCloseTimer);
      token += 1;
      const currentToken = token;
      openTimer = setTimeout(async () => {
        const data = await getPreviewData(card);
        if (currentToken !== token) return;
        if (!card.matches(":hover")) return;
        openPreviewModal(card, data);
      }, previewDelayMs);
    });
    card.addEventListener("mouseleave", () => {
      clearTimeout(openTimer);
      token += 1;
    });
  }

  function bindHoverPreviewFor(root) {
    const scope = root || document;
    const cards = Array.from(
      scope.querySelectorAll("[data-anime-card], article.featured-card, #ranking-grid article")
    );
    cards.forEach(bindHoverPreview);
  }

  function warmPreviewCache(root) {
    if (isSeriesPage) return;
    const scope = root && root.nodeType === 1 ? root : document;
    const cards = Array.from(
      scope.querySelectorAll("[data-anime-card], article.featured-card, #ranking-grid article")
    ).filter((card) => !card.dataset.previewPrefetch);
    if (!cards.length) return;
    cards.forEach((card) => { card.dataset.previewPrefetch = "1"; });
    let index = 0;
    const total = cards.length;
    const workers = Array.from({ length: Math.min(previewPrefetchConcurrency, total) }, async () => {
      while (index < total) {
        const card = cards[index++];
        if (!card) break;
        try {
          await getPreviewData(card);
        } catch {
          // Ignore prefetch errors
        }
        if (previewPrefetchDelay) {
          await new Promise((r) => setTimeout(r, previewPrefetchDelay));
        }
      }
    });
    Promise.all(workers).catch(() => {});
  }

  function hydrateSeen() {
    grid.querySelectorAll("[data-anime-card]").forEach((card) => {
      const title = card.getAttribute("data-title") || card.querySelector("h3,h4,h5")?.textContent || "";
      const malId = card.getAttribute("data-mal-id") || "";
      if (title) seenTitles.add(norm(title));
      if (malId) seenIds.add(String(malId));
    });
  }

  function setGenres(card, item) {
    const wrap = card.querySelector(".flex.flex-nowrap.gap-2.min-w-0.overflow-hidden");
    if (wrap && wrap.querySelector("span")) wrap.remove();
    const legacy = card.querySelector(".flex.flex-wrap.gap-2");
    if (legacy && legacy.querySelector("span")) legacy.remove();
  }

  function applyImageFallback(img) {
    if (!img) return;
    if (!img.dataset.fallback) img.dataset.fallback = DEFAULT_FALLBACK;
    if (!img.loading) img.loading = "lazy";
    img.decoding = "async";
    if (!img.referrerPolicy) img.referrerPolicy = "no-referrer";
    if (img.dataset.fallbackBound) return;
    img.addEventListener("error", () => {
      const fallback = img.dataset.fallback || DEFAULT_FALLBACK;
      if (fallback && img.src !== fallback) img.src = fallback;
    });
    img.dataset.fallbackBound = "1";
  }

  function setYear(card, item) {
    const year = item?.year || item?.aired?.prop?.from?.year || "";
    if (!year) return;
    card.setAttribute("data-year", String(year));
    const posterBadge = card.querySelector(".relative span");
    if (posterBadge) {
      posterBadge.textContent = String(year);
      if (window.location.pathname.toLowerCase().includes("peliculas")) {
        posterBadge.className = "absolute left-3 bottom-3 rounded-full bg-surface/80 px-4 py-1.5 text-sm font-bold text-on-surface";
      }
    }
    if (hideCardYears) {
      card.querySelectorAll("[data-card-year]").forEach((el) => el.remove());
      return;
    }
    const yearEl = card.querySelector("[data-card-year]");
    if (yearEl) {
      yearEl.textContent = String(year);
      return;
    }
    const p = card.querySelector("p");
    if (!p) return;
    const span = document.createElement("span");
    span.dataset.cardYear = "1";
    span.className = "block text-xs text-on-surface-variant/80 mt-0.5";
    span.textContent = String(year);
    p.insertAdjacentElement("afterend", span);
  }

  function setStudio(card, item) {
    if (hideCardYears) return;
    const p = card.querySelector("p");
    if (!p) return;
    const studio = (item?.studios || [])[0]?.name || "Estudio";
    p.textContent = `Estudio: ${studio}`;
  }

  function setScoreBadge(card, item) {
    const scoreValue = item?.score || item?.scored || "";
    if (!scoreValue) return;
    const media = card.querySelector(".relative");
    if (!media) return;
    let badge = media.querySelector(".anidex-score-badge");
    if (!badge) {
      badge = document.createElement("span");
      const isMovie = window.location.pathname.toLowerCase().includes("peliculas");
      badge.className =
        "anidex-score-badge absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur " + 
        (isMovie ? "px-2.5 py-1.5 rounded-lg text-sm" : "px-2 py-1 rounded text-xs") + 
        " font-bold text-primary flex items-center gap-1.5 shadow-lg";
      badge.innerHTML =
        `<span class="material-symbols-outlined text-[${isMovie ? '14px' : '10px'}]" style="font-variation-settings: 'FILL' 1;">star</span><span></span>`;
      media.appendChild(badge);
    }
    const valueEl = badge.querySelector("span:last-child");
    if (valueEl) valueEl.textContent = Number(scoreValue).toFixed(1);
  }

  function buildCard(item) {
    const base = grid.querySelector("[data-anime-card]");
    if (!base) return null;
    const card = base.cloneNode(true);

    const title = item?.title || "Anime";
    if (isBlockedMovieTitle(title)) return null;
    const img = card.querySelector("img");
    if (img) {
      applyImageFallback(img);
      const src = item?.images?.webp?.large_image_url || item?.images?.jpg?.large_image_url || item?.images?.jpg?.image_url || item?.image || img.getAttribute("src");
      if (src) img.setAttribute("src", src);
      img.setAttribute("alt", title);
    }

    const h = card.querySelector("h3,h4,h5");
    if (h) h.textContent = title;

    const malId = item?.mal_id ? String(item.mal_id) : "";
    const genres = (item?.genres || []).map((g) => g?.name || g).filter(Boolean);
    const type = item?.type || (isCatalog ? "Movie" : "TV");
    const status = item?.status || "";
    const score = item?.score || item?.scored || "";

    card.setAttribute("data-title", norm(title));
    if (malId) card.setAttribute("data-mal-id", malId);

    const link = card.querySelector("a");
    if (link) {
      if (malId) link.setAttribute("data-mal-id", malId);
      // Actualizar el href con mal_id y q para evitar ruteos incorrectos
      if (malId) {
        link.href = appUrl(`detail?mal_id=${encodeURIComponent(malId)}&q=${encodeURIComponent(title)}`);
      } else {
        link.href = appUrl(`detail?q=${encodeURIComponent(title)}`);
      }
    }
    card.setAttribute("data-type", type);
    if (status) card.setAttribute("data-status", status);
    if (genres.length) card.setAttribute("data-genres", genres.join(","));
    if (score) card.setAttribute("data-score", String(score));
    setStudio(card, item);
    setGenres(card, item);
    setYear(card, item);
    setScoreBadge(card, item);
    bindHoverPreview(card);

    return card;
  }

  async function fetchMore() {
    const type = isCatalog ? "movie" : "tv";
    page += 1;
    const url = `${API_BASE}?endpoint=${encodeURIComponent("anime?type=" + type + "&page=" + page + "&limit=" + batchSize + "&order_by=popularity&sort=asc")}`;
    const res = await fetch(url);
    if (!res.ok) return [];
    const json = await res.json();
    lastPage = json?.pagination?.last_visible_page || lastPage;
    const rows = json?.data || [];
    const unique = [];
    rows.forEach((it) => {
      const t = norm(it?.title);
      if (isBlockedMovieTitle(t)) return;
      const id = it?.mal_id ? String(it.mal_id) : "";
      if (!t) return;
      if (id && seenIds.has(id)) return;
      if (seenTitles.has(t)) return;
      if (id) seenIds.add(id);
      seenTitles.add(t);
      unique.push(it);
    });
    return unique;
  }

  function pickFallback(count) {
    const out = [];
    while (out.length < count && fallbackIndex < fallbackPool.length) {
      const item = fallbackPool[fallbackIndex++];
      const t = norm(item.title);
      if (!t || seenTitles.has(t)) continue;
      seenTitles.add(t);
      out.push({
        title: item.title,
        year: item.year,
        type: item.type,
        status: item.status,
        score: item.score,
        genres: (item.genres || []).map((g) => ({ name: g })),
        images: { jpg: { large_image_url: item.image } }
      });
    }
    return out;
  }

  function showSkeletons(count) {
    if (!grid) return;
    for (let i = 0; i < count; i++) {
       const skel = document.createElement("article");
       skel.className = "group rounded-lg bg-surface-container-low p-4 animate-pulse skeleton-loader";
       skel.innerHTML = `
         <div class="relative aspect-[2/3] overflow-hidden rounded-lg bg-surface-container-highest/40"></div>
         <div class="space-y-2 mt-3">
           <div class="h-5 bg-surface-container-highest/40 rounded w-full"></div>
           <div class="h-4 bg-surface-container-highest/40 rounded w-2/3"></div>
         </div>
       `;
       grid.appendChild(skel);
    }
  }

  function hideSkeletons() {
    if (!grid) return;
    grid.querySelectorAll(".skeleton-loader").forEach(el => el.remove());
  }

  async function onLoadMore() {
    if (loading) return;
    loading = true;
    loadBtn.disabled = true;
    hydrateSeen();
    
    showSkeletons(batchSize);
    let items = [];
    try {
      items = await fetchMore();
    } catch {
      items = [];
    }
    
    hideSkeletons();
    
    if (items.length < batchSize) {
      items = items.concat(pickFallback(batchSize - items.length));
    }
    items.forEach((it) => {
      const card = buildCard(it);
      if (card) grid.appendChild(card);
    });
    if (!items.length || (lastPage && page >= lastPage && fallbackIndex >= fallbackPool.length)) {
      loadBtn.remove();
    }
    loadBtn.disabled = false;
    loading = false;
    if (window.AniDexFilters && typeof window.AniDexFilters.apply === "function") {
      window.__aniSuppressSortOnce = true;
      window.AniDexFilters.apply();
    }
    warmPreviewCache(grid);
  }

  if (hasLoadMore) {
    loadBtn.addEventListener("click", onLoadMore);
    // Hide when filters/search are active (set by filters.js)
    window.AniDexLoadMore = {
      hide: () => { if (typeof loadBtn !== 'undefined' && loadBtn) loadBtn.style.display = "none"; },
      show: () => { if (typeof loadBtn !== 'undefined' && loadBtn) loadBtn.style.display = ""; }
    };
    bindHoverPreviewFor(grid);
    if (!isSeriesPage) warmPreviewCache(grid);
  }

  window.AniDexHoverPreview = {
    bind: (root) => {
      const scope = root || document;
      bindHoverPreviewFor(scope);
      if (!isSeriesPage) warmPreviewCache(scope);
    }
  };
})();



