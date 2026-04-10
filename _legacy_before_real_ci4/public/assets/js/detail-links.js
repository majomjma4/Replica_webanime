(() => {
  const pickTitleFromCard = (el) => {
    if (!el) return "";
    const card = el.closest("[data-mal-id], [data-title], [data-anime-card], .group, article, a, button") || el.closest("div") || el;

    const dataTitle = card.getAttribute?.("data-title");
    if (dataTitle && dataTitle.length > 2) return dataTitle;

    const heading = card.querySelector?.("h1,h2,h3,h4,h5");
    if (heading?.textContent?.trim()) {
      const hText = heading.textContent.trim();
      if (!/^(Anime|Pelicula|Pelcula|Movie|TV|OVA|Special|Hoy)$/i.test(hText)) return hText;
    }

    const bolds = Array.from(card.querySelectorAll?.(".font-bold, .font-semibold, .font-headline") || []);
    const textual = bolds
      .map((n) => (n.textContent || "").trim())
      .find((t) => {
        if (!t || t.length <= 2) return false;
        if (/^(Anime|Pelicula|Pelcula|Movie|TV|OVA|Special|Finalizado|En emision|En emisin|Hoy|Favoritos)$/i.test(t)) return false;
        return /[a-zA-Z]/.test(t) && !/^\d+([.,]\d+)?$/.test(t);
      });

    return textual || "";
  };

  const buildUrl = (malId = "", title = "", dbId = "") =>
    window.AniDexShared?.buildDetailUrl
      ? window.AniDexShared.buildDetailUrl(malId, title, dbId)
      : (malId ? `detail/${encodeURIComponent(String(malId))}` : `detail/${encodeURIComponent(String(title || "anime").trim())}`);

  const parseDetailSource = (value) => {
    if (!value || !String(value).includes("detail")) return null;
    try {
      const url = new URL(value, window.location.origin);
      const routeMatch = url.pathname.match(/\/detail(?:\/([^/?#]+))?$/i);
      return {
        malId: url.searchParams.get("mal_id") || (routeMatch?.[1] && /^\d+$/.test(routeMatch[1]) ? routeMatch[1] : ""),
        dbId: url.searchParams.get("id") || "",
        q: url.searchParams.get("q") || (!routeMatch?.[1] || /^\d+$/.test(routeMatch[1]) ? "" : routeMatch[1].replace(/-/g, " "))
      };
    } catch {
      return null;
    }
  };

  const parseInlineOnclick = (node) => {
    const raw = node.getAttribute("onclick") || "";
    const malId = raw.match(/[?&]mal_id=([^&'"`]+)/i)?.[1] || "";
    const dbId = raw.match(/[?&]id=([^&'"`]+)/i)?.[1] || "";
    const q = raw.match(/[?&]q=([^&'"`]+)/i)?.[1] || "";
    return {
      malId: malId ? decodeURIComponent(malId) : "",
      dbId: dbId ? decodeURIComponent(dbId) : "",
      q: q ? decodeURIComponent(q) : ""
    };
  };

  const wireLinks = () => {
    document.querySelectorAll('a[href*="detail"]').forEach((a) => {
      const parsed = parseDetailSource(a.getAttribute("href") || a.href || "") || { malId: "", dbId: "", q: "" };
      const ownMalId = a.getAttribute("data-mal-id");
      const parentMalId = a.closest("[data-mal-id]")?.getAttribute("data-mal-id");
      const malId = ownMalId || parentMalId || parsed.malId;
      const title = parsed.q || pickTitleFromCard(a);
      a.href = buildUrl(malId, title, parsed.dbId);
    });

    document.querySelectorAll("[onclick*='detail']").forEach((node) => {
      const parsed = parseInlineOnclick(node);
      const malId = node.getAttribute("data-mal-id") || node.closest("[data-mal-id]")?.getAttribute("data-mal-id") || parsed.malId;
      const title = parsed.q || pickTitleFromCard(node);
      node.onclick = () => {
        window.location.href = buildUrl(malId, title, parsed.dbId);
      };
    });

    const isDetailPage = /\/detail(\/|$|\?)/i.test(window.location.pathname || "");
    if (!isDetailPage && !document.body.dataset.detailLiveBound) {
      document.body.dataset.detailLiveBound = "1";
      document.addEventListener(
        "click",
        (e) => {
          const ignoredTrigger = e.target.closest(
            "[data-open-list], [data-open-status], [data-lists-close], [data-status-close], " +
            "[data-scroll-left], [data-scroll-right], #premium-access-btn, #profile-settings-btn, " +
            "#request-title-btn, #profile-logout-btn, [data-profile-close], [data-request-close], " +
            "[data-avatar-close], [data-avatar-src], [data-logout-confirm], [data-logout-cancel]"
          );
          if (ignoredTrigger) return;

          const interactiveTarget = e.target.closest("button, input, textarea, select, label");
          if (interactiveTarget && !interactiveTarget.matches("[onclick*='detail']")) return;

          const target = e.target.closest("[data-mal-id], [onclick*='detail'], [data-anime-card]");
          if (!target) return;
          const anchor = e.target.closest('a[href*="detail"]');
          if (anchor) {
            return;
          }

          const cardAnchor = target.matches("a[href*='detail']")
            ? target
            : target.querySelector?.("a[href*='detail']") || target.closest("article, .group, [data-anime-card]")?.querySelector?.("a[href*='detail']");
          if (cardAnchor?.href) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = cardAnchor.href;
            return;
          }

          const malId = target.getAttribute("data-mal-id") || target.closest("[data-mal-id]")?.getAttribute("data-mal-id") || "";
          const title = pickTitleFromCard(target);
          if (!malId && !title) return;
          e.preventDefault();
          e.stopPropagation();
          window.location.href = buildUrl(malId, title);
        },
        true
      );
    }
  };

  window.AniDexDetailLinks = { init: wireLinks };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", wireLinks, { once: true });
  } else {
    wireLinks();
  }
})();
