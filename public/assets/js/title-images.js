(() => {
  const API_BASE = "api/jikan_proxy.php";
  const cache = {};
  const YEAR_BADGE_PAGES = /series\.php|peliculas\.php/i.test(window.location.pathname || "");
  const DEFAULT_FALLBACK = "img/fondoanime.png";
  const INDEX_TITLE_ALIASES = {
    "Frieren: Ms all del final del viaje": "Frieren: Beyond Journey's End",
    "Solo Leveling": "Solo Leveling",
    "The Apothecary Diaries": "Kusuriya no Hitorigoto",
    "Undead Unluck": "Undead Unluck",
    "Haikyuu!! Final": "Haikyuu!! Movie: Gomisuteba no Kessen"
  };
  const MOVIE_TITLE_ALIASES = {
    "Your Name": "Kimi no Na wa.",
    "Spirited Away": "Sen to Chihiro no Kamikakushi",
    "A Silent Voice": "Koe no Katachi",
    "Weathering With You": "Tenki no Ko",
    "Princess Mononoke": "Mononoke Hime",
    "Demon Slayer: Mugen Train": "Kimetsu no Yaiba Movie: Mugen Ressha-hen",
    "Howl's Moving Castle": "Howl no Ugoku Shiro",
    "Castle in the Sky": "Tenkuu no Shiro Laputa",
    "The Girl Who Leapt Through Time": "Toki wo Kakeru Shoujo",
    "Wolf Children": "Ookami Kodomo no Ame to Yuki",
    "The Boy and the Heron": "Kimitachi wa Dou Ikiru ka",
    "Paprika": "Paprika",
    "Tokyo Godfathers": "Tokyo Godfathers",
    "Kiki's Delivery Service": "Majo no Takkyuubin",
    "The Wind Rises": "Kaze Tachinu",
    "5 Centimeters per Second": "Byousoku 5 Centimeter",
    "Millennium Actress": "Sennen Joyuu",
    "Ghost in the Shell": "Koukaku Kidoutai",
    "Nausicaa of the Valley of the Wind": "Kaze no Tani no Nausicaa",
    "The Tale of the Princess Kaguya": "Kaguya-hime no Monogatari"
  };
  const MOVIE_PREFILL = new Map(
    [
      ["Your Name", { score: 8.4, image: "https://cdn.myanimelist.net/images/anime/5/87048l.jpg" }],
      ["Spirited Away", { score: 8.7, image: "https://cdn.myanimelist.net/images/anime/6/79597l.jpg" }],
      ["A Silent Voice", { score: 8.2, image: "https://cdn.myanimelist.net/images/anime/1122/96435l.jpg" }],
      ["Weathering With You", { score: 8.0, image: "https://cdn.myanimelist.net/images/anime/1880/101146l.jpg" }],
      ["Princess Mononoke", { score: 8.7, image: "https://cdn.myanimelist.net/images/anime/7/75919l.jpg" }],
      ["Demon Slayer: Mugen Train", { score: 8.5, image: "https://cdn.myanimelist.net/images/anime/1704/106947l.jpg" }],
      ["Akira", { score: 8.1, image: "https://cdn.myanimelist.net/images/anime/13/11606l.jpg" }],
      ["Perfect Blue", { score: 8.5, image: "https://cdn.myanimelist.net/images/anime/6/20936l.jpg" }],
      ["Howl's Moving Castle", { score: 8.6, image: "https://cdn.myanimelist.net/images/anime/5/75810l.jpg" }],
      ["Castle in the Sky", { score: 8.2, image: "https://cdn.myanimelist.net/images/anime/4/19644l.jpg" }],
      ["The Girl Who Leapt Through Time", { score: 8.1, image: "https://cdn.myanimelist.net/images/anime/5/30651l.jpg" }],
      ["Wolf Children", { score: 8.6, image: "https://cdn.myanimelist.net/images/anime/9/35721l.jpg" }],
      ["The Boy and the Heron", { score: 8.3, image: "https://cdn.myanimelist.net/images/anime/1938/135656l.jpg" }],
      ["Paprika", { score: 8.0, image: "https://cdn.myanimelist.net/images/anime/12/23809l.jpg" }],
      ["Tokyo Godfathers", { score: 7.9, image: "https://cdn.myanimelist.net/images/anime/2/16571l.jpg" }],
      ["Promare", { score: 7.9, image: "https://cdn.myanimelist.net/images/anime/1368/101573l.jpg" }],
      ["Redline", { score: 8.3, image: "https://cdn.myanimelist.net/images/anime/7/19633l.jpg" }],
      ["Kiki's Delivery Service", { score: 8.2, image: "https://cdn.myanimelist.net/images/anime/2/7597l.jpg" }],
      ["The Wind Rises", { score: 8.2, image: "https://cdn.myanimelist.net/images/anime/12/47059l.jpg" }],
      ["5 Centimeters per Second", { score: 7.6, image: "https://cdn.myanimelist.net/images/anime/6/94221l.jpg" }],
      ["Millennium Actress", { score: 7.9, image: "https://cdn.myanimelist.net/images/anime/2/11232l.jpg" }],
      ["Ghost in the Shell", { score: 8.3, image: "https://cdn.myanimelist.net/images/anime/10/8251l.jpg" }],
      ["Nausicaa of the Valley of the Wind", { score: 8.1, image: "https://cdn.myanimelist.net/images/anime/10/7597l.jpg" }],
      ["The Tale of the Princess Kaguya", { score: 8.2, image: "https://cdn.myanimelist.net/images/anime/2/61237l.jpg" }]
    ].map(([k, v]) => [norm(k), v])
  );
  const MOVIE_TITLE_ALIASES_NORM = new Map(
    Object.entries(MOVIE_TITLE_ALIASES).map(([k, v]) => [norm(k), v])
  );
  const INDEX_FIXED_BY_TITLE = {
    "Frieren: Ms all del final del viaje": 52991, // Sousou no Frieren
    "Solo Leveling": 52299,
    "The Apothecary Diaries": 54492, // Kusuriya no Hitorigoto
    "Undead Unluck": 52741,
    "Haikyuu!! Final": 20583 // Haikyuu!!
  };

  function imageOf(item) {
    return (
      item?.images?.webp?.large_image_url ||
      item?.images?.jpg?.large_image_url ||
      item?.images?.jpg?.image_url ||
      ""
    );
  }

  function applyImageFallback(img) {
    if (!img) return;
    if (img.dataset.fallbackApplied) delete img.dataset.fallbackApplied;
    if (!img.dataset.originalSrc) img.dataset.originalSrc = img.getAttribute("src") || "";
    if (!img.dataset.fallback) img.dataset.fallback = DEFAULT_FALLBACK;
    if (!img.loading) img.loading = "lazy";
    img.decoding = "async";
    if (!img.referrerPolicy) img.referrerPolicy = "no-referrer";

    if (!img.dataset.fallbackBound) {
      const onError = () => {
        if (img.dataset.fallbackApplied === "1") return;
        img.dataset.fallbackApplied = "1";
        const fallback = img.dataset.fallback || img.dataset.originalSrc || DEFAULT_FALLBACK;
        if (fallback && img.src !== fallback) img.src = fallback;
      };
      img.addEventListener("error", onError);
      img.dataset.fallbackBound = "1";
    }
  }

  function primeImageFallbacks(scope = document) {
    Array.from(scope.querySelectorAll("img[data-fallback]")).forEach(applyImageFallback);
  }

  async function searchByTitle(title) {
    if (!title) return null;
    const url =
      "api/jikan_proxy.php?endpoint=" + encodeURIComponent("anime?q=" +
      encodeURIComponent(title) +
      "&limit=10&order_by=popularity&sort=asc");
    const json = await (window.AniDexShared?.fetchJson ? window.AniDexShared.fetchJson(url) : fetch(url).then((res) => res.ok ? res.json() : null).catch(() => null));
    return (json?.data || [])[0] || null;
  }

  function norm(text) {
    const base = window.AniDexShared?.normalizeText ? window.AniDexShared.normalizeText(text) : String(text || "").toLowerCase().trim();
    return base
      .replace(/[^\w\s]/g, " ")
      .replace(/\s+/g, " ")
      .trim();
  }

  function allTitles(item) {
    return [
      item?.title,
      item?.title_english,
      item?.title_japanese,
      ...(item?.titles || []).map((t) => t.title)
    ].filter(Boolean);
  }

  function scoreMatch(query, candidate) {
    const q = norm(query);
    const c = norm(candidate);
    if (!q || !c) return 0;
    if (q === c) return 100;
    if (c.includes(q) || q.includes(c)) return 80;
    const qTokens = q.split(" ").filter(Boolean);
    const cTokens = c.split(" ").filter(Boolean);
    if (!qTokens.length || !cTokens.length) return 0;
    const overlap = qTokens.filter((t) => cTokens.includes(t)).length;
    return Math.round((overlap / Math.max(qTokens.length, cTokens.length)) * 60);
  }

  const movieSearchCache = new Map();
  async function searchMovieByTitle(rawTitle, year) {
    const rawKey = norm(rawTitle);
    const query = MOVIE_TITLE_ALIASES_NORM.get(rawKey) || rawTitle;
    const key = `${norm(query)}:${year || ""}`;
    if (!key) return null;
    if (movieSearchCache.has(key)) return movieSearchCache.get(key);
    try {
      const url =
        "api/jikan_proxy.php?endpoint=" + encodeURIComponent("anime?q=" +
        encodeURIComponent(query) +
        "&type=movie&limit=10&order_by=popularity&sort=desc");
      let res = await fetch(url);
      if (res.status === 429) {
        await new Promise((r) => setTimeout(r, 700));
        res = await fetch(url);
      }
      if (!res.ok) {
        movieSearchCache.set(key, null);
        return null;
      }
      const json = await res.json();
      const list = json?.data || [];
      let best = null;
      let bestScore = 0;
      list.forEach((item) => {
        const titles = allTitles(item);
        let score = 0;
        titles.forEach((t) => {
          score = Math.max(score, scoreMatch(query, t));
          if (rawTitle && rawTitle !== query) score = Math.max(score, scoreMatch(rawTitle, t));
        });
        if (!score) return;
        if ((item?.type || "").toLowerCase().includes("movie")) score += 10;
        const itemYear = item?.year || item?.aired?.prop?.from?.year;
        if (year && itemYear) {
          if (String(itemYear) === String(year)) score += 12;
          else score -= 6;
        }
        if (score > bestScore) {
          bestScore = score;
          best = item;
        }
      });
      const picked = bestScore >= 45 ? best : null;
      movieSearchCache.set(key, picked);
      return picked;
    } catch {
      movieSearchCache.set(key, null);
      return null;
    }
  }

  async function searchByTitleCanonical(rawTitle) {
    const query = INDEX_TITLE_ALIASES[rawTitle] || rawTitle;
    const base = await searchByTitle(query);
    if (!base) return null;

    try {
      const url =
        "api/jikan_proxy.php?endpoint=" + encodeURIComponent("anime?q=" +
        encodeURIComponent(query) +
        "&limit=10&order_by=popularity&sort=asc");
      const res = await fetch(url);
      if (!res.ok) return base;
      const json = await res.json();
      const list = json?.data || [];
      const qn = norm(query);
      const exact = list.find((item) =>
        allTitles(item).some((t) => norm(t) === qn || norm(t).includes(qn) || qn.includes(norm(t)))
      );
      return exact || base;
    } catch {
      return base;
    }
  }

  async function fetchByMalId(id) {
    if (!id) return null;
    const key = `id:${id}`;
    if (cache[key]) return cache[key];
    try {
      const res = await fetch(`api/jikan_proxy.php?endpoint=${encodeURIComponent("anime/" + id + "/full")}`);
      if (!res.ok) return null;
      const json = await res.json();
      const item = json?.data || null;
      if (item) cache[key] = item;
      return item;
    } catch {
      return null;
    }
  }

  function trailerOrImageOf(item) {
    return (
      item?.trailer?.images?.maximum_image_url ||
      item?.trailer?.images?.large_image_url ||
      imageOf(item)
    );
  }

  const TOP_CACHE_TTL = 1000 * 60 * 60 * 6; // 6 horas
  function loadTopCache(type) {
    try {
      const raw = localStorage.getItem(`anidex_top_cache_v1_${type}`);
      if (!raw) return null;
      const parsed = JSON.parse(raw);
      if (!parsed || !Array.isArray(parsed.items)) return null;
      const fresh = Date.now() - Number(parsed.ts || 0) < TOP_CACHE_TTL;
      return { items: parsed.items, fresh };
    } catch {
      return null;
    }
  }
  function saveTopCache(type, items) {
    try {
      localStorage.setItem(`anidex_top_cache_v1_${type}`, JSON.stringify({ ts: Date.now(), items }));
    } catch {}
  }

  async function fetchTop(type, needed) {
    const key = `${type}:${needed}`;
    if (cache[key]) return cache[key];

    const cached = loadTopCache(type);
    if (cached?.items?.length && cached.fresh) {
      cache[key] = cached.items;
      return cached.items;
    }

    const out = [];
    let page = 1;
    while (out.length < needed && page <= 5) {
      const url =
        `${API_BASE}?endpoint=${encodeURIComponent('top/anime?filter=bypopularity&type=' + encodeURIComponent(type) + '&limit=25&page=' + page)}`;
      let res = await fetch(url);
      if (res.status === 429) {
        await new Promise((r) => setTimeout(r, 900));
        res = await fetch(url);
      }
      if (!res.ok) break;
      const json = await res.json();
      const rows = json?.data || [];
      if (!rows.length) break;
      out.push(...rows);
      page += 1;
    }
    if (out.length) {
      saveTopCache(type, out);
      cache[key] = out;
      return out;
    }
    const fallback = cached?.items || [];
    cache[key] = fallback;
    return fallback;
  }

  function setTitle(card, title) {
    const h = card.querySelector("h3,h4,h5");
    if (h) h.textContent = title;

    const landscapeTitle = card.querySelector("span.font-bold");
    if (landscapeTitle && !h) landscapeTitle.textContent = title;
  }

  function setMeta(card, item) {
    if (YEAR_BADGE_PAGES) return;
    const p = card.querySelector("p");
    if (!p) return;
    const studio = item?.studios?.[0]?.name || "N/A";
    p.textContent = `Estudio: ${studio}`;
  }

  function setImage(card, item) {
    const img = card.querySelector("img");
    if (!img) return;
    const title = item?.title || "Anime";
    const src = imageOf(item);
    if (src) {
      img.src = src;
      img.dataset.dynamicPoster = "1";
    }
    img.alt = title;
    img.loading = "lazy";
    applyImageFallback(img);
  }

  function setBadge(card, item, type) {
    const badge = card.querySelector("span.absolute.left-3.top-3, span.absolute.left-3.bottom-3");
    if (!badge) return;
    if (YEAR_BADGE_PAGES) {
      const year =
        card.getAttribute("data-year") ||
        card.querySelector("[data-card-year]")?.textContent?.trim() ||
        (item?.year ? String(item.year) : "");
      if (year) badge.textContent = year;
      return;
    }
    if (type === "movie") {
      const mins = (item?.duration || "").match(/\d+\s*min/i)?.[0] || (item?.duration || "");
      if (mins) badge.textContent = mins.trim();
      return;
    }
    const eps = item?.episodes;
    if (eps) badge.textContent = `${eps} ep`;
  }

  function setScore(card, item) {
    const media = card.querySelector(".relative");
    if (!media) return;
    const scoreValue = typeof item?.score === "number" ? item.score.toFixed(1) : null;
    if (!scoreValue) return;

    let badge = media.querySelector(".anidex-score-badge");
    if (!badge) {
      badge = document.createElement("div");
      badge.className =
        "anidex-score-badge absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1";
      badge.innerHTML =
        "<span class=\"material-symbols-outlined text-[10px]\" style=\"font-variation-settings: 'FILL' 1;\">star</span><span></span>";
      media.appendChild(badge);
    }
    const valueEl = badge.querySelector("span:last-child");
    if (valueEl) valueEl.textContent = scoreValue;
  }

  function setDataAttrs(card, item, type) {
    if (item?.mal_id) {
      card.setAttribute("data-mal-id", String(item.mal_id));
      const link = card.matches("a") ? card : card.querySelector("a");
      if (link) link.setAttribute("data-mal-id", String(item.mal_id));
    }
    if (!card.hasAttribute("data-anime-card")) return;
    const genres = (item?.genres || []).map((g) => (g?.name || "").toLowerCase()).filter(Boolean);
    const path = (window.location.pathname || "").toLowerCase();
    const keepCatalogMovieYear = type === "movie" && path.includes("peliculas");
    const existingYear = String(card.getAttribute("data-year") || "").trim();
    card.setAttribute("data-title", (item?.title || "").toLowerCase());
    card.setAttribute("data-genres", genres.join(","));
    const year = keepCatalogMovieYear && existingYear ? existingYear : (item?.year ? String(item.year) : "");
    card.setAttribute("data-year", year);
    if (year && !card.getAttribute("data-year-original")) {
      card.setAttribute("data-year-original", year);
    }
    const yearEl = card.querySelector("[data-card-year]");
    if (yearEl && year) yearEl.textContent = year;
    card.setAttribute("data-type", type === "movie" ? "Película" : "Anime");
    card.setAttribute("data-status", "Finalizado");
  }

  function applyItemsToCards(cards, items, type) {
    cards.forEach((card, idx) => {
      const item = items[idx];
      if (!item) return;
      setTitle(card, item.title || "Anime");
      setMeta(card, item);
      setImage(card, item);
      setBadge(card, item, type);
      setScore(card, item);
      setDataAttrs(card, item, type);
    });
  }

  async function applyIndexSpecials() {
    // Hero de Frieren
    const heroTitleEl = document.querySelector("h1");
    const heroImg = document.querySelector("section img.hero-mask");
    if (heroTitleEl && heroImg) {
      const title = heroTitleEl.textContent.replace(/\s+/g, " ").trim();
      const fixedId = INDEX_FIXED_BY_TITLE[title];
      let item = fixedId ? await fetchByMalId(fixedId) : await searchByTitleCanonical(title);
      if (!item && /haikyuu/i.test(title)) {
        item = await searchByTitleCanonical("Haikyuu!!");
      }
      const src = trailerOrImageOf(item);
      if (src) heroImg.src = src;
    }

    // Destacados de la temporada
    const seasonal = Array.from(
      document.querySelectorAll("section img[alt*='Destacado'], section img[alt*='Spotlight']")
    ).filter((img) => !img.hasAttribute("data-spotlight-rotating"));

    for (const img of seasonal) {
      const card = img.closest(".group.cursor-pointer");
      if (!card) continue;
      const titleEl = card.querySelector("h3,h4");
      const title = titleEl?.textContent?.replace(/\s+/g, " ").trim();
      if (!title) continue;
      const fixedId = INDEX_FIXED_BY_TITLE[title];
      if (fixedId) card.setAttribute("data-mal-id", String(fixedId));
      const item = fixedId ? await fetchByMalId(fixedId) : await searchByTitleCanonical(title);
      if (!fixedId && item?.mal_id) card.setAttribute("data-mal-id", String(item.mal_id));
      const src = trailerOrImageOf(item);
      if (src) {
        img.src = src;
        img.alt = title;
      }
      applyImageFallback(img);
    }
  }

  function cardsBySelector(selector) {
    return Array.from(document.querySelectorAll(selector));
  }

  function getCardTitle(card) {
    return (
      card.querySelector("h3,h4,h5")?.textContent?.replace(/\s+/g, " ").trim() ||
      card.getAttribute("data-title") ||
      ""
    );
  }

  function getCardYear(card) {
    const raw =
      card.getAttribute("data-year") ||
      card.querySelector("[data-card-year]")?.textContent ||
      "";
    const match = String(raw).match(/(19|20)\d{2}/);
    return match ? match[0] : "";
  }

  async function applyAnimesPage() {
    const cards = cardsBySelector("[data-anime-card]");
    if (!cards.length) return;

    // En series.php conservamos los datos renderizados por la base local
    // para evitar mezclar portadas, títulos y enlaces al refrescar.
    cards.forEach((card) => {
      const img = card.querySelector("img");
      if (img) applyImageFallback(img);
    });
  }

  function loadMovieCache() {
    try {
      const raw = localStorage.getItem("anidex_movie_cache_v1");
      return raw ? JSON.parse(raw) : {};
    } catch {
      return {};
    }
  }

  function prefillMovieCard(card) {
    const title = getCardTitle(card);
    if (!title) return false;
    const key = norm(title);
    const info = MOVIE_PREFILL.get(key);
    if (!info) return false;
    const img = card.querySelector("img");
    if (img) {
      applyImageFallback(img);
      const current = img.getAttribute("src") || "";
      if (!current || /fondoanime\.png$/i.test(current) || current.includes("fondoanime.png")) {
        img.src = info.image;
      }
    }
    if (info.score) setScore(card, { score: info.score });
    return true;
  }

  function saveMovieCache(cacheObj) {
    try {
      localStorage.setItem("anidex_movie_cache_v1", JSON.stringify(cacheObj));
    } catch {
      // ignore storage failures
    }
  }

  async function applyCatalogPage() {
    const cards = cardsBySelector("[data-anime-card]");
    if (!cards.length) return;
    let prefilled = 0;
    cards.forEach((card) => {
      if (prefillMovieCard(card)) prefilled += 1;
    });
    const allPrefilled = prefilled === cards.length;
    if (allPrefilled) return;
    const items = await fetchTop("movie", cards.length);
    // Only replace posters when we can match by title to avoid mismatches.
    const byTitle = new Map();
    if (items.length) {
      items.forEach((item) => {
        [item?.title, item?.title_english, item?.title_japanese].forEach((t) => {
          const key = norm(t);
          if (key && !byTitle.has(key)) byTitle.set(key, item);
        });
      });
    }
    const cache = loadMovieCache();
    let cacheDirty = false;
    for (const card of cards) {
      const img = card.querySelector("img");
      if (img) applyImageFallback(img);

      const posterFixed = card.hasAttribute("data-poster-fixed");
      const title = getCardTitle(card);
      const year = getCardYear(card);
      const cacheKey = `${norm(title)}:${year || ""}`;
      const cached = cache[cacheKey];
      if (cached) {
        if (!posterFixed && cached.image) {
          setImage(card, { title, images: { jpg: { large_image_url: cached.image } } });
        }
        if (cached.score) setScore(card, { score: cached.score });
        if (cached.mal_id) {
          setDataAttrs(card, { mal_id: cached.mal_id, genres: cached.genres || [] }, "movie");
        }
      }

      let item = byTitle.get(norm(title));
      if (item && year && item?.year && String(item.year) !== String(year)) item = null;
      if (!item) {
        item = await searchMovieByTitle(title, year);
      }
      if (!item) continue;
      if (!posterFixed) setImage(card, item);
      setScore(card, item);
      setDataAttrs(card, item, "movie");
      cache[cacheKey] = {
        image: imageOf(item),
        score: item?.score || "",
        mal_id: item?.mal_id || "",
        genres: item?.genres || []
      };
      cacheDirty = true;
    }
    if (cacheDirty) saveMovieCache(cache);
  }

  async function applyIndexPage() {
    const tvCards = cardsBySelector("#trending-row .flex-none.group.cursor-pointer");
    const movieCards = cardsBySelector("#movies-row .flex-none.group.cursor-pointer");

    if (tvCards.length) {
      const tvItems = await fetchTop("tv", tvCards.length);
      applyItemsToCards(tvCards, tvItems, "tv");
    }

    if (movieCards.length) {
      const movieItems = await fetchTop("movie", movieCards.length);
      applyItemsToCards(movieCards, movieItems, "movie");
    }
  }

  async function applyDetailPage() {
    const recCards = cardsBySelector("section a.group.cursor-pointer");
    if (!recCards.length) return;
    const items = await fetchTop("tv", recCards.length);
    applyItemsToCards(recCards, items, "tv");
  }

  async function applyUserPage() {
    const userCards = cardsBySelector("main .group.cursor-pointer");
    if (!userCards.length) return;
    
    // For user page, we must search each title specifically to avoid mismatches
    for (const card of userCards) {
      const title = getCardTitle(card);
      if (!title) continue;
      
      const item = await searchByTitleCanonical(title);
      if (item) {
        setImage(card, item);
        setScore(card, item);
        setDataAttrs(card, item, "tv");
        // Update badge (episodes)
        const eps = item?.episodes;
        const badge = card.querySelector("span.absolute.left-3.top-3, span.absolute.left-3.bottom-3");
        if (badge && eps) badge.textContent = `${eps} ep`;
      }
    }
  }

  window.AniDexTitleImages = {
    async init() {
      const path = (window.location.pathname || "").toLowerCase();
      try {
        primeImageFallbacks();
        if (path.includes("series")) await applyAnimesPage();
        else if (path.includes("peliculas")) await applyCatalogPage();
        else if (path.includes("detail")) await applyDetailPage();
        else if (path.includes("user")) await applyUserPage();
        else {
          await applyIndexPage();
          await applyIndexSpecials();
        }
      } catch {
        // Keep original hardcoded cards if API fails.
      }
    }
  };
})();


