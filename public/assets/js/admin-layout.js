(() => {
  const basePath = window.location.pathname.includes('/views/') ? '../' : '';
  async function loadAdminSidebar() {
    const host = document.querySelector('[data-admin-sidebar]');
    if (!host) return;
    try {
      const res = await fetch(basePath + "partials/admin-layout", { cache: 'no-store' });
      if (!res.ok) return;
      const html = await res.text();
      const container = document.createElement('div');
      container.innerHTML = html;
      const tpl = container.querySelector('#admin-sidebar');
      if (!tpl) return;
      host.innerHTML = tpl.innerHTML.trim();

      const page = document.body.dataset.adminPage || '';
      const active = host.querySelector(`[data-admin-item="${page}"]`);
      if (active) {
        active.className = 'flex items-center gap-4 text-[#cdbdff] bg-[#1f2020] rounded-r-full border-l-4 border-[#7c4dff] py-4 px-6 mb-2 transition-all duration-300 font-headline font-bold tracking-tight text-sm uppercase';
        const icon = active.querySelector('.material-symbols-outlined');
        if (icon) icon.style.fontVariationSettings = "'FILL' 1";
      }

      const logout = host.querySelector('[data-admin-logout]');
      if (logout) {
        logout.addEventListener('click', async (event) => {
          event.preventDefault();
          try {
            const res = await fetch('api/auth.php?action=check', { cache: 'no-store', credentials: 'same-origin' });
            if (res.ok) {
              const auth = await res.json();
              if (auth && auth.logged) {
                localStorage.setItem('nekora_logged_in', 'true');
                if (auth.username) {
                  localStorage.setItem('nekora_user', auth.username);
                  localStorage.setItem('anidex_profile_name', auth.username);
                }
                if (auth.userId) localStorage.setItem('anidex_user_id', auth.userId);
                if (auth.publicUserId) localStorage.setItem('anidex_public_user_id', auth.publicUserId);
                if (auth.isPremium || auth.isAdmin) localStorage.setItem('nekora_premium', 'true');
                else localStorage.removeItem('nekora_premium');
                if (auth.isAdmin) localStorage.setItem('nekora_admin', 'true');
                else localStorage.removeItem('nekora_admin');

                try {
                  const profileRes = await fetch('api/profile.php?action=get', { cache: 'no-store', credentials: 'same-origin' });
                  if (profileRes.ok) {
                    const profileJson = await profileRes.json();
                    const d = profileJson?.data || null;
                    if (profileJson?.success && d) {
                      if (d.anidex_profile_name) localStorage.setItem('anidex_profile_name', d.anidex_profile_name);
                      if (d.anidex_profile_desc && auth.userId) localStorage.setItem(`anidex_profile_desc_${auth.userId}`, d.anidex_profile_desc);
                      if (d.anidex_profile_color && auth.userId) localStorage.setItem(`anidex_profile_color_${auth.userId}`, d.anidex_profile_color);
                      if (d.anidex_profile_avatar) {
                        localStorage.setItem('anidex_profile_avatar', d.anidex_profile_avatar);
                        if (auth.userId) localStorage.setItem(`anidex_profile_avatar_${auth.userId}`, d.anidex_profile_avatar);
                      }
                    }
                  }
                } catch (e) {}
              }
            }
          } catch (e) {}
          window.location.href = 'index';
        });
      }

      if (document.body.dataset.adminPage) {
        initAdminButtons();
      }
    } catch (e) {}
  }

  function initAdminButtons() {
    if (document.querySelector('[data-admin-pagination-script]')) return;

    const showToast = () => {};

    const externalPagination = document.querySelector('[data-admin-pagination-script]');
    const rows = Array.from(document.querySelectorAll('[data-admin-request-row]'));
    if (rows.length > 0 && !externalPagination) {
      const targetTotal = 24;
      const template = rows[0].cloneNode(true);
      while (rows.length < targetTotal) {
        const clone = template.cloneNode(true);
        clone.removeAttribute('style');
        rows[0].parentElement.appendChild(clone);
        rows.push(clone);
      }
      rows.forEach((row, idx) => {
        row.dataset.adminRowIndex = String(idx);
      });

      const names = [
        'Elias Kael','Mio Naruse','S. Ryuzaki','T. Vercetti','Lina Kuroi','Akira Ren','Valen Sato','Noa Ishikawa',
        'Mika Arai','Ryo Tan','Ivy Rojas','Ken Sora','Nadia Bloom','Haruto Jin','Sora Vale','Kira Ames',
        'Maya Rin','Theo Cruz','Yuna Mori','Kai Lotus','Ari Vega','Suzu Hane','Taro Wisp','Luca Frost'
      ];
      const roles = [
        'Curador Nivel 4','Nuevo Miembro','Colaborador Premium','Curador Nivel 2','Editor Invitado','Moderador',
        'Curador Senior','Curador Nivel 3','Beta Tester','Staff Temporal','Curador Nivel 1','Curador Elite'
      ];
      const titles = [
        'Cyberpunk: Edgerunners (Season 2)','Solo Leveling: Arise OVA','Blue Lock - Episode Nagi','Monster: Special Edition (Remaster)',
        'Frieren: Beyond Journeys End - OVA','Jujutsu Kaisen: Culling Game','Oshi no Ko: Side Story','Hells Paradise: Rebirth',
        'Pluto: Directors Cut','Vinland Saga: North Arc','Haikyuu!! Final Serve','Spy x Family: Code White',
        'Chainsaw Man: School Arc','Berserk: Eclipse Remake','Mob Psycho 100: Encore','Attack on Titan: Aftermath',
        'Death Note: Legacy Files','Demon Slayer: Infinity Path','Your Name: Recut','A Silent Voice: Reprise',
        'Steins;Gate: Chrono Shift','Fullmetal Alchemist: Redux','Hunter x Hunter: Lost Pages','Naruto: New Dawn'
      ];
      const sources = [
        'Studio Trigger / Netflix','A-1 Pictures','Eight Bit','Madhouse','CloverWorks','MAPPA','WIT Studio',
        'Bones','Production I.G','Ufotable','Kyoto Animation','OLM'
      ];
      const dates = [
        'Oct 24, 2023  09:12 AM','Oct 23, 2023  04:45 PM','Oct 22, 2023  11:20 AM','Oct 21, 2023  10:00 PM',
        'Oct 20, 2023  08:05 AM','Oct 19, 2023  06:18 PM','Oct 18, 2023  01:33 PM','Oct 17, 2023  09:49 AM'
      ];
      const initials = (name) => name.split(' ').map((p) => p[0]).slice(0,2).join('').toUpperCase();
      const badgeColors = ['text-primary','text-tertiary-dim','text-on-surface-variant','text-primary','text-on-surface','text-tertiary-dim'];

      rows.forEach((row, idx) => {
        const name = `${names[idx % names.length]} #${idx + 1}`;
        const role = roles[idx % roles.length];
        const title = `${titles[idx % titles.length]}  Req ${idx + 1}`;
        const source = sources[idx % sources.length];
        const date = dates[idx % dates.length];

        const nameEl = row.querySelector('p.text-sm.font-semibold');
        if (nameEl) nameEl.textContent = name;
        const roleEl = row.querySelector('p.text-[10px]');
        if (roleEl) roleEl.textContent = role;
        const titleEl = row.querySelector('p.text-base.font-bold');
        if (titleEl) titleEl.textContent = title;
        const sourceEl = row.querySelector('p.text-xs.text-on-surface-variant.italic');
        if (sourceEl) sourceEl.textContent = `Source: ${source}`;
        const dateEl = row.querySelector('p.text-xs.text-on-surface-variant.font-body');
        if (dateEl) dateEl.textContent = date;

        const initialBadge = row.querySelector('.w-8.h-8');
        if (initialBadge) {
          initialBadge.textContent = initials(name);
          initialBadge.className = `w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-[10px] font-bold ${badgeColors[idx % badgeColors.length]}`;
        }
      });
    }

    let currentPage = 1;
    const pageSize = 4;
    const updatePagination = () => {
  const basePath = window.location.pathname.includes('/views/') ? '../' : '';
      const tableBody = document.querySelector('tbody');
      if (tableBody) {
        tableBody.insertAdjacentHTML('beforeend', '');
      }
      const allRows = Array.from(document.querySelectorAll('[data-admin-request-row]'));
      const total = allRows.length;
      const maxPage = Math.max(1, Math.ceil(total / pageSize));
      if (currentPage > maxPage) currentPage = maxPage;
      const start = (currentPage - 1) * pageSize;
      const end = start + pageSize;
      allRows.forEach((row, idx) => {
        const isVisible = idx >= start && idx < end;
        if (isVisible) {
          row.classList.add('admin-visible');
        } else {
          row.classList.remove('admin-visible');
        }
      });
      const countEl = document.querySelector('[data-admin-pending-count]');
      const textEl = document.querySelector('[data-admin-pending-text]');
      const footerEl = document.querySelector('[data-admin-pending-footer]');
      if (countEl) countEl.textContent = String(total);
      if (textEl) textEl.textContent = `${total} solicitudes pendientes`;
      if (footerEl) footerEl.textContent = `Mostrando ${Math.min(end, total)} de ${total} solicitudes pendientes`;

      const prevBtn = document.querySelector('[data-admin-page-prev]');
      const nextBtn = document.querySelector('[data-admin-page-next]');
      if (prevBtn) prevBtn.disabled = currentPage <= 1;
      if (nextBtn) nextBtn.disabled = currentPage >= maxPage;
    };
    updatePagination();
    requestAnimationFrame(updatePagination);
    setTimeout(updatePagination, 30);

    document.addEventListener('click', (event) => {
      if (document.querySelector('[data-admin-pagination-script]')) {
        return;
      }
      const prevBtn = event.target.closest('[data-admin-page-prev]');
      if (prevBtn) {
        event.preventDefault();
        if (currentPage > 1) currentPage -= 1;
        updatePagination();
        return;
      }
      const nextBtn = event.target.closest('[data-admin-page-next]');
      if (nextBtn) {
        event.preventDefault();
        currentPage += 1;
        updatePagination();
        return;
      }

      const rejectBtn = event.target.closest('[data-admin-reject]');
      if (rejectBtn) {
        event.preventDefault();
        const row = rejectBtn.closest('[data-admin-request-row]');
        const approve = row ? row.querySelector('[data-admin-approve]') : null;

        if (rejectBtn.dataset.rejectState === 'on') {
          rejectBtn.dataset.rejectState = 'off';
          if (rejectBtn.dataset.origClass) rejectBtn.className = rejectBtn.dataset.origClass;
          if (rejectBtn.dataset.origHtml) rejectBtn.innerHTML = rejectBtn.dataset.origHtml;
          if (approve) {
            approve.disabled = false;
            if (approve.dataset.origClass) approve.className = approve.dataset.origClass;
            if (approve.dataset.origHtml) approve.innerHTML = approve.dataset.origHtml;
          }
          if (row) row.classList.remove('opacity-60');
          return;
        }

        if (!rejectBtn.dataset.origClass) rejectBtn.dataset.origClass = rejectBtn.className;
        if (!rejectBtn.dataset.origHtml) rejectBtn.dataset.origHtml = rejectBtn.innerHTML;
        if (approve) {
          if (!approve.dataset.origClass) approve.dataset.origClass = approve.className;
          if (!approve.dataset.origHtml) approve.dataset.origHtml = approve.innerHTML;
          approve.disabled = true;
          approve.className = 'px-5 py-2 rounded-full bg-surface-container-highest text-on-surface-variant font-headline font-bold text-xs uppercase tracking-tight flex items-center gap-2 opacity-50 cursor-not-allowed';
        }

        rejectBtn.dataset.rejectState = 'on';
        rejectBtn.className = 'px-5 py-2 rounded-full bg-error/15 text-error font-headline font-bold text-xs uppercase tracking-tight flex items-center gap-2';
        rejectBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]" data-icon="cancel">cancel</span> NO APROBADO';
        if (row) row.classList.add('opacity-60');
        return;
      }

      const quick = event.target.closest('[data-admin-quick-action]');
      if (quick) {
        const countEl = document.querySelector('[data-admin-pending-count]');
        const textEl = document.querySelector('[data-admin-pending-text]');
        const footerEl = document.querySelector('[data-admin-pending-footer]');
        if (countEl) countEl.textContent = '0';
        if (textEl) textEl.textContent = '0 solicitudes pendientes';
        if (footerEl) footerEl.textContent = 'Mostrando 0 de 0 solicitudes pendientes';

        document.querySelectorAll('[data-admin-request-row]').forEach((row) => {
          row.classList.add('opacity-70');
          const approve = row.querySelector('[data-admin-approve]');
          if (approve) {
            approve.disabled = true;
            approve.className = 'px-5 py-2 rounded-full bg-emerald-500/15 text-emerald-300 font-headline font-bold text-xs uppercase tracking-tight flex items-center gap-2';
            approve.innerHTML = '<span class=\"material-symbols-outlined text-[18px]\" data-icon=\"check_circle\">check_circle</span> APROBADO';
          }
        });
        return;
      }

      const button = event.target.closest('button');
      if (!button) return;
      if (!document.body.dataset.adminPage) return;
      if (button.closest('form') && button.type === 'submit') return;
      const link = button.getAttribute('data-admin-link');
      if (link) {
        window.location.href = link;
        return;
      }
      const pageKey = document.body.dataset.adminPage || '';
      if (pageKey === 'comments' || pageKey === 'users' || pageKey === 'manage') {
        return;
      }
    });

    const searchInput = document.querySelector('[data-admin-search]');
    if (searchInput) {
      searchInput.addEventListener('input', () => {
  const basePath = window.location.pathname.includes('/views/') ? '../' : '';
        const term = searchInput.value.trim().toLowerCase();
        const rows = Array.from(document.querySelectorAll('[data-admin-request-row]'));
        if (!term) {
          updatePagination();
          return;
        }
        let visibleCount = 0;
        rows.forEach((row) => {
          const text = row.textContent.toLowerCase();
          const match = !term || text.includes(term);
          row.style.display = match ? '' : 'none';
          if (match) visibleCount += 1;
        });

        const countEl = document.querySelector('[data-admin-pending-count]');
        const textEl = document.querySelector('[data-admin-pending-text]');
        const footerEl = document.querySelector('[data-admin-pending-footer]');
        if (countEl) countEl.textContent = String(visibleCount);
        if (textEl) textEl.textContent = `${visibleCount} solicitudes pendientes`;
        if (footerEl) footerEl.textContent = `Mostrando ${visibleCount} de ${visibleCount} solicitudes pendientes`;
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAdminSidebar);
  } else {
    loadAdminSidebar();
  }
})();



