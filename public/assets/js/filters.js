(function () {
  const appUrl = window.AniDexShared?.buildAppUrl || ((path = "") => String(path || ""));
  const buildDetailUrl = window.AniDexShared?.buildDetailUrl || ((malId = "", title = "") => {
    const numericId = String(malId || "").trim();
    if (/^\d+$/.test(numericId)) return appUrl(`detail/${encodeURIComponent(numericId)}`);
    const cleanTitle = String(title || "").trim();
    return cleanTitle ? appUrl(`detail?q=${encodeURIComponent(cleanTitle)}`) : appUrl("detail");
  });
  let GENRE_OPTIONS = window.DB_GENRES || [
    "Acci\u00f3n",
    "Aventura",
    "Comedia",
    "Drama",
    "Romance",
    "Fantas\u00eda",
    "Ciencia ficci\u00f3n",
    "Sobrenatural",
    "Misterio",
    "Psicol\u00f3gico",
    "Thriller",
    "Suspenso",
    "Horror",
    "Slice of Life",
    "Deportes",
    "Artes marciales",
    "Mecha",
    "Isekai",
    "Escolar",
    "Militar",
    "Hist\u00f3rico",
    "Magia",
    "M\u00fasica",
    "Superh\u00e9roes",
    "Detectives",
    "Vampiros",
    "Superpoderes",
    "Supervivencia",
    "Tragedia",
    "Recuentos de vida",
    "Parodia",
    "Samur\u00e1i",
    "Cyberpunk",
    "Post-apocal\u00edptico",
    "Distop\u00eda",
    "Viajes en el tiempo",
    "Reencarnaci\u00f3n",
    "Demons (Demonios)",
    "\u00c1ngeles",
    "Aliens",
    "Videojuegos",
    "Magical Girl",
    "Idols",
    "Cocina",
    "Trabajo (Oficios)",
    "Policial",
    "Espionaje",
    "Crimen",
    "Aventura espacial",
    "Realidad virtual",
  ];
  const GENRE_MAP = {
    "accion": "Acci\u00f3n",
    "action": "Acci\u00f3n",
    "adventure": "Aventura",
    "aventura": "Aventura",
    "comedy": "Comedia",
    "comedia": "Comedia",
    "drama": "Drama",
    "romance": "Romance",
    "fantasy": "Fantas\u00eda",
    "fantasia": "Fantas\u00eda",
    "sci fi": "Ciencia ficci\u00f3n",
    "sci-fi": "Ciencia ficci\u00f3n",
    "science fiction": "Ciencia ficci\u00f3n",
    "sobrenatural": "Sobrenatural",
    "supernatural": "Sobrenatural",
    "mystery": "Misterio",
    "misterio": "Misterio",
    "psychological": "Psicol\u00f3gico",
    "psicologico": "Psicol\u00f3gico",
    "thriller": "Thriller",
    "suspense": "Suspenso",
    "suspenso": "Suspenso",
    "horror": "Horror",
    "terror": "Horror",
    "slice of life": "Slice of Life",
    "recuentos de vida": "Slice of Life",
    "sports": "Deportes",
    "deportes": "Deportes",
    "martial arts": "Artes marciales",
    "artes marciales": "Artes marciales",
    "mecha": "Mecha",
    "isekai": "Isekai",
    "school": "Escolar",
    "escolar": "Escolar",
    "military": "Militar",
    "militar": "Militar",
    "historical": "Hist\u00f3rico",
    "historico": "Hist\u00f3rico",
    "magic": "Magia",
    "magia": "Magia",
    "music": "M\u00fasica",
    "musica": "M\u00fasica",
    "superhero": "Superh\u00e9roes",
    "superheroes": "Superh\u00e9roes",
    "detective": "Detectives",
    "detectives": "Detectives",
    "vampire": "Vampiros",
    "vampiros": "Vampiros",
    "super power": "Superpoderes",
    "superpower": "Superpoderes",
    "superpoderes": "Superpoderes",
    "survival": "Supervivencia",
    "supervivencia": "Supervivencia",
    "tragedy": "Tragedia",
    "tragedia": "Tragedia",
    "parody": "Parodia",
    "parodia": "Parodia",
    "samurai": "Samur\u00e1i",
    "cyberpunk": "Cyberpunk",
    "post apocalyptic": "Post-apocal\u00edptico",
    "post-apocalyptic": "Post-apocal\u00edptico",
    "distopia": "Distop\u00eda",
    "dystopia": "Distop\u00eda",
    "time travel": "Viajes en el tiempo",
    "viajes en el tiempo": "Viajes en el tiempo",
    "reincarnation": "Reencarnaci\u00f3n",
    "reencarnacion": "Reencarnaci\u00f3n",
    "demon": "Demons (Demonios)",
    "demons": "Demons (Demonios)",
    "demonios": "Demons (Demonios)",
    "angel": "\u00c1ngeles",
    "angeles": "\u00c1ngeles",
    "aliens": "Aliens",
    "alien": "Aliens",
    "video games": "Videojuegos",
    "videojuegos": "Videojuegos",
    "magical girl": "Magical Girl",
    "idols": "Idols",
    "idol": "Idols",
    "cooking": "Cocina",
    "cocina": "Cocina",
    "work": "Trabajo (Oficios)",
    "trabajo": "Trabajo (Oficios)",
    "police": "Policial",
    "policial": "Policial",
    "espionage": "Espionaje",
    "espionaje": "Espionaje",
    "crime": "Crimen",
    "crimen": "Crimen",
    "space": "Aventura espacial",
    "aventura espacial": "Aventura espacial",
    "virtual reality": "Realidad virtual",
    "realidad virtual": "Realidad virtual",
  };
  const STATUS_OPTIONS = ["Todos", "En emisi\u00f3n", "Finalizado", "Pr\u00f3ximamente", "Cancelado", "Pausado"];
  const STATUS_OPTIONS_SERIES = ["Todos", "En emisi\u00f3n", "Finalizado", "Pr\u00f3ximamente", "Cancelado", "Pausado"];
  const STATUS_OPTIONS_MOVIES = ["Todos", "Pr\u00f3ximamente", "En cartelera", "Finalizado", "Cancelado", "Retrasado"];



  function normalize(v) {

    return String(v || "")

      .toLowerCase()

      .normalize("NFD")

      .replace(/[\u0300-\u036f]/g, "")

      .replace(/[^\w\s-]/g, " ")

      .replace(/\s+/g, " ")

      .trim();

  }



  function canonicalGenre(g) {
    const key = normalize(g).replace(/_/g, " ");
    if (GENRE_MAP[key]) return GENRE_MAP[key];
    if (key.includes("action") || key.includes("acci")) return "Acci\u00f3n";
    if (key.includes("advent") || key.includes("avent")) return "Aventura";
    if (key.includes("comedy") || key.includes("comed")) return "Comedia";
    if (key.includes("drama")) return "Drama";
    if (key.includes("fantas")) return "Fantas\u00eda";
    if (key.includes("romance")) return "Romance";
    if (key.includes("sci") || key.includes("science")) return "Ciencia ficci\u00f3n";
    if (key.includes("horror") || key.includes("terror")) return "Horror";
    if (key.includes("mister") || key.includes("mystery")) return "Misterio";
    if (key.includes("supernatural") || key.includes("sobrenat")) return "Sobrenatural";
    if (key.includes("thriller")) return "Thriller";
    if (key.includes("suspense") || key.includes("suspenso")) return "Suspenso";
    if (key.includes("sport") || key.includes("deport")) return "Deportes";
    if (key.includes("school") || key.includes("escolar")) return "Escolar";
    if (key.includes("music") || key.includes("musical")) return "M\u00fasica";
    if (key.includes("histor")) return "Hist\u00f3rico";
    if (key.includes("magic") || key.includes("magia")) return "Magia";
    if (key.includes("psych") || key.includes("psico")) return "Psicol\u00f3gico";
    return "";
  }



  // Si vienen g\u00e9neros desde DB (Jikan suele traerlos en ingl\u00e9s),
  // los normalizamos a una etiqueta can\u00f3nica para que el dropdown se vea en espa\u00f1ol
  // y el matching sea consistente.
  if (Array.isArray(GENRE_OPTIONS) && GENRE_OPTIONS.length) {
    const normalized = GENRE_OPTIONS
      .map((g) => canonicalGenre(g) || String(g || "").trim())
      .filter(Boolean);
    GENRE_OPTIONS = Array.from(new Set(normalized));
  }



  function chip(label, active, onClick, compact) {

    const btn = document.createElement("button");

    btn.type = "button";

    btn.textContent = label;

    btn.className = `${compact ? "px-2.5 py-1 text-[11px]" : "px-3 py-1.5 text-xs"} rounded-md border transition-colors inline-flex items-center justify-center text-center leading-tight ${active ? "bg-primary text-on-primary border-primary" : "bg-surface-container-high text-on-surface-variant border-transparent hover:text-on-surface"}`;

    btn.addEventListener("click", onClick);

    return btn;

  }



  function gridChip(label, active, onClick) {

    const btn = chip(label, active, onClick, true);
    const normalizedLabel = String(label || "").normalize("NFD").replace(/[̀-ͯ]/g, "");
    const allowTwoLines = ["Pelicula original", "Basada en serie"].includes(normalizedLabel);

    btn.classList.add("w-full", "text-center", "inline-flex", "items-center", "justify-center", allowTwoLines ? "min-h-[3.2rem]" : "min-h-[2.85rem]");

    btn.classList.remove("px-2.5");

    btn.classList.add("px-2");

    btn.style.paddingLeft = "0.5rem";

    btn.style.paddingRight = "0.5rem";

    if (allowTwoLines) {
      btn.dataset.multiline = "1";
      btn.style.whiteSpace = "normal";
      btn.style.wordBreak = "normal";
      btn.style.overflow = "visible";
      btn.style.textOverflow = "clip";
      btn.style.lineHeight = "1.15";
      btn.style.paddingTop = "0.45rem";
      btn.style.paddingBottom = "0.45rem";
    }

    return btn;

  }



  function createWrap() {
    const div = document.createElement("div");
    div.className = "anidex-chip-grid";
    if (!document.getElementById("anidex-chip-grid-style")) {
      const style = document.createElement("style");
      style.id = "anidex-chip-grid-style";
      style.textContent = `
        .anidex-chip-grid {
          display: grid;
          grid-template-columns: repeat(2, minmax(0, 1fr));
          gap: 0.5rem;
          align-items: stretch;
          justify-items: stretch;
        }
        .anidex-chip-grid button {
          white-space: nowrap;
          word-break: normal;
          line-height: 1.1;
          overflow: hidden;
          text-overflow: ellipsis;
        }
        .anidex-chip-grid button[data-multiline="1"] {
          white-space: normal !important;
          overflow: visible !important;
          text-overflow: clip !important;
          word-break: normal !important;
          line-height: 1.15 !important;
        }
        @media (max-width: 1024px) {
          .anidex-chip-grid {
            grid-template-columns: repeat(auto-fit, minmax(6.5rem, 1fr));
          }
        }
        @media (max-width: 640px) {
          .anidex-chip-grid {
            grid-template-columns: 1fr;
          }
        }
      `;
      document.head.appendChild(style);
    }
    return div;
  }

  function getYearBounds(isMoviesPage) {
    return {
      min: isMoviesPage ? 1945 : 1917,
      max: 2026
    };
  }

  function buildYearList(min, max) {
    const years = [];
    for (let y = max; y >= min; y -= 1) years.push(y);
    return years;
  }

  function createYearDropdown(kindLabel) {
    const container = document.createElement("div");
    container.className = "flex flex-col gap-1";
    const mini = document.createElement("span");
    mini.className = "text-[11px] uppercase tracking-wide text-on-surface-variant";
    mini.textContent = kindLabel;

    const wrap = document.createElement("div");
    wrap.className = "relative w-full";
    wrap.dataset.yearDropdown = kindLabel.toLowerCase();

    const button = document.createElement("button");
    button.type = "button";
    button.className =
      "w-full relative flex items-center justify-between gap-3 rounded-full bg-surface-container-high pl-4 pr-4 py-2.5 border border-outline/40 text-sm text-on-surface-variant hover:text-on-surface transition-colors overflow-hidden";
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-haspopup", "listbox");
    button.innerHTML =
      `<span data-year-label class="block truncate text-left pr-2">${kindLabel}</span><span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-base">expand_more</span>`;

    const panel = document.createElement("div");
    panel.className =
      "absolute left-0 mt-2 rounded-xl border border-outline/40 bg-surface-container-high p-3 shadow-xl z-30 w-[10rem] max-w-[min(90vw,10rem)]";
    panel.hidden = true;
    panel.dataset.yearPanel = kindLabel.toLowerCase();

    const search = document.createElement("input");
    search.type = "search";
    search.placeholder = "Buscar a\u00f1o";
    search.dataset.noSuggest = "1";
    search.className =
      "w-full rounded-full bg-surface-container-low px-4 py-2 text-sm text-on-surface placeholder:text-on-surface-variant/60 border border-outline/30 focus:outline-none focus:ring-1 focus:ring-primary/40";

    const list = document.createElement("div");
    list.className =
      "anidex-genre-scroll mt-3 max-h-[10rem] overflow-y-auto pr-1 space-y-1 whitespace-nowrap text-left";

    panel.appendChild(search);
    panel.appendChild(list);
    wrap.appendChild(button);
    wrap.appendChild(panel);
    container.appendChild(mini);
    container.appendChild(wrap);

    return { container, wrap, button, panel, search, list, label: mini };
  }

  function createGenreDropdown() {
    const wrap = document.createElement("div");
    wrap.className = "relative w-full";
    wrap.dataset.genreDropdown = "1";

    const button = document.createElement("button");
    button.type = "button";
    button.className =
      "w-full rounded-full bg-surface-container-high pl-4 pr-12 py-2.5 border border-outline/40 text-sm text-on-surface-variant hover:text-on-surface transition-colors overflow-hidden text-left bg-no-repeat";
    button.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%239aa0a6\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'/%3E%3C/svg%3E")';
    button.style.backgroundPosition = "right 0.75rem center";
    button.style.backgroundSize = "14px";
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-haspopup", "listbox");
    button.innerHTML =
      `<span data-genre-label class="block min-w-0 truncate pr-2">Todos</span>`;

    const panel = document.createElement("div");
    panel.className =
      "absolute left-0 mt-2 rounded-xl border border-outline/40 bg-surface-container-high p-3 shadow-xl z-30 w-max min-w-full max-w-[min(90vw,10rem)]";
    panel.hidden = true;
    panel.dataset.genrePanel = "1";

    const search = document.createElement("input");
    search.type = "search";
    search.placeholder = "Buscar g\u00e9nero";
    search.dataset.noSuggest = "1";
    search.dataset.genreSearch = "1";
    search.className =
      "w-full rounded-full bg-surface-container-low px-4 py-2 text-sm text-on-surface placeholder:text-on-surface-variant/60 border border-outline/30 focus:outline-none focus:ring-1 focus:ring-primary/40";

    if (!document.getElementById("anidex-genre-scroll-hide")) {
      const style = document.createElement("style");
      style.id = "anidex-genre-scroll-hide";
      style.textContent = `
        .anidex-genre-scroll {
          scrollbar-width: thin;
          scrollbar-color: rgba(148, 163, 184, 0.45) transparent;
        }
        .anidex-genre-scroll::-webkit-scrollbar {
          width: 6px;
          height: 6px;
        }
        .anidex-genre-scroll::-webkit-scrollbar-track {
          background: transparent;
        }
        .anidex-genre-scroll::-webkit-scrollbar-thumb {
          background-color: rgba(148, 163, 184, 0.45);
          border-radius: 9999px;
          border: 2px solid transparent;
          background-clip: content-box;
        }
      `;
      document.head.appendChild(style);
    }

    const list = document.createElement("div");
    list.className =
      "anidex-genre-scroll mt-3 max-h-[min(40vh,18rem)] overflow-y-auto pr-1 space-y-1 whitespace-nowrap text-left";

    panel.appendChild(search);
    panel.appendChild(list);
    wrap.appendChild(button);
    wrap.appendChild(panel);

    return { wrap, button, panel, search, list };
  }

  function gatherCards() {

    return Array.from(document.querySelectorAll("[data-anime-card]"));

  }



  function readGenresFromCard(card) {

    if (card.dataset.genres) {

      return String(card.dataset.genres)

        .split(",")

        .map((g) => g.trim())

        .filter(Boolean);

    }

    const fromBadges = Array.from(card.querySelectorAll(".flex.flex-wrap.gap-2 span"))

      .map((n) => n.textContent || "")

      .map((t) => t.trim())

      .filter(Boolean);

    if (fromBadges.length) return fromBadges;

    const genreLine = Array.from(card.querySelectorAll("p"))

      .map((p) => p.textContent || "")

      .find((t) => normalize(t).includes("genero"));

    if (genreLine) {

      const after = genreLine.split(":")[1] || genreLine;

      return after.split(",").map((g) => g.trim()).filter(Boolean);

    }

    return [];

  }



  function applyRuntimeCardData(cards) {
    const isMoviesPage = window.location.pathname.toLowerCase().includes("peliculas");
    cards.forEach((card, idx) => {

      if (!card.dataset.originalOrder) {
        card.dataset.originalOrder = String(idx);
      }

      if (!card.dataset.title) {

        const t = card.querySelector("h3,h4")?.textContent?.trim();

        if (t) card.dataset.title = t;

      }

      if (!card.dataset.year) {

        const meta = card.querySelector("p")?.textContent || "";

        const y = meta.match(/(19|20)\d{2}/)?.[0];

        if (y) card.dataset.year = y;

      }

      if (card.dataset.year && !card.dataset.yearOriginal) {

        card.dataset.yearOriginal = card.dataset.year;

      }

      if (!card.dataset.genres) {

        const gs = readGenresFromCard(card).map((g) => canonicalGenre(g)).filter(Boolean);

        card.dataset.genres = gs.join(",");

      }

      if (!card.dataset.type) {
        const badge = card.querySelector("span.absolute")?.textContent || "";
        const b = normalize(badge);
        if (isMoviesPage) {
          if (b.includes("pelicula") || b.includes("movie") || b.includes("film")) {
            card.dataset.type = "Pel\u00edcula original";
          } else {
            card.dataset.type = "Pel\u00edcula original";
          }
        } else {
          if (b.includes("ova")) card.dataset.type = "OVA";
          else if (b.includes("ona")) card.dataset.type = "ONA";
          else if (b.includes("especial")) card.dataset.type = "Especiales";
          else if (b.includes("corto") || b.includes("short")) card.dataset.type = "Cortos";
          else if (b.includes("pelicula") || b.includes("movie")) card.dataset.type = "Pel\u00edcula";
          else card.dataset.type = "Serie de TV";
        }
      }

      if (!card.dataset.status) card.dataset.status = "Finalizado";

    });

  }



  function setup() {
    if (window.__aniDexFiltersInitialized) return;
    window.__aniDexFiltersInitialized = true;

    const cards = gatherCards();

    if (cards.length) {
      applyRuntimeCardData(cards);
    }

    const grid = cards[0]?.parentElement || null;
    let emptyBox = null;

    const state = {
      search: "",
      genres: new Set(),
      years: new Set(),
      types: new Set(),
      statuses: new Set(),
      sort: "popularity_desc",
      isFetchingGlobal: false
    };

    const hideCardYears = /series\.php|peliculas\.php/i.test(window.location.pathname || "");

    const available = {
      genres: new Set(),
      years: new Set(),
      yearFrom: null,
      yearTo: null,
      types: new Set(),
      statuses: new Set()
    };

    cards.forEach((card) => {

      readGenresFromCard(card).map((g) => canonicalGenre(g)).filter(Boolean).forEach((g) => available.genres.add(g));

      const y = Number(card.dataset.year || 0);

      if (y) available.years.add(y);

      const t = canonicalType(card.dataset.type || "");

      if (t) available.types.add(t);

      const s = canonicalStatus(card.dataset.status || "");

      if (s) available.statuses.add(s);

    });



    const searchInput = document.querySelector('[data-catalog-search="1"]') || document.getElementById("filter-search");

    if (!searchInput) return;



    const searchWrap = searchInput.closest("div");

    if (searchWrap) {

      searchWrap.className = "flex items-center gap-2 rounded-full bg-surface-container-high px-4 py-2.5 border border-outline/40 focus-within:border-primary/60 focus-within:ring-1 focus-within:ring-primary/30 transition-all";

      searchInput.className = "w-full bg-transparent border-none focus:ring-0 text-sm placeholder:text-on-surface-variant/60";

    }



    const labels = Array.from(document.querySelectorAll("label"));

    let genreLabel = labels.find((l) => normalize(l.textContent).includes("genero"));

    if (!genreLabel) genreLabel = labels.find((l) => normalize(l.textContent).includes("gen"));

    const genreHeading = Array.from(document.querySelectorAll("*"))

      .find((el) => el.tagName !== "SCRIPT" && el.tagName !== "STYLE" && normalize(el.textContent).trim() === "generos");

    const yearLabel = document.querySelector("label[for='filter-year']");

    const typeLabel = document.querySelector("label[for='filter-type']");

    const statusLabel = document.querySelector("label[for='filter-status']");

    const isMoviesPage = window.location.pathname.toLowerCase().includes("peliculas");

    if (isMoviesPage) {

      if (genreLabel) genreLabel.textContent = "G\u00e9neros";

      if (yearLabel) yearLabel.textContent = "A\u00f1o";

      if (typeLabel) typeLabel.textContent = "Tipo";

      if (statusLabel) statusLabel.textContent = "Estado";

    }

    if (isMoviesPage) {

      // Move duration badge to bottom-left and remove "Película" label.

      cards.forEach((card) => {

        const badge = card.querySelector("span.absolute.left-3.top-3, span.absolute.left-3.bottom-3");

        if (!badge) return;

        const txt = (badge.textContent || "")

          .replace(/pel[i]?cula\\s*/i, "")

          .replace(/pel\\?cula\\s*/i, "")

          .replace(/\s{2,}/g, " ")

          .trim();

        if (txt) badge.textContent = txt;

        badge.classList.remove("top-3");

        badge.classList.add("bottom-3");

      });

    }



    const genreDropdown = createGenreDropdown();
    const typeWrap = createWrap();

    typeWrap.classList.add("anidex-type-wrap");

    const statusWrap = createWrap();

    statusWrap.classList.add("anidex-status-wrap");
    const yearFrom = createYearDropdown("Desde");
    const yearTo = createYearDropdown("Hasta");
    yearFrom.wrap.style.marginLeft = "-6px";
    yearTo.wrap.style.marginLeft = "-6px";
    const yearRangeWrap = document.createElement("div");
    yearRangeWrap.className = "w-full grid grid-cols-2 gap-3 anidex-year-wrap";
    yearRangeWrap.appendChild(yearFrom.container);
    yearRangeWrap.appendChild(yearTo.container);
    const yearBounds = getYearBounds(isMoviesPage);
    const allYears = buildYearList(yearBounds.min, yearBounds.max);
    const recentYears = allYears.slice(0, 4);



    const filterPanel = document.querySelector("section[aria-label='Filtros']");

    const genreHost = isMoviesPage

      ? (genreLabel?.parentElement

        || genreHeading?.parentElement

        || filterPanel?.querySelector(".space-y-2:nth-of-type(2)")

        || filterPanel?.querySelector(".space-y-2"))

      : (genreLabel?.parentElement || genreHeading?.parentElement);

      if (genreHost) {
        const oldButtons = genreHost.querySelector(".flex.flex-wrap.gap-2");
        if (oldButtons) oldButtons.remove();
        genreHost
          .querySelectorAll(".anidex-genre-wrap,[data-genre-dropdown],[data-genre-chips]")
          .forEach((n) => n.remove());
        genreHost.appendChild(genreDropdown.wrap);
      }
    if (yearLabel?.parentElement) {

      yearLabel.parentElement.querySelectorAll(".anidex-year-wrap").forEach((n) => n.remove());

      yearLabel.parentElement.appendChild(yearRangeWrap);

    }

    if (!isMoviesPage) {
      if (typeLabel?.parentElement) {
        typeLabel.parentElement.querySelectorAll(".anidex-type-wrap").forEach((n) => n.remove());
        typeLabel.parentElement.appendChild(typeWrap);
      }
      if (statusLabel?.parentElement) {
        statusLabel.parentElement.querySelectorAll(".anidex-status-wrap").forEach((n) => n.remove());
        statusLabel.parentElement.appendChild(statusWrap);
      }
    } else {
      if (typeLabel?.parentElement) {
        typeLabel.parentElement.querySelectorAll(".anidex-type-wrap").forEach((n) => n.remove());
        typeLabel.parentElement.appendChild(typeWrap);
      }
      if (statusLabel?.parentElement) {
        statusLabel.parentElement.querySelectorAll(".anidex-status-wrap").forEach((n) => n.remove());
        statusLabel.parentElement.appendChild(statusWrap);
      }
    }



    const oldGenreRow = genreLabel?.parentElement?.querySelector("div.flex.flex-wrap.gap-2");

    if (oldGenreRow) oldGenreRow.remove();

    ["filter-year", "filter-type", "filter-status"].forEach((id) => {

      const el = document.getElementById(id);

      if (el) el.remove();

    });



    Array.from(document.querySelectorAll("button")).forEach((btn) => {

      const t = normalize(btn.textContent);

      if (t === "aplicar" || t === "reiniciar") btn.remove();

    });



    const sortHost = Array.from(document.querySelectorAll("header .flex.items-center.gap-3")).find((d) => normalize(d.textContent).includes("ordenar"));

    let sortSelect = null;

    if (sortHost) {

      const oldButton = sortHost.querySelector("button");

      if (oldButton) oldButton.remove();

      const existingSelect = sortHost.querySelector("select");

      if (existingSelect) {

        sortSelect = existingSelect;

      } else {

        sortSelect = document.createElement("select");

        sortSelect.className = "anidex-sort rounded-xl bg-surface-container-high px-3 py-2 text-sm text-on-surface border border-outline/40 shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/40";

        sortSelect.innerHTML = [

          ["popularity_desc", "Popularidad"],

          ["year_desc", "A\u00f1o (m\u00e1s nuevo)"],

          ["year_asc", "A\u00f1o (m\u00e1s antiguo)"],

          ["title_asc", "T\u00edtulo (A-Z)"],

          ["title_desc", "T\u00edtulo (Z-A)"]

        ].map(([v, l]) => `<option value="${v}">${l}</option>`).join("");

        sortHost.appendChild(sortSelect);

        if (!document.getElementById("anidex-sort-style")) {
          const style = document.createElement("style");
          style.id = "anidex-sort-style";
          style.textContent = `
            .anidex-sort {
              appearance: none;
              -webkit-appearance: none;
              -moz-appearance: none;
              padding-right: 2.25rem;
              background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239aa0a6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
              background-repeat: no-repeat;
              background-position: right 0.75rem center;
              background-size: 14px;
            }
            .anidex-sort:focus {
              outline: none;
            }
            .anidex-sort option {
              background-color: #1c1c1f;
              color: #e6e6e6;
              padding: 8px 12px;
            }
          `;
          document.head.appendChild(style);
        }

      }

      if (sortSelect) sortSelect.dataset.sortSelect = "1";

    }



    const stripYearFromMeta = (card) => {

      const p = card.querySelector("p");

      if (p) {

        const txt = p.textContent || "";

        const cleaned = txt.replace(/\b(19|20)\d{2}\b/g, "").replace(/\s{2,}/g, " ").trim();

        if (cleaned !== txt) p.textContent = cleaned;

      }

      card.querySelectorAll("[data-card-year]").forEach((el) => el.remove());

    };

    if (hideCardYears) {

      cards.forEach(stripYearFromMeta);

    } else {

      cards.forEach((card) => {

        const p = card.querySelector("p");

        const y = card.dataset.year || "";

        if (!p || !y) return;

        if (card.querySelector("[data-card-year]")) return;

        const yearEl = document.createElement("span");

        yearEl.dataset.cardYear = "1";

        yearEl.className = "block text-xs text-on-surface-variant/80 mt-0.5";

        yearEl.textContent = y;

        p.insertAdjacentElement("afterend", yearEl);

      });

    }



    function ensureEmptyBox() {

      if (emptyBox) return;

      const existing = document.querySelector("[data-state='empty']");

      if (existing) {

        emptyBox = existing;

        const host = existing.querySelector(".rounded-lg") || existing;

        if (host && !host.querySelector("[data-empty-gif]")) {

          const img = document.createElement("img");

          img.src = "https://media.giphy.com/media/52OAVA0xaq5hd8HbfY/giphy.gif";

          img.alt = "Doraemon triste";

          img.dataset.emptyGif = "1";

          img.className = "mx-auto mb-4 h-36 w-36 object-cover rounded-lg";

          const anchor = host.querySelector("h2,h3") || host.firstChild;

          host.insertBefore(img, anchor);

        }

        return;

      }

      if (!grid) return;

      emptyBox = document.createElement("div");

      emptyBox.className = "hidden col-span-full rounded-xl border border-outline/30 bg-surface-container-low p-8 text-center";

      const emptyTitle = "Sin resultados";

      const emptyBody = "Prueba otros filtros para ver m\u00e1s t\u00edtulos disponibles.";

      emptyBox.innerHTML = `

        <img src="https://media.giphy.com/media/52OAVA0xaq5hd8HbfY/giphy.gif" alt="Doraemon triste" class="mx-auto mb-4 h-36 w-36 object-cover rounded-lg" />

        <h3 class="text-xl font-bold text-on-surface">${emptyTitle}</h3>

        <p class="mt-2 text-sm text-on-surface-variant">${emptyBody}</p>

      `;

      grid.appendChild(emptyBox);

    }



    function updateGenreButtonLabel() {
      const label = genreDropdown.button.querySelector("[data-genre-label]");
      if (!label) return;
      if (state.genres.size === 0) {
        label.textContent = "Todos";
      } else {
        label.textContent = Array.from(state.genres)[0];
      }
    }

    function renderGenreDropdown(filterText = "") {
      const q = normalize(filterText);
      genreDropdown.list.innerHTML = "";
      const list = ["Todos", ...GENRE_OPTIONS];
      list.forEach((name) => {
        if (q && !normalize(name).includes(q)) return;
        const isAll = name === "Todos";
        const active = isAll ? state.genres.size === 0 : state.genres.has(name);
        const row = document.createElement("button");
        row.type = "button";
        row.className =
          "w-full flex items-center justify-start gap-3 rounded-md px-3 py-2 text-sm transition-colors whitespace-nowrap text-left " +
          (active
            ? "bg-primary/15 text-on-surface"
            : "text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest/60");
        row.innerHTML = `<span>${name}</span>`;
        row.addEventListener("click", () => {
          if (isAll) {
            state.genres.clear();
          } else {
            state.genres.clear();
            state.genres.add(name);
          }
          updateGenreButtonLabel();
          renderGenreDropdown(genreDropdown.search.value || "");
          applyFilters();
        });
        genreDropdown.list.appendChild(row);
      });
    }

    const closeGenreDropdown = () => {
      genreDropdown.panel.hidden = true;
      genreDropdown.button.setAttribute("aria-expanded", "false");
      genreDropdown.search.value = "";
      renderGenreDropdown("");
    };

    const openGenreDropdown = () => {
      genreDropdown.panel.hidden = false;
      genreDropdown.button.setAttribute("aria-expanded", "true");
      genreDropdown.search.focus();
    };

    const closeAllDropdowns = () => {
      closeGenreDropdown();
      closeYearDropdown(yearFrom, "from");
      closeYearDropdown(yearTo, "to");
    };

    genreDropdown.button.addEventListener("click", (e) => {
      e.stopPropagation();
      if (!genreDropdown.panel.hidden) {
        closeAllDropdowns();
        return;
      }
      closeAllDropdowns();
      openGenreDropdown();
    });

    genreDropdown.search.addEventListener("input", () => {
      renderGenreDropdown(genreDropdown.search.value || "");
    });

    window.__aniGenreDropdownWrap = genreDropdown.wrap;
    window.__aniGenreDropdownClose = closeGenreDropdown;
    if (!window.__aniGenreDropdownDocBound) {
      document.addEventListener("click", (e) => {
        const wrap = window.__aniGenreDropdownWrap;
        if (wrap && !wrap.contains(e.target)) window.__aniGenreDropdownClose?.();
      });
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") window.__aniGenreDropdownClose?.();
      });
      window.__aniGenreDropdownDocBound = true;
    }


    let lastYearChange = null;

    function updateYearButtonLabel(dropdown, value, fallback) {
      const label = dropdown.button.querySelector("[data-year-label]");
      if (!label) return;
      label.textContent = value ? String(value) : fallback;
    }

    function applyYearSelection(kind, value) {
      if (kind === "from") {
        state.yearFrom = value;
        lastYearChange = "from";
      } else {
        state.yearTo = value;
        lastYearChange = "to";
      }
      if (typeof state.yearFrom === "number" && typeof state.yearTo === "number" && state.yearFrom > state.yearTo) {
        if (lastYearChange === "from") state.yearTo = state.yearFrom;
        else state.yearFrom = state.yearTo;
      }
      updateYearButtonLabel(yearFrom, state.yearFrom, "Desde");
      updateYearButtonLabel(yearTo, state.yearTo, "Hasta");
    }

    function renderYearDropdown(dropdown, value, kind, filterText = "") {
      const q = normalize(filterText).replace(/[^\d]/g, "");
      dropdown.list.innerHTML = "";
      const restYears = allYears.slice(4);
      const baseList = ["Todos", ...recentYears, ...restYears];
      baseList.forEach((year) => {
        const label = String(year);
        if (q && !label.includes(q)) return;
        const isAll = label === "Todos";
        const active = isAll ? value === null : Number(label) === value;
        const row = document.createElement("button");
        row.type = "button";
        row.className =
          "w-full flex items-center justify-start gap-3 rounded-md px-3 py-2 text-sm transition-colors whitespace-nowrap text-left " +
          (active
            ? "bg-primary/15 text-on-surface"
            : "text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest/60");
        row.innerHTML = `<span>${label}</span>`;
        row.addEventListener("click", () => {
          if (isAll) applyYearSelection(kind, null);
          else applyYearSelection(kind, Number(label));
          renderYearDropdown(dropdown, kind === "from" ? state.yearFrom : state.yearTo, kind, dropdown.search.value || "");
          applyFilters();
          closeYearDropdown(dropdown, kind);
        });
        dropdown.list.appendChild(row);
      });
    }

    const closeYearDropdown = (dropdown, kind) => {
      dropdown.panel.hidden = true;
      dropdown.button.setAttribute("aria-expanded", "false");
      dropdown.search.value = "";
      renderYearDropdown(dropdown, kind === "from" ? state.yearFrom : state.yearTo, kind, "");
    };

    const openYearDropdown = (dropdown) => {
      dropdown.panel.hidden = false;
      dropdown.button.setAttribute("aria-expanded", "true");
      dropdown.search.focus();
    };

    yearFrom.button.addEventListener("click", (e) => {
      e.stopPropagation();
      if (!yearFrom.panel.hidden) {
        closeAllDropdowns();
        return;
      }
      closeAllDropdowns();
      openYearDropdown(yearFrom);
    });
    yearTo.button.addEventListener("click", (e) => {
      e.stopPropagation();
      if (!yearTo.panel.hidden) {
        closeAllDropdowns();
        return;
      }
      closeAllDropdowns();
      openYearDropdown(yearTo);
    });

    yearFrom.search.addEventListener("input", () => {
      renderYearDropdown(yearFrom, state.yearFrom, "from", yearFrom.search.value || "");
    });
    yearTo.search.addEventListener("input", () => {
      renderYearDropdown(yearTo, state.yearTo, "to", yearTo.search.value || "");
    });

    if (filterPanel && !filterPanel.dataset.dropdownLeaveBound) {
      filterPanel.addEventListener("mouseleave", () => {
        closeAllDropdowns();
      });
      filterPanel.dataset.dropdownLeaveBound = "1";
    }
    if (filterPanel) {
      window.__aniFilterPanel = filterPanel;
      window.__aniCloseAllDropdowns = closeAllDropdowns;
    }
    if (filterPanel && !window.__aniFilterMouseTrack) {
      document.addEventListener("mousemove", (e) => {
        const panel = window.__aniFilterPanel;
        const close = window.__aniCloseAllDropdowns;
        if (!panel || !close) return;
        const el = document.elementFromPoint(e.clientX, e.clientY);
        if (!el) return;
        if (!panel.contains(el) && !panel.matches(":hover")) close();
      });
      window.__aniFilterMouseTrack = true;
    }

    if (!window.__aniYearDropdownDocBound) {
      document.addEventListener("click", (e) => {
        if (yearFrom.wrap && !yearFrom.wrap.contains(e.target)) closeYearDropdown(yearFrom, "from");
        if (yearTo.wrap && !yearTo.wrap.contains(e.target)) closeYearDropdown(yearTo, "to");
      });
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          closeYearDropdown(yearFrom, "from");
          closeYearDropdown(yearTo, "to");
        }
      });
      window.__aniYearDropdownDocBound = true;
    }



    function renderSimple(wrap, options, key, single = false) {

      wrap.innerHTML = "";

      options.forEach((name) => {

        const set = state[key];

        const active = name === "Todos" ? set.size === 0 : set.has(name);

        const btn = gridChip(name, active, () => {
          if (name === "Todos") {
            set.clear();
          } else if (single) {
            set.clear();
            set.add(name);
          } else if (set.has(name)) {
            set.delete(name);
          } else {
            set.add(name);
          }
          renderSimple(wrap, options, key, single);
          applyFilters();
        });
        wrap.appendChild(btn);

      });

    }



    function getPopularity(card) {

      const score = Number(card.dataset.score || "0");

      if (score) return score;

      const chipScore = Number((card.querySelector(".fa-star")?.parentElement?.textContent || "").replace(/[^\d.]/g, ""));

      return Number.isFinite(chipScore) ? chipScore : 0;

    }



    function syncCardYears(cardsList) {

      if (hideCardYears) {

        cardsList.forEach(stripYearFromMeta);

        return;

      }

      cardsList.forEach((card) => {

        const y = card.dataset.year || card.dataset.yearOriginal || "";

        const yearEls = Array.from(card.querySelectorAll("[data-card-year]"));

        if (yearEls.length > 1) yearEls.slice(1).forEach((el) => el.remove());

        const yearEl = yearEls[0] || null;

        if (!y) {

          if (yearEl) yearEl.remove();

          return;

        }

        const p = card.querySelector("p");

        if (p) {

          const txt = p.textContent || "";

          const cleaned = txt.replace(/\b(19|20)\d{2}\b/g, "").replace(/\s{2,}/g, " ").trim();

          if (cleaned !== txt) p.textContent = cleaned;

        }

        let out = yearEl;

        if (!out && p) {

          out = document.createElement("span");

          out.dataset.cardYear = "1";

          out.className = "block text-xs text-on-surface-variant/80 mt-0.5";

          p.insertAdjacentElement("afterend", out);

        }

        if (out) out.textContent = String(y);

      });

    }



    function applyFilters() {

      const currentCards = gatherCards();

      syncCardYears(currentCards);

      const q = normalize(state.search);

      currentCards.forEach((card) => {

        const title = normalize(card.dataset.title);

        const genres = readGenresFromCard(card)
          .map((g) => canonicalGenre(g))
          .filter(Boolean);
        const selectedGenres = Array.from(state.genres)
          .map((g) => canonicalGenre(g))
          .filter(Boolean);
        const selectedUnique = Array.from(new Set(selectedGenres));
        if (!card.dataset.year) {

          const yNode = card.querySelector("[data-card-year]");

          const yMatch = yNode?.textContent?.match(/(19|20)\d{2}/);

          if (yMatch) card.dataset.year = yMatch[0];

        }

        const year = Number(card.dataset.year || 0);

        const type = canonicalType(card.dataset.type || "");

        const status = canonicalStatus(card.dataset.status || "");



        const matchText = !q || title.includes(q);

        const matchGenres = selectedUnique.length === 0 || selectedUnique.every((g) => genres.includes(g));
        const hasFrom = typeof state.yearFrom === "number";
        const hasTo = typeof state.yearTo === "number";
        const matchYear =
          (!hasFrom && !hasTo) ||
          (!hasFrom && hasTo && year > 0 && year <= state.yearTo) ||
          (hasFrom && !hasTo && year >= state.yearFrom) ||
          (hasFrom && hasTo && year >= state.yearFrom && year <= state.yearTo);

        const matchType = state.types.size === 0 || state.types.has(type);

        const matchStatus = state.statuses.size === 0 || state.statuses.has(status);



        card.style.display = matchText && matchGenres && matchYear && matchType && matchStatus ? "" : "none";

      });



      const visible = currentCards.filter((c) => c.style.display !== "none");

      if (window.__aniSuppressSortOnce) {
        window.__aniSuppressSortOnce = false;
      } else {
        sortAndRepaint(visible);
      }

      updateHeaderCount(visible.length);

      toggleEmptyState(visible.length);

      // METODOS DE SKELETON
      function ensureGridSkeletons(count) {
        const host = grid || document.querySelector("[data-anime-card]")?.parentElement;
        if (!host) return;
        host.querySelectorAll(".skeleton-loader").forEach(e => e.remove());
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
          host.appendChild(skel);
        }
      }

      function clearGridSkeletons() {
        const host = grid || document.querySelector("[data-anime-card]")?.parentElement;
        if (host) host.querySelectorAll(".skeleton-loader").forEach(e => e.remove());
      }

      // FALLBACK: Si no hay resultados locales y hay texto de búsqueda, buscar en Jikan
      if (visible.length === 0 && q && q.length > 2) {
        if (!state.isFetchingGlobal) {
          state.isFetchingGlobal = true;
          const isMoviesPage = window.location.pathname.toLowerCase().includes("peliculas");
          const mediaType = isMoviesPage ? "movie" : "tv";
          
          if (emptyBox) {
            emptyBox.style.display = "none";
          }
          
          ensureGridSkeletons(6); // Mostrar skeletons de busqueda

          fetch(`${appUrl("api/jikan_proxy")}?endpoint=${encodeURIComponent(`anime?q=${encodeURIComponent(state.search)}&type=${mediaType}&limit=12&order_by=popularity&sort=asc&sfw=1`)}`)
            .then(r => r.ok ? r.json() : null)
            .then(json => {
              state.isFetchingGlobal = false;
              clearGridSkeletons();
              if (json && json.data && json.data.length > 0) {
                hydrateCardsWithResults(json.data, mediaType);
              } else {
                 if (emptyBox) {
                   emptyBox.style.display = "";
                   if (emptyBox.querySelector("p")) {
                     emptyBox.querySelector("p").textContent = "No se encontraron resultados en el cat\u00e1logo global.";
                   }
                 }
              }
            })
            .catch(() => {
              state.isFetchingGlobal = false;
              clearGridSkeletons();
              if (emptyBox) emptyBox.style.display = "";
            });
        }
      } else {
         clearGridSkeletons();
      }

      const filtersActive =

        state.search ||

        state.genres.size > 0 ||

        typeof state.yearFrom === "number" ||
        typeof state.yearTo === "number" ||

        state.types.size > 0 ||

        state.statuses.size > 0;

      if (window.AniDexLoadMore) {

        if (filtersActive) window.AniDexLoadMore.hide();

        else window.AniDexLoadMore.show();

      }

    }



    function canonicalType(v) {
      const n = normalize(v);
      if (n.includes("pelicula original") || n.includes("original movie")) return "Pel\u00edcula original";
      if (n.includes("basada en serie") || n.includes("based on series")) return "Basada en serie";
      if (n.includes("recopil")) return "Recopilatoria";
      if (n.includes("secuela") || n.includes("sequel")) return "Secuela";
      if (n.includes("precuela") || n.includes("prequel")) return "Precuela";
      if (n.includes("spin off") || n.includes("spinoff")) return "Spin-off";
      if (["pelicula", "movie", "film"].includes(n)) return "Pel\u00edcula original";
      if (n.includes("especial") || n === "special") return "Especiales";
      if (n.includes("corto") || n === "short") return "Cortos";
      if (n === "ona") return "ONA";
      if (n === "ova") return "OVA";
      if (n === "tv" || n === "anime" || n === "serie" || n.includes("serie de tv")) return "Serie de TV";
      return v || "Serie de TV";
    }



    function canonicalStatus(v) {
      const n = normalize(v);
      if (n.includes("emision") || n.includes("airing")) return "En emisi\u00f3n";
      if (n.includes("upcoming") || n.includes("proxim")) return "Pr\u00f3ximamente";
      if (n.includes("finalizada") || n.includes("finalizado") || n.includes("final") || n.includes("finished")) return "Finalizado";
      if (n.includes("cancelada") || n.includes("cancelado") || n.includes("cancel")) return "Cancelado";
      if (n.includes("hiatus") || n.includes("paus")) return "Pausado";
      if (n.includes("cartelera") || n.includes("theater") || n.includes("cinema")) return "En cartelera";
      if (n.includes("retras")) return "Retrasado";
      return v || "Finalizado";
    }
    function hydrateCardsWithResults(items, mediaType) {
      const grid = document.querySelector("section[aria-label='Grid de anime']");
      if (!grid || !items || !items.length) return;

      const cards = gatherCards();
      cards.forEach(c => c.style.display = "none");

      items.forEach((item, idx) => {
        let card = cards[idx];
        const isNew = !card;

        const title = item.title_english || item.title || "Anime";
      const normalizedTitle = normalize(title);
      const blacklist = ["hentai", "futanari", "dick", "pussy", "sex", "porn", "cock", "blowjob", "does it count if"];
      if (blacklist.some(word => normalizedTitle.includes(word))) return null;
        const img = item.images?.webp?.large_image_url || item.images?.jpg?.large_image_url || "";
        const year = item.year || (item.aired?.prop?.from?.year) || "";
        const score = item.score || item.scored || 0;
        const genres = (item.genres || []).map(g => g.name || "").join(", ");

        if (isNew) {
          card = document.createElement("article");
          card.className = "group rounded-lg bg-surface-container-low p-4 transition-transform duration-300 ease-snappy hover:scale-[1.02]";
          card.dataset.animeCard = "1";
          card.innerHTML = `
            <a class="block" href="#" aria-label="">
              <div class="relative aspect-[2/3] overflow-hidden rounded-lg bg-surface-container-high">
                <img alt="" class="h-full w-full object-cover transition-transform duration-500 ease-snappy group-hover:scale-[1.03]" src="" loading="lazy"/>
                <span data-card-year="1" class="absolute left-3 top-3 rounded-full bg-surface/90 px-4 py-1.5 text-sm font-bold text-on-surface shadow-lg"></span>
                <span class="anidex-score-badge absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs gap-1 font-bold text-primary flex items-center shadow-lg">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span>--</span>
                </span>
              </div>
              <div class="space-y-2 mt-2">
                <h3 class="font-headline text-lg font-bold text-on-surface truncate"></h3>
              </div>
            </a>
          `;
          grid.appendChild(card);
        }

        card.dataset.title = normalize(title);
        card.dataset.genres = genres;
        card.dataset.year = String(year);
        card.dataset.type = canonicalType(item.type || card.dataset.type || "");
        card.dataset.status = canonicalStatus(item.status || card.dataset.status || "");
        card.setAttribute("data-mal-id", String(item.mal_id || card.getAttribute("data-mal-id") || ""));
        card.style.display = "";

        const titleEl = card.querySelector("h3,h4,h5");
        if (titleEl) titleEl.textContent = title;

        const imgEl = card.querySelector("img");
        if (imgEl) imgEl.src = img;

        const yearEl = card.querySelector("[data-card-year]") || card.querySelector("span.absolute");
        if (yearEl) {
          yearEl.textContent = String(year);
          yearEl.style.display = year ? "" : "none";
        }

        const scoreEl = card.querySelector(".anidex-score-badge span:last-child");
        if (scoreEl) scoreEl.textContent = score ? Number(score).toFixed(1) : "--";

        const link = card.closest("a") || card.querySelector("a") || card.querySelector("a");
        if (link) {
          link.href = buildDetailUrl(item.mal_id, title);
          link.ariaLabel = title;
        }
      });

      toggleEmptyState(items.length);
      updateHeaderCount(items.length);

      // Re-apply styles needed for movies page if applicable
      const isMoviesPage = window.location.pathname.toLowerCase().includes("peliculas");
      if (isMoviesPage) {
        items.forEach((item, idx) => {
           let card = cards[idx] || grid.children[grid.children.length - items.length + idx];
           const badge = card.querySelector("span.absolute.left-3");
           if (badge) {
             badge.classList.remove("top-3");
              badge.classList.add("bottom-3", "px-4", "py-1.5", "text-sm", "bg-surface/90", "shadow-lg");
           }
           const scoreBadge = card.querySelector(".anidex-score-badge");
           if (scoreBadge) {
             scoreBadge.classList.replace("px-2", "px-2.5");
             scoreBadge.classList.replace("py-1", "py-1.5");
             scoreBadge.classList.replace("rounded", "rounded-lg");
             scoreBadge.classList.add("text-sm", "gap-1.5");
             const star = scoreBadge.querySelector(".material-symbols-outlined");
             if (star) star.style.fontSize = "14px";
           }
        });
      }
    }


    function sortAndRepaint(visible) {

      const host = grid || document.querySelector("[data-anime-card]")?.parentElement;

      if (!host) return;

      const sorted = [...visible];
      const allCards = gatherCards();
      const hiddenCards = allCards.filter((card) => card.style.display === "none");
      const sortableYear = (card, direction) => {
        const year = Number(card.dataset.year || card.dataset.yearOriginal || 0);
        if (Number.isFinite(year) && year > 0) return year;
        return direction === "asc" ? Number.MAX_SAFE_INTEGER : Number.MIN_SAFE_INTEGER;
      };

      switch (state.sort) {

        case "year_desc": sorted.sort((a, b) => sortableYear(b, "desc") - sortableYear(a, "desc")); break;

        case "year_asc": sorted.sort((a, b) => sortableYear(a, "asc") - sortableYear(b, "asc")); break;

        case "title_asc": sorted.sort((a, b) => normalize(a.dataset.title).localeCompare(normalize(b.dataset.title))); break;

        case "title_desc": sorted.sort((a, b) => normalize(b.dataset.title).localeCompare(normalize(a.dataset.title))); break;

        default:
          sorted.sort((a, b) => {
            const pa = getPopularity(a);
            const pb = getPopularity(b);
            if (pb !== pa) return pb - pa;
            const oa = Number(a.dataset.originalOrder || 0);
            const ob = Number(b.dataset.originalOrder || 0);
            return oa - ob;
          });
          break;

      }

      [...sorted, ...hiddenCards].forEach((c) => host.appendChild(c));

    }



    function toggleEmptyState(count) {

      ensureEmptyBox();

      if (!emptyBox) return;

      emptyBox.classList.toggle("hidden", count > 0);

      emptyBox.style.display = count > 0 ? "none" : "";

    }



    function updateHeaderCount(count) {

      const p = document.querySelector("header p");

      if (!p) return;

      p.textContent = `Mostrando ${count} t\u00edtulos seleccionados para ti`;

    }



    searchInput.addEventListener("input", () => {

      state.search = searchInput.value || "";

      applyFilters();

    });



    if (sortSelect) {

      state.sort = sortSelect.value || state.sort;

      sortSelect.addEventListener("change", () => {

        state.sort = sortSelect.value;

        applyFilters();

      });

    }

    if (!window.__aniSortDelegate) {

      document.addEventListener("change", (e) => {

        const t = e.target;

        if (t && t.tagName === "SELECT" && t.dataset && t.dataset.sortSelect === "1") {

          if (window.__aniSortHandler) window.__aniSortHandler(t.value);

        }

      });

      window.__aniSortDelegate = true;

    }

    window.__aniSortHandler = (value) => {

      state.sort = value;

      applyFilters();

    };



    renderGenreDropdown();
    updateGenreButtonLabel();
    if (genreHost && !genreHost.contains(genreDropdown.wrap)) genreHost.appendChild(genreDropdown.wrap);
    updateYearButtonLabel(yearFrom, state.yearFrom, "Desde");
    updateYearButtonLabel(yearTo, state.yearTo, "Hasta");
    renderYearDropdown(yearFrom, state.yearFrom, "from");
    renderYearDropdown(yearTo, state.yearTo, "to");

    if (!isMoviesPage) {
      const typeList = ["Todos", "Serie de TV", "OVA", "ONA", "Especiales", "Cortos"];
      renderSimple(typeWrap, typeList, "types", true);

      const statusList = STATUS_OPTIONS_SERIES;
      renderSimple(statusWrap, statusList, "statuses", true);
    } else {
      const typeList = ["Todos", "Serie de TV", "OVA", "ONA", "Especiales", "Cortos"];
      renderSimple(typeWrap, typeList, "types", true);

      const statusList = STATUS_OPTIONS_SERIES;
      if (statusLabel?.parentElement) {
        statusLabel.parentElement.querySelectorAll(".anidex-status-wrap").forEach((n) => n.remove());
        statusLabel.parentElement.appendChild(statusWrap);
      }
      renderSimple(statusWrap, statusList, "statuses", true);
    }
    applyFilters();
    renderSidebarRanking();
    if (window.AniDexFilters) window.AniDexFilters.apply = applyFilters;

  }



  window.AniDexFilters = {
    init: setup
  };

  function renderSidebarRanking() {
    const host = document.querySelector("[data-sidebar-ranking]");
    if (!host || window.__aniSidebarRankingLoaded) return;
    window.__aniSidebarRankingLoaded = true;

    if (!document.getElementById("anidex-sidebar-ranking-style")) {
      const style = document.createElement("style");
      style.id = "anidex-sidebar-ranking-style";
      style.textContent = `
        .anidex-rank-top {
          background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(56, 189, 248, 0.12)) !important;
          border: 1px solid rgba(168, 85, 247, 0.35);
          box-shadow: 0 10px 24px rgba(0,0,0,0.25), 0 0 18px rgba(168, 85, 247, 0.18);
        }
        .anidex-rank-top:hover {
          box-shadow: 0 12px 28px rgba(0,0,0,0.3), 0 0 22px rgba(56, 189, 248, 0.22);
        }
      `;
      document.head.appendChild(style);
    }

    const type = host.dataset.rankingType || "anime";
    const jikanEndpoint = type === "movie" ? "top/anime?type=movie&limit=5" : "top/anime?type=tv&limit=5";
    const endpoint = `${appUrl("api/jikan_proxy")}?endpoint=${encodeURIComponent(jikanEndpoint)}`;

    host.innerHTML = `<div class="text-xs text-on-surface-variant">Cargando ranking...</div>`;

    const cacheKey = `anidex-ranking-${type}`;

    const renderItems = (items) => {
      if (!items.length) {
        host.innerHTML = `<div class="text-xs text-on-surface-variant">Sin resultados.</div>`;
        return;
      }
      host.innerHTML = "";
      items.slice(0, 5).forEach((item, idx) => {
        const title = item.title_english || item.title || "Anime";
        const img =
          (item.images && item.images.webp && item.images.webp.image_url) ||
          (item.images && item.images.jpg && item.images.jpg.image_url) ||
          "";
        const score = typeof item.score === "number" ? item.score.toFixed(1) : "--";
        const row = document.createElement("button");
        row.type = "button";
          row.className =
            "w-full flex items-center gap-3 rounded-lg bg-surface-container-high/60 px-3 py-2 text-left transition-colors hover:bg-surface-container-high" +
            (idx < 3 ? " anidex-rank-top" : "");
          row.innerHTML = `
            <span class="flex h-12 w-9 items-center justify-center overflow-hidden rounded-md bg-surface-container-high">
              ${img ? `<img src="${img}" alt="${title}" class="h-full w-full object-cover" loading="lazy" />` : ""}
            </span>
            <span class="flex-1 min-w-0">
              <span class="block text-sm font-semibold text-on-surface truncate">${title}</span>
              <span class="mt-0.5 flex items-center gap-2 text-[11px]">
                <span class="text-on-surface-variant">Puesto #${idx + 1}</span>
                <span class="inline-flex items-center gap-1 font-semibold text-amber-300">
                  <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  ${score}
                </span>
              </span>
            </span>
          `;
        row.addEventListener("click", () => {
          const q = encodeURIComponent(title);
          const id = item && item.mal_id ? encodeURIComponent(String(item.mal_id)) : "";
          window.location.href = id ? `detail?mal_id=${id}&q=${q}` : `detail?q=${q}`;
        });
        host.appendChild(row);
      });
    };

    const fetchRanking = (tries, delayMs) =>
      fetch(endpoint, { cache: "no-store" })
        .then((res) => (res && res.ok ? res.json() : null))
        .then((json) => {
          const items = json && Array.isArray(json.data) ? json.data : [];
          if (items.length) {
            try {
              localStorage.setItem(cacheKey, JSON.stringify({ ts: Date.now(), items }));
            } catch {}
          }
          return items;
        })
        .catch(() => []);

    const retry = (attempt) =>
      fetchRanking().then((items) => {
        if (items.length || attempt <= 0) return items;
        return new Promise((resolve) => setTimeout(resolve, delayMs))
          .then(() => retry(attempt - 1));
      });

    const delayMs = 600;
    retry(2).then((items) => {
      if (items.length) {
        renderItems(items);
        return;
      }
      try {
        const cached = JSON.parse(localStorage.getItem(cacheKey) || "{}");
        if (cached && Array.isArray(cached.items) && cached.items.length) {
          renderItems(cached.items);
          return;
        }
      } catch {}
      host.innerHTML = `<div class="text-xs text-on-surface-variant">Sin resultados.</div>`;
    });
  }

})();






