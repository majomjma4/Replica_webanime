(() => {
  const API = "api/jikan_proxy.php";
  const qp = new URLSearchParams(location.search);
  const animeId = qp.get("anime_id");
  const charId = qp.get("char_id");

  const init = async () => {
    if (!animeId || !charId) return;
    const res = await fetch(`${API}?endpoint=${encodeURIComponent('anime/' + animeId + '/characters')}`);
    if (!res.ok) return;
    const json = await res.json();
    const ch = (json?.data || []).find((x) => String(x?.character?.mal_id) === String(charId));
    if (!ch) return;
    const c = ch.character || {};
    const vas = ch.voice_actors || [];
    const jp = vas.find((v) => v.language === "Japanese")?.person?.name || "N/A";
    const en = vas.find((v) => v.language === "English")?.person?.name || "N/A";
    const es = vas.find((v) => /spanish/i.test(v.language || ""))?.person?.name || "N/A";

    const title = document.getElementById("char-title");
    const img = document.getElementById("char-image");
    const about = document.getElementById("char-about");
    const info = document.getElementById("char-info");
    if (title) title.textContent = c.name || "Personaje";
    if (img) img.src = c?.images?.jpg?.image_url || "";
    if (about) about.textContent = c.about || "Sin descripci\u00f3n disponible.";
    if (info) {
      info.innerHTML = `
        <p><strong>Doblaje japon\u00e9s:</strong> ${jp}</p>
        <p><strong>Doblaje ingl\u00e9s:</strong> ${en}</p>
        <p><strong>Doblaje espa\u00f1ol:</strong> ${es}</p>
        <p><strong>Creador:</strong> N/A (no disponible en endpoint)</p>
      `;
    }
  };

  window.AniDexCharacterData = { init };
})();
