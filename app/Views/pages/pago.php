<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" href="img/icon3.webp"/>
<title>NekoraList - Pago Seguro</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Manrope:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
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
              "headline": ["Space Grotesk"],
              "body": ["Manrope"],
              "label": ["Space Grotesk"]
            },
            borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #0e0e0e;
            color: #ffffff;
            font-family: 'Manrope', sans-serif;
        }
        .neon-glow-primary {
            box-shadow: 0 0 20px rgba(205, 189, 255, 0.28);
        }
        .neon-glow-secondary {
            box-shadow: 0 0 15px rgba(205, 189, 255, 0.2);
        }
        .glass-panel {
            background: rgba(32, 31, 31, 0.6);
            backdrop-filter: blur(12px);
        }
        .asymmetric-clip {
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
        }
        .brand-gradient {
            background: linear-gradient(90deg, #38bdf8, #a855f7);
        }
        .brand-text {
            background: linear-gradient(90deg, #38bdf8, #a855f7);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .brand-glow {
            box-shadow: 0 0 18px rgba(56, 189, 248, 0.22), 0 0 26px rgba(168, 85, 247, 0.22);
        }
        .payment-toggle {
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, background 0.25s ease;
        }
        .payment-toggle:hover {
            transform: translateY(-1px);
        }
        .payment-toggle[data-payment-toggle="card"]:hover {
            border-left-color: #38bdf8;
        }
        .payment-toggle[data-active="true"] {
            background: rgba(36, 36, 40, 0.9);
            box-shadow: 0 0 16px rgba(0, 0, 0, 0.35);
        }
        .payment-toggle[data-payment-toggle="card"][data-active="true"] {
            border-color: #ec7c8a;
            box-shadow: 0 0 0 2px rgba(236, 124, 138, 0.2);
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }
        .shake { animation: shake 0.2s ease-in-out 0s 2; }
        .payment-submit:not(:disabled) { cursor: pointer; }
        .payment-submit.btn-invalid { opacity: 0.7; filter: grayscale(0.5); }
        .pay-option {
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .pay-option::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, 0.28) 50%, transparent 100%);
            opacity: 0;
            transform: translateX(-120%);
            transition: transform 0.6s ease, opacity 0.3s ease;
            pointer-events: none;
        }
        .pay-option:hover {
            transform: translateY(-1px) scale(1.02);
        }
        .pay-option:hover::after {
            opacity: 0.7;
            transform: translateX(120%);
        }
        .pay-option--card:hover {
            box-shadow: 0 0 16px rgba(56, 189, 248, 0.35);
        }
        .payment-submit:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
            filter: grayscale(0.2);
        }
        .input-invalid {
            border: 1px solid rgba(239, 68, 68, 0.75);
            box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.35), 0 0 12px rgba(239, 68, 68, 0.25);
        }
        .error-text {
            color: #f87171;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">
<div data-layout="header"></div>
<main class="flex-grow pt-32 pb-20 px-6 max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12">
<!-- Left Side: Checkout Form -->
<div class="lg:col-span-7 space-y-10">
<div class="space-y-2">
<h1 class="font-headline text-5xl font-black italic tracking-tighter text-primary uppercase">ACTIVA TU EXPERIENCIA</h1>
<p class="text-on-surface-variant font-body">Finaliza tu pago y desbloquea tu experiencia en NekoraList.</p>
</div>
<div class="space-y-8">
<!-- Payment Method Toggle + Options -->
<div class="grid grid-cols-1 gap-4">
  <div class="space-y-4">
    <button class="payment-toggle w-full py-4 bg-surface-bright border-l-4 border-[#38bdf8] flex items-center justify-center gap-3 transition-all" data-payment-toggle="card" data-active="false" type="button">
      <span class="material-symbols-outlined text-[#38bdf8]">key</span>
      <span class="font-label uppercase tracking-widest text-sm font-bold">METODO PRINCIPAL</span>
    </button>
    <div class="bg-surface-container-low rounded-xl p-4 border border-white/5 hidden" data-payment-panel="card">
    <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-on-surface-variant">
      <span class="material-symbols-outlined text-[#38bdf8] text-base">key</span>
      METODO PRINCIPAL
    </div>
    <div class="mt-3 flex flex-wrap gap-2">
      <label class="cursor-pointer">
        <input class="sr-only peer" type="radio" name="method-type" value="visa" checked/>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-on-surface-variant transition-all hover:border-[#38bdf8] hover:text-[#38bdf8] hover:bg-[#38bdf8]/10 peer-checked:border-[#38bdf8] peer-checked:text-[#38bdf8] peer-checked:bg-[#38bdf8]/10 peer-checked:shadow-[0_0_12px_rgba(56,189,248,0.35)] pay-option pay-option--card">
          <img src="img/visa.webp" alt="Visa" class="h-4 w-4 object-contain" loading="lazy" decoding="async"/>Visa
        </span>
      </label>
      <label class="cursor-pointer">
        <input class="sr-only peer" type="radio" name="method-type" value="amex"/>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-on-surface-variant transition-all hover:border-[#38bdf8] hover:text-[#38bdf8] hover:bg-[#38bdf8]/10 peer-checked:border-[#38bdf8] peer-checked:text-[#38bdf8] peer-checked:bg-[#38bdf8]/10 peer-checked:shadow-[0_0_12px_rgba(56,189,248,0.35)] pay-option pay-option--card">
          <img src="img/amex.webp" alt="American Express" class="h-4 w-4 object-contain" loading="lazy" decoding="async"/>Amex
        </span>
      </label>
      <label class="cursor-pointer">
        <input class="sr-only peer" type="radio" name="method-type" value="discover"/>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-on-surface-variant transition-all hover:border-[#38bdf8] hover:text-[#38bdf8] hover:bg-[#38bdf8]/10 peer-checked:border-[#38bdf8] peer-checked:text-[#38bdf8] peer-checked:bg-[#38bdf8]/10 peer-checked:shadow-[0_0_12px_rgba(56,189,248,0.35)] pay-option pay-option--card">
          <img src="img/discover.webp" alt="Discover" class="h-4 w-4 object-contain" loading="lazy" decoding="async"/>Discover
        </span>
      </label>
      <label class="cursor-pointer">
        <input class="sr-only peer" type="radio" name="method-type" value="jcb"/>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-on-surface-variant transition-all hover:border-[#38bdf8] hover:text-[#38bdf8] hover:bg-[#38bdf8]/10 peer-checked:border-[#38bdf8] peer-checked:text-[#38bdf8] peer-checked:bg-[#38bdf8]/10 peer-checked:shadow-[0_0_12px_rgba(56,189,248,0.35)] pay-option pay-option--card">
          <img src="img/jcb.webp" alt="JCB" class="h-4 w-4 object-contain" loading="lazy" decoding="async"/>JCB
        </span>
      </label>
    </div>
    </div>
  </div>
  
</div>
<!-- Form Fields -->
<form id="main-payment-form" class="space-y-6" onsubmit="event.preventDefault(); return false;">
<div class="space-y-6" data-payment-form-panel="card">
  <div class="space-y-1 hidden" data-selected-method>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#38bdf8]">METODO SELECCIONADO: <span class="text-white" data-selected-method-name></span></p>
  </div>
  <div class="space-y-2">
    <label class="font-label text-xs tracking-widest uppercase text-on-surface-variant">NOMBRE DEL PARTICIPANTE</label>
    <input class="w-full bg-surface-container-highest border-none rounded-xl py-4 px-6 text-on-surface font-headline focus:ring-1 focus:ring-primary focus:neon-glow-secondary transition-all placeholder:opacity-20" placeholder="SHINJI IKARI" type="text" data-field-holder required/>
    <p class="text-[11px] error-text hidden" data-error="field-holder">Completa este campo.</p>
  </div>
  <div class="space-y-2">
    <label class="font-label text-xs tracking-widest uppercase text-on-surface-variant" data-field-code-label>CODIGO PRINCIPAL</label>
    <p class="text-[11px] text-on-surface-variant/70" data-field-code-hint>16 d?gitos</p>
    <div class="relative">
      <input class="w-full bg-surface-container-highest border-none rounded-xl py-4 px-6 text-on-surface font-headline focus:ring-1 focus:ring-primary focus:neon-glow-secondary transition-all placeholder:opacity-20" placeholder="Ej: 1234567890123456" type="text" data-field-code-input maxlength="19" required/>
      <p class="text-[11px] error-text hidden" data-error="field-code">Codigo invalido o incompleto.</p>
      <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">lock</span>
    </div>
  </div>
  <div class="grid grid-cols-2 gap-6">
    <div class="space-y-2">
      <label class="font-label text-xs tracking-widest uppercase text-on-surface-variant">FECHA (MM/AA)</label>
      <input class="w-full bg-surface-container-highest border-none rounded-xl py-4 px-6 text-on-surface font-headline focus:ring-1 focus:ring-primary focus:neon-glow-secondary transition-all placeholder:opacity-20" placeholder="MM/AA" type="text" data-field-date maxlength="5" required/>
      <p class="text-[11px] error-text hidden" data-error="field-date">Formato invalido.</p>
    </div>
    <div class="space-y-2">
      <label class="font-label text-xs tracking-widest uppercase text-on-surface-variant" data-field-pin-label>CODIGO PRIVADO (3 DIGITOS)</label>
      <input class="w-full bg-surface-container-highest border-none rounded-xl py-4 px-6 text-on-surface font-headline focus:ring-1 focus:ring-primary focus:neon-glow-secondary transition-all placeholder:opacity-20" placeholder="123" type="text" data-field-pin-input maxlength="3" required/>
      <p class="text-[11px] error-text hidden" data-error="field-pin">Codigo invalido.</p>
    </div>
  </div>
  <div class="space-y-2">
    <label class="font-label text-xs tracking-widest uppercase text-on-surface-variant">DIRECCION DE CONTACTO</label>
    <input class="w-full bg-surface-container-highest border-none rounded-xl py-4 px-6 text-on-surface font-headline focus:ring-1 focus:ring-primary focus:neon-glow-secondary transition-all placeholder:opacity-20" placeholder="Av. Principal 123, Ciudad" type="text" data-field-address required/>
    <p class="text-[11px] error-text hidden" data-error="field-address">Completa este campo.</p>
  </div>
</div>
<div class="pt-6">
<div id="btn-completar-compra" class="payment-submit w-full brand-gradient py-5 rounded-lg text-on-primary-container font-headline font-black uppercase tracking-widest text-lg brand-glow hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 cursor-pointer select-none" role="button" data-payment-submit onclick="window.processNekoraPayment(event)">COMPLETAR COMPRA <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">bolt</span></div>
<div id="payment-status-log" class="mt-4 p-4 rounded-xl bg-black/40 border border-white/5 text-[10px] font-mono text-white/40 space-y-1 hidden"></div>
</div>
</form>
</div>
</div>
<!-- Right Side: Order Summary -->
<div class="lg:col-span-5">
<div class="sticky top-28 space-y-6">
<div class="glass-panel rounded-xl overflow-hidden border border-outline-variant/15">
<!-- Energy Bar -->
<div class="h-1 w-full bg-gradient-to-r from-[#38bdf8] via-[#a855f7] to-[#cdbdff]"></div>
<div class="p-8 space-y-8">
<div class="flex justify-between items-start">
<div>
<h2 class="font-headline text-2xl font-black italic tracking-tighter text-[#a855f7] uppercase">RESUMEN DEL PEDIDO</h2>
<p class="text-on-surface-variant font-label text-[10px] tracking-widest">ID_TRANSACCION: NK-5931-884</p>
</div>
<span class="bg-[#a855f7]/15 text-[#a855f7] px-3 py-1 rounded-full font-label text-[10px] font-bold tracking-widest uppercase">ACCESO LIMITADO</span>
</div>
<!-- Product Card -->
<div class="bg-surface-container p-4 rounded-lg flex gap-4 items-center">
<div class="w-24 h-24 bg-surface-container-highest rounded-lg overflow-hidden flex-shrink-0 relative border border-primary/20">
<img alt="NekoraList icon" class="w-full h-full object-cover" data-alt="NekoraList icon" src="img/icon.webp"/>
<div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
</div>
<div class="flex-grow">
<h3 class="font-headline font-bold text-lg text-primary leading-tight">NekoraList</h3>
<p class="text-on-surface-variant text-sm font-body">Acceso: Modo Limit Break</p>
<div class="mt-2 text-[#38bdf8] font-headline font-bold">$4.99/mo</div>
</div>
</div>
<!-- Price Breakdown -->
<div class="space-y-4 border-t border-outline-variant/15 pt-6">
<div class="flex justify-between text-on-surface-variant font-label text-xs uppercase tracking-widest">
<span>TASA DE ACCESO</span>
<span>$4.99</span>
</div>
<div class="flex justify-between text-on-surface-variant font-label text-xs uppercase tracking-widest">
<span>IVA (15%)</span>
<span>$0.75</span>
</div>
<div class="flex justify-between text-on-surface-variant font-label text-xs uppercase tracking-widest">
<span>DESCUENTO ESPECIAL </span>
<span class="text-[#a855f7]">-$0.44</span>
</div>
</div>
<div class="pt-6 border-t border-primary/30 flex justify-between items-baseline">
<span class="font-headline font-black text-xl italic text-on-surface tracking-tighter uppercase">TOTAL A PAGAR</span>
<span class="font-headline font-black text-4xl text-primary tracking-tighter">$5.30</span>
</div>
</div>
</div>
<!-- Incentive Box -->
<div class="p-6 bg-surface-container-low rounded-xl flex items-center gap-4 border-l-4 border-[#38bdf8] asymmetric-clip">
<span class="material-symbols-outlined text-[#38bdf8] text-3xl">auto_awesome</span>
<div>
<p class="font-headline font-bold text-sm text-[#38bdf8] uppercase tracking-widest">MEJORA INSTANTANEA</p>
<p class="text-on-surface-variant text-xs font-body">Accede al contenido exclusivo de NekoraList apenas se valide tu pago.</p>
</div>
</div>
</div>
</div>
</main>
<div data-layout="footer"></div>
<div id="payment-success" class="fixed inset-0 z-[80] hidden flex items-start justify-center">
  <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
  <div class="relative mt-[20vh] inline-flex w-fit max-w-[24rem] flex-col items-center rounded-2xl bg-surface-container-high/90 border border-violet-500/30 p-8 shadow-2xl text-center overflow-hidden">
    <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-violet-500/20 blur-3xl"></span>
    <span class="pointer-events-none absolute -bottom-24 -left-16 h-44 w-44 rounded-full bg-fuchsia-500/20 blur-3xl"></span>
    <button type="button" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-all" onclick="document.getElementById('payment-success').classList.add('hidden')">×</button>
    <div class="relative mb-6">
      <div class="absolute inset-0 blur-xl bg-violet-500/30 animate-pulse rounded-full"></div>
      <img src="img/doraemon.gif" alt="Sucesso" class="relative w-24 h-24 mx-auto rounded-full border-2 border-violet-400/50 shadow-lg" />
    </div>
    <h3 class="font-headline text-3xl font-extrabold text-white tracking-tighter uppercase italic">?COMPRA EXITOSA!</h3>
    <div class="mt-4 space-y-2">
      <h4 class="font-headline text-xl font-bold text-violet-300">Bienvenido, <span id="payment-name" class="text-white">Nekora</span>!</h4>
      <p class="text-on-surface-variant text-sm font-body max-w-[18rem] mx-auto opacity-80">Tu acceso al modo <span class="text-primary font-bold">Limit Break</span> ya está activo.</p>
    </div>
    <div class="mt-8 flex items-center gap-3 px-6 py-3 bg-violet-500/10 rounded-full border border-violet-500/20 animate-pulse">
      <span class="material-symbols-outlined text-violet-400 text-sm">sync</span>
      <span class="text-violet-300 font-label text-[10px] uppercase tracking-widest font-black">Redireccionando al inicio...</span>
    </div>
  </div>
</div>

<script>
  // SISTEMA DE PAGO GLOBAL RECONSTRUIDO (FINAL)
  const cardOptions = {
    visa: { name: "Visa", numDigits: 16, pinDigits: 3 },
    amex: { name: "Amex", numDigits: 15, pinDigits: 4 },
    discover: { name: "Discover", numDigits: 16, pinDigits: 3 },
    jcb: { name: "JCB", numDigits: 16, pinDigits: 3 }
  };

  const onlyDig = (s) => String(s || "").replace(/\D/g, "");
  
  const luhnCheck = (v) => {
    let s = 0;
    for (let i = 0; i < v.length; i++) {
        let n = parseInt(v.substr(v.length - 1 - i, 1));
        if (i % 2 === 1) { n *= 2; if (n > 9) n -= 9; }
        s += n;
    }
    return s % 10 === 0;
  };

  window.processNekoraPayment = async (event) => {
    if (event) { event.preventDefault(); event.stopImmediatePropagation(); }
    
    const btn = document.getElementById("btn-completar-compra");
    const logBox = document.getElementById("payment-status-log");

    const log = (msg, isErr = false) => {
        if (!logBox) return;
        logBox.classList.remove("hidden");
        const line = document.createElement("div");
        line.className = "flex items-center gap-2 " + (isErr ? "text-red-400 font-bold" : "text-white/70");
        line.innerHTML = `<span class="material-symbols-outlined text-[10px]">${isErr ? 'error' : 'check_circle'}</span> ${msg}`;
        logBox.appendChild(line);
        logBox.scrollTop = logBox.scrollHeight;
    };

    if (logBox) { logBox.innerHTML = ""; logBox.classList.remove("hidden"); }
    if (btn) {
        btn.style.pointerEvents = "none";
        btn.innerHTML = 'VERIFICANDO...';
    }

    log("Iniciando Verificaci?n Nekora...");

    try {
        const holder = document.querySelector("[data-field-holder]")?.value.trim() || "";
        const code = onlyDig(document.querySelector("[data-field-code-input]")?.value || "");
        const date = document.querySelector("[data-field-date]")?.value.trim() || "";
        const pin = onlyDig(document.querySelector("[data-field-pin-input]")?.value || "");
        const addr = document.querySelector("[data-field-address]")?.value.trim() || "";
        const method = (Array.from(document.querySelectorAll('input[name="method-type"]')).find(r => r.checked))?.value || "visa";

        // Paso a paso
        if (holder.length < 4) log("Error: nombre demasiado corto.", true); else log("Nombre OK");
        
        let codeOk = code.length === cardOptions[method].numDigits && luhnCheck(code);
        if (!codeOk) log("Error: tarjeta inválida (" + cardOptions[method].name + ").", true); else log("Tarjeta OK");
        
        let dateOk = date.length === 5 && date.includes("/");
        if (!dateOk) log("Error: formato de fecha MM/AA.", true); else log("Fecha OK");
        
        let pinOk = pin.length === cardOptions[method].pinDigits;
        if (!pinOk) log("Error: PIN (" + cardOptions[method].pinDigits + " dígitos).", true); else log("PIN OK");
        
        if (addr.length < 6) log("Error: dirección incorrecta.", true); else log("Dirección OK");

        const hasErrors = holder.length < 4 || !codeOk || !dateOk || !pinOk || addr.length < 6;

        if (hasErrors) {
            log("CORRIGE LOS ERRORES ARRIBA.", true);
            if (btn) {
                btn.style.pointerEvents = "auto";
                btn.innerHTML = 'COMPLETAR COMPRA <span class="material-symbols-outlined">bolt</span>';
            }
            return;
        }

        log("Conectando con el servidor...");
        const auth = window.AniDexLayout ? await window.AniDexLayout.checkAuth() : { logged: false };
        if (!auth.logged) { 
            log("Sesión no encontrada.", true); 
            window.location.href = "ingresar?redirect=pago";
            return; 
        }

        log("Procesando transacción...");
        window.onbeforeunload = () => "Pago en curso...";

        const res = await fetch("<?= asset_path('api/auth') ?>?action=buy_premium", { method: "POST" });
        const data = await res.json();
        window.onbeforeunload = null;

        if (data.success) {
            log("¡PAGO CONFIRMADO!");
            try { new Audio("https://cdn.pixabay.com/audio/2021/11/24/audio_985532d525.mp3").play(); } catch(e){}
            localStorage.setItem("nekora_premium", "true");
            document.getElementById("payment-success")?.classList.remove("hidden");
            if (window.AniDexLayout?.checkAuth) await window.AniDexLayout.checkAuth();
            setTimeout(() => { window.location.href = "index"; }, 2500);
            log("Error del servidor: " + (data.error || "Fallo"), true);
            log("Error del servidor: " + (data.error || "Fallo"), true);
            if (btn) { btn.style.pointerEvents = "auto"; btn.innerHTML = 'COMPLETAR COMPRA'; }
        }
        log("Error: " + e.message, true);
        log("Error: " + e.message, true);
        if (btn) { btn.style.pointerEvents = "auto"; btn.innerHTML = 'COMPLETAR COMPRA'; }
    }
  };

  document.addEventListener("DOMContentLoaded", () => {
    const codeInp = document.querySelector("[data-field-code-input]");
    const dateInp = document.querySelector("[data-field-date]");
    const toggles = Array.from(document.querySelectorAll("[data-payment-toggle]"));
    const panels = { card: document.querySelector('[data-payment-panel="card"]') };
    const formPanels = { card: document.querySelector('[data-payment-form-panel="card"]') };

    function goPanel(k) {
        Object.entries(panels).forEach(([key, v]) => v?.classList.toggle("hidden", key !== k));
        Object.entries(formPanels).forEach(([key, v]) => v?.classList.toggle("hidden", key !== k));
        toggles.forEach(b => b.setAttribute("data-active", b.getAttribute("data-payment-toggle") === k ? "true" : "false"));
    }
    
    toggles.forEach(b => b.addEventListener("click", () => goPanel(b.getAttribute("data-payment-toggle"))));

    if (codeInp) {
        codeInp.addEventListener("input", (e) => {
            let v = e.target.value.replace(/\D/g, "").slice(0, 16);
            let p = v.match(/.{1,4}/g) || [];
            e.target.value = p.join(" ");
        });
    }

    if (dateInp) {
        dateInp.addEventListener("input", (e) => {
            let v = e.target.value.replace(/\D/g, "").slice(0, 4);
            if (v.length >= 3) {
                e.target.value = v.slice(0, 2) + "/" + v.slice(2);
            } else {
                e.target.value = v;
            }
        });
        dateInp.setAttribute("maxlength", "5");
    }

    // Inicializar UI
    goPanel("card");
  });
</script>

<script src="assets/js/i18n.js"></script>
<script src="assets/js/search.js?v=popular7"></script>
</body>
</html>




















