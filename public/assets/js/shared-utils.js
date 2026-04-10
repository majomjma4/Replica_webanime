(() => {
  const getCookie = (name) => {
    const escaped = name.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const match = document.cookie.match(new RegExp(`(?:^|; )${escaped}=([^;]*)`));
    return match ? decodeURIComponent(match[1]) : "";
  };

  const isSameOriginUrl = (value) => {
    try {
      const url = new URL(value, window.location.href);
      return url.origin === window.location.origin;
    } catch {
      return false;
    }
  };

  const normalizeBasePath = (value) => {
    const normalized = String(value || "").replace(/\\/g, "/");
    if (!normalized || normalized === "/") return "/";
    return normalized.endsWith("/") ? normalized : `${normalized}/`;
  };

  const inferBasePathFromScript = () => {
    const scripts = Array.from(document.querySelectorAll("script[src]"));
    for (const script of scripts) {
      const src = script.getAttribute("src") || "";
      const match = src.match(/^(.*\/)assets\/js\/[^/]+$/i);
      if (match?.[1]) {
        return normalizeBasePath(match[1]);
      }
    }
    return "";
  };

  const getBasePath = () => {
    const metaBase = document.querySelector('meta[name="app-base-path"]')?.getAttribute("content") || "";
    if (metaBase) {
      return normalizeBasePath(metaBase);
    }

    const scriptBase = inferBasePathFromScript();
    if (scriptBase) {
      return scriptBase;
    }

    const path = window.location.pathname.replace(/\\/g, "/");
    const publicIndex = path.toLowerCase().indexOf("/public/");
    if (publicIndex >= 0) {
      return normalizeBasePath(path.slice(0, publicIndex + "/public/".length));
    }
    if (path.toLowerCase().endsWith("/public")) {
      return normalizeBasePath(path);
    }

    const routeMatch = path.match(/^(.*\/)(index|destacados|ranking|series|peliculas|registro|ingresar|admin|user|detail)(?:\/[^/?#]+)?\/?$/i);
    if (routeMatch?.[1]) {
      return normalizeBasePath(routeMatch[1]);
    }

    const apiMatch = path.match(/^(.*\/)api\/[^/?#]+\/?$/i);
    if (apiMatch?.[1]) {
      return normalizeBasePath(apiMatch[1]);
    }

    const partialsMatch = path.match(/^(.*\/)partials\/[^/?#]+\/?$/i);
    if (partialsMatch?.[1]) {
      return normalizeBasePath(partialsMatch[1]);
    }

    return normalizeBasePath(path);
  };

  const buildAppUrl = (path = "") => {
    const basePath = getBasePath();
    const cleanPath = String(path || "").replace(/^\/+/, "");
    return cleanPath ? `${basePath}${cleanPath}` : basePath.replace(/\/$/, "");
  };
  const slugify = (value) => {
    const normalized = String(value || "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-+|-+$/g, "");
    return normalized || "anime";
  };

  const buildDetailUrl = (malId = "", title = "", dbId = "") => {
    const basePath = getBasePath();
    const numericId = String(malId || dbId || "").trim();
    if (/^\d+$/.test(numericId)) {
      return `${basePath}detail/${encodeURIComponent(numericId)}`;
    }
    const cleanTitle = String(title || "").trim();
    if (cleanTitle) {
      return `${basePath}detail/${encodeURIComponent(slugify(cleanTitle))}`;
    }
    return `${basePath}detail`;
  };

  const getDetailRouteInfo = () => {
    const path = window.location.pathname.replace(/\/+/g, "/");
    const match = path.match(/\/detail(?:\/([^/?#]+))?$/i);
    const search = new URLSearchParams(window.location.search);
    const routeSegment = match?.[1] ? decodeURIComponent(match[1]) : "";
    const legacyId = search.get("mal_id") || search.get("id") || "";
    const legacyTitle = search.get("q") || "";
    const numericCandidate = routeSegment || legacyId;
    const malId = /^\d+$/.test(String(numericCandidate)) ? String(numericCandidate) : "";
    const slug = malId ? "" : routeSegment;
    const query = legacyTitle || (slug ? slug.replace(/-/g, " ") : "");
    return { routeSegment, malId, slug, query };
  };

  const withSecurityDefaults = (url, options = {}) => {
    const normalized = { ...options };
    const method = String(normalized.method || "GET").toUpperCase();
    const headers = new Headers(normalized.headers || {});

    if (!normalized.credentials && isSameOriginUrl(url)) {
      normalized.credentials = "same-origin";
    }

    if (!headers.has("X-Requested-With") && isSameOriginUrl(url)) {
      headers.set("X-Requested-With", "XMLHttpRequest");
    }

    if (!["GET", "HEAD", "OPTIONS"].includes(method) && isSameOriginUrl(url)) {
      const csrf = getCookie("XSRF-TOKEN");
      if (csrf && !headers.has("X-CSRF-Token")) {
        headers.set("X-CSRF-Token", csrf);
      }
    }

    normalized.headers = headers;
    return normalized;
  };

  const nativeFetch = window.fetch.bind(window);
  window.fetch = (input, init = {}) => {
    const url = typeof input === "string" || input instanceof URL ? String(input) : input?.url || window.location.href;
    const secureInit = withSecurityDefaults(url, init);
    return nativeFetch(input, secureInit);
  };

  const normalizeText = (value) =>
    String(value || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();

  const fetchJson = async (url, options = {}, retries = 1, retryDelayMs = 700) => {
    try {
      const secureOptions = withSecurityDefaults(url, options);
      let res = await nativeFetch(url, secureOptions);
      let remaining = retries;
      while (res && res.status === 429 && remaining > 0) {
        await new Promise((resolve) => setTimeout(resolve, retryDelayMs));
        res = await nativeFetch(url, secureOptions);
        remaining -= 1;
      }
      if (!res || !res.ok) return null;
      return await res.json();
    } catch {
      return null;
    }
  };

  const translateAutoToEs = async (text) => {
    const raw = String(text || "").trim();
    if (!raw) return "";
    const url =
      "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=es&dt=t&q=" +
      encodeURIComponent(raw);
    const data = await fetchJson(url, {}, 1, 700);
    const out = (data?.[0] || []).map((row) => row?.[0] || "").join("").trim();
    return out || raw;
  };

  const scoreTextMatch = (query, candidate) => {
    const q = normalizeText(query);
    const c = normalizeText(candidate);
    if (!q || !c) return 0;
    if (q === c) return 100;
    if (c.includes(q) || q.includes(c)) return 80;
    const qTokens = q.split(" ").filter(Boolean);
    const cTokens = c.split(" ").filter(Boolean);
    const overlap = qTokens.filter((token) => cTokens.includes(token)).length;
    if (!overlap) return 0;
    return Math.round((overlap / Math.max(qTokens.length, cTokens.length)) * 70);
  };

  const safeOpenExternal = (url, target = "_blank") => {
    const opened = window.open(url, target, "noopener,noreferrer");
    if (opened) {
      try {
        opened.opener = null;
      } catch {}
    }
    return opened;
  };

  document.addEventListener("click", (event) => {
    const trigger = event.target.closest("[data-external-url]");
    if (!trigger) return;

    event.preventDefault();
    const url = trigger.getAttribute("data-external-url");
    if (!url) return;
    safeOpenExternal(url);
  });

  window.AniDexShared = {
    buildAppUrl,
    buildDetailUrl,
    getBasePath,
    getCookie,
    getDetailRouteInfo,
    normalizeText,
    scoreTextMatch,
    slugify,
    safeOpenExternal,
    translateAutoToEs,
    withSecurityDefaults,
    fetchJson
  };
})();

