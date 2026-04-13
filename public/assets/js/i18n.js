(() => {
  const buildAssetPath = (path) => {
    const sharedBuilder = window.AniDexShared?.buildAppUrl;
    if (typeof sharedBuilder === "function") return sharedBuilder(path);
    const pathname = window.location.pathname.replace(/\\/g, "/");
    const publicIndex = pathname.toLowerCase().indexOf("/public/");
    let basePath = "/";
    if (publicIndex >= 0) {
        basePath = pathname.slice(0, publicIndex + 8);
    } else {
        const parts = pathname.split('/');
        if (parts.length > 1) {
            basePath = "/" + parts[1] + "/";
        }
    }
    const cleanPath = String(path || "").replace(/^\/+/, "");
    return cleanPath ? `${basePath}${cleanPath}` : basePath.replace(/\/$/, "");
  };

  const STORAGE_KEY = "anidex_lang";
  const CACHE_KEY = "anidex_en_cache_v1";
  const LANGS = {
    es: { src: buildAssetPath("img/espana.webp"), alt: "ES" },
    en: { src: buildAssetPath("img/reino-unido.webp"), alt: "EN" }
  };

  const fixedMap = new Map([
    ["AniDex", "NekoraList"],
    ["ES", "ES"],
    ["EN", "EN"],
    ["search", "search"],
    ["favorite", "favorite"],
    ["expand_more", "expand_more"],
    ["play_arrow", "play_arrow"],
    ["play_circle", "play_circle"],
    ["bookmark", "bookmark"],
    ["open_in_new", "open_in_new"],
    ["edit", "edit"],
    ["arrow_forward", "arrow_forward"],
    ["add", "add"]
  ]);

  const forcedMap = new Map([
    ["ani", "Ani"],
    ["dex", "Dex"],
    ["anidex", "NekoraList"],
    ["inicio", "Home"],
    ["series", "Animes"],
    ["animes", "Animes"],
    ["populares", "Popular"],
    ["popularidad", "Popularity"],
    ["películas", "Movies"],
    ["pel\u00edculas", "Movies"],
    ["fantasy", "Fantasy"],
    ["fancy", "Fantasy"],
    ["pel\u00edculas populares", "Popular Movies"],
    ["destacados de la temporada", "Season Highlights"],
    ["estreno invierno 2024", "Winter 2024 Premiere"],
    ["ver ahora", "Watch Now"],
    ["mi lista", "My List"],
    ["buscar...", "Search..."],
    ["favoritos", "Favorites"],
    ["politica de privacidad", "Privacy Policy"],
    ["terminos de servicio", "Terms of Service"],
    ["documentacion api", "API Documentation"]
    ,["estudio", "Studio"]
    ,["estudio:", "Studio:"]
    ,["studio", "Studio"]
    ,["studio:", "Studio:"]
  ]);

  const textNodes = [];
  const attrNodes = [];
  const cache = loadCache();

  function loadCache() {
    try {
      const parsed = JSON.parse(localStorage.getItem(CACHE_KEY) || "{}");
      if (parsed.fantasia && String(parsed.fantasia).toLowerCase() === "fancy") {
        parsed.fantasia = "Fantasy";
      }
      if (parsed.fancy) {
        parsed.fancy = "Fantasy";
      }
      return parsed;
    } catch {
      return {};
    }
  }

  function saveCache() {
    try {
      localStorage.setItem(CACHE_KEY, JSON.stringify(cache));
    } catch {
      // ignore quota errors
    }
  }

  function normalize(text) {
    return (text || "")
      .replaceAll("\u00C3\u00A1", "")
      .replaceAll("\u00C3\u00A9", "")
      .replaceAll("\u00C3\u00AD", "")
      .replaceAll("\u00C3\u00B3", "")
      .replaceAll("\u00C3\u00BA", "")
      .replaceAll("\u00C3\u00B1", "")
      .replaceAll("\u00C3", "")
      .replaceAll("", "")
      .replaceAll("", "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();
  }

  function isIconNode(el) {
    return !!(el && el.classList && el.classList.contains("material-symbols-outlined"));
  }

  function shouldSkip(raw) {
    const t = (raw || "").trim();
    if (!t) return true;
    if (fixedMap.has(t)) return true;
    if (/^[0-9.,:+\-/%\s]+$/.test(t)) return true;
    if (t.length < 2) return true;
    return false;
  }

  function collectNodes(root) {
    textNodes.length = 0;
    attrNodes.length = 0;
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
    let node;
    while ((node = walker.nextNode())) {
      const parent = node.parentElement;
      if (!parent) continue;
      if (parent.tagName === "SCRIPT" || parent.tagName === "STYLE") continue;
      if (isIconNode(parent)) continue;
      if (typeof node.__anidex_es === "undefined") {
        node.__anidex_es = node.textContent;
      }
      textNodes.push({ node, es: node.__anidex_es });
    }

    root.querySelectorAll("*").forEach((el) => {
      if (el.tagName === "SCRIPT" || el.tagName === "STYLE") return;
      if (isIconNode(el)) return;
      ["placeholder", "aria-label", "title", "alt"].forEach((attr) => {
        if (!el.hasAttribute(attr)) return;
        const key = `__anidex_es_${attr}`;
        if (typeof el[key] === "undefined") {
          el[key] = el.getAttribute(attr) || "";
        }
        attrNodes.push({ el, attr, es: el[key] || "" });
      });
    });
  }

  async function translateEsToEn(raw) {
    const source = (raw || "").trim();
    if (shouldSkip(source)) return raw;

    const key = normalize(source).toLowerCase();
    if (forcedMap.has(key)) return raw.replace(source, forcedMap.get(key));
    if (cache[key]) return raw.replace(source, cache[key]);

    try {
      const url =
        "https://translate.googleapis.com/translate_a/single?client=gtx&sl=es&tl=en&dt=t&q=" +
        encodeURIComponent(source);
      const res = await fetch(url);
      if (!res.ok) return raw;
      const data = await res.json();
      const translated = (data?.[0] || []).map((row) => row?.[0] || "").join("").trim();
      if (!translated) return raw;
      const safe = translated.toLowerCase() === "fancy" ? "Fantasy" : translated;
      cache[key] = safe;
      return raw.replace(source, safe);
    } catch {
      return raw;
    }
  }

  async function applyEnglish() {
    const unique = new Map();
    [...textNodes, ...attrNodes].forEach((entry) => {
      const es = entry.es || "";
      const trimmed = es.trim();
      if (!shouldSkip(trimmed)) unique.set(trimmed, true);
    });

    const translations = {};
    for (const esText of unique.keys()) {
      const translated = await translateEsToEn(esText);
      translations[esText] = translated.trim() || esText;
    }
    saveCache();

    textNodes.forEach((entry) => {
      const trimmed = (entry.es || "").trim();
      if (!trimmed || !translations[trimmed]) {
        entry.node.textContent = entry.es;
        return;
      }
      entry.node.textContent = entry.es.replace(trimmed, translations[trimmed]);
    });

    attrNodes.forEach((entry) => {
      const trimmed = (entry.es || "").trim();
      if (!trimmed || !translations[trimmed]) {
        entry.el.setAttribute(entry.attr, entry.es);
        return;
      }
      entry.el.setAttribute(entry.attr, entry.es.replace(trimmed, translations[trimmed]));
    });
  }

  function applySpanish() {
    textNodes.forEach((entry) => {
      entry.node.textContent = entry.es;
    });
    attrNodes.forEach((entry) => {
      entry.el.setAttribute(entry.attr, entry.es);
    });
  }

  function applyLanguage(lang) {
    const selected = LANGS[lang] ? lang : "es";
    if (selected === "es") applySpanish();
    if (selected === "en") {
      textNodes.forEach((entry) => {
        const trimmed = (entry.es || "").trim();
        if (!trimmed) {
          entry.node.textContent = entry.es;
          return;
        }
        const key = normalize(trimmed).toLowerCase();
        if (forcedMap.has(key)) {
          entry.node.textContent = entry.es.replace(trimmed, forcedMap.get(key));
        } else if (cache[key]) {
          entry.node.textContent = entry.es.replace(trimmed, cache[key]);
        } else {
          entry.node.textContent = entry.es;
        }
      });
      attrNodes.forEach((entry) => {
        const trimmed = (entry.es || "").trim();
        if (!trimmed) {
          entry.el.setAttribute(entry.attr, entry.es);
          return;
        }
        const key = normalize(trimmed).toLowerCase();
        if (forcedMap.has(key)) {
          entry.el.setAttribute(entry.attr, entry.es.replace(trimmed, forcedMap.get(key)));
        } else if (cache[key]) {
          entry.el.setAttribute(entry.attr, entry.es.replace(trimmed, cache[key]));
        } else {
          entry.el.setAttribute(entry.attr, entry.es);
        }
      });
      setTimeout(() => {
  const basePath = window.location.pathname.includes('/views/') ? '../' : '';

        applyEnglish();
      }, 0);
    }
    document.documentElement.lang = selected;
    localStorage.setItem(STORAGE_KEY, selected);
  }

  let currentLang = "es";
  let isApplying = false;
  let pending = null;
  let observerBound = false;
  let langUiBound = false;

  const scheduleApply = () => {
    if (pending) return;
    pending = setTimeout(async () => {
      pending = null;
      if (isApplying) return;
      isApplying = true;
      collectNodes(document.body);
      await applyLanguage(currentLang);
      isApplying = false;
    }, 120);
  };

  function init() {
    const toggle = document.querySelector("[data-lang-toggle]");
    const menu = document.querySelector("[data-lang-menu]");

    collectNodes(document.body);
    const saved = localStorage.getItem(STORAGE_KEY) || "es";
    const current = LANGS[saved] ? saved : "es";
    currentLang = current;

    const icon = toggle ? toggle.querySelector("img") : null;
    const setFlag = (lang) => {
      if (!icon) return;
      icon.src = LANGS[lang].src;
      icon.alt = LANGS[lang].alt;
    };

    applyLanguage(currentLang);
    setFlag(current);

    if (!observerBound) {
      observerBound = true;
      const observer = new MutationObserver(() => {
        if (isApplying) return;
        scheduleApply();
      });
      observer.observe(document.body, {
        childList: true,
        subtree: true,
        characterData: true
      });

      // Re-scan after initial render scripts populate dynamic menu/items.
      setTimeout(scheduleApply, 250);
      setTimeout(scheduleApply, 900);
    }

    if (!toggle || !menu || langUiBound) return;
    langUiBound = true;

    toggle.addEventListener("click", () => {
      const isOpen = !menu.classList.contains("hidden");
      menu.classList.toggle("hidden", isOpen);
      toggle.setAttribute("aria-expanded", String(!isOpen));
    });

    menu.addEventListener("click", (e) => {
      const item = e.target.closest("[data-lang]");
      if (!item) return;
      const selected = LANGS[item.dataset.lang] ? item.dataset.lang : "es";
      currentLang = selected;
      scheduleApply();
      setFlag(selected);
      menu.classList.add("hidden");
      toggle.setAttribute("aria-expanded", "false");
    });

    document.addEventListener("click", (e) => {
      if (!toggle.parentElement.contains(e.target)) {
        menu.classList.add("hidden");
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  window.AniDexI18n = { init };
})();

