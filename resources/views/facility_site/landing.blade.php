@extends('facility_site.layouts.base')

@section('head_extra')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => $facility->name,
  'url' => $facility->website ?: url()->current(),
  'logo' => $facility->logo_url,
  'description' => $facility->meta_description ?: ($facility->description ?: ''),
  'sameAs' => array_values(array_filter([
    $facility->facebook_url ?? null,
    $facility->twitter_url ?? null,
    $facility->instagram_url ?? null,
    $facility->linkedin_url ?? null,
  ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  /* Landing radical redesign (scoped to landing sections) */
  #hero{ position:relative; min-height:76vh; display:grid; place-items:center; background:
    radial-gradient(1200px 800px at 120% -10%, rgba(252,174,65,.18), transparent 60%),
    linear-gradient(135deg, rgba(252,174,65,.22), transparent 48%),
    var(--bg);
  }
  #hero .hero-copy{ text-align:center; max-width: 80ch; margin-inline:auto }
  #hero h1{ font-weight:900; font-size: clamp(28px, 5vw, 48px); line-height:1.2; letter-spacing:.2px }
  #hero p{ color: color-mix(in oklab, var(--fg), transparent 40%); font-size: clamp(15px, 2.2vw, 18px); margin:.6rem auto 1rem }
  #hero .eyebrow{ display:inline-block; background: var(--primary); color:#000; font-weight:900; padding:.35rem .7rem; border-radius: 999px; letter-spacing:.3px }
  #hero ul{ display:flex; gap:.6rem; justify-content:center; flex-wrap:wrap; margin:.8rem 0 }
  #hero ul li{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 85%); padding:.35rem .6rem; border-radius:999px; font-weight:700 }
  #hero .hero-media{ display:none }
  @media (min-width:1024px){
    #hero .hero-media{ display:block }
    #hero .surface-box{ position:relative; width:460px; max-width:38vw; aspect-ratio:1/1; margin-inline:auto }
    #hero .surface-box::before{ content:""; position:absolute; inset:0; border-radius:999px; background: radial-gradient(closest-side, color-mix(in oklab, var(--primary), transparent 0%), transparent 70%); filter: saturate(120%) blur(0px) }
    #hero .hero-media-img{ position:relative; z-index:1; width:100%; height:100%; object-fit:cover; border-radius:28px; box-shadow: 0 24px 60px rgba(0,0,0,.35) }
  }
  #hero .side-label{ position:absolute; inset:auto auto 18% 12px; writing-mode:vertical-rl; transform: rotate(180deg); letter-spacing:.35rem; font-weight:900; font-size:.8rem; color: color-mix(in oklab, var(--fg), transparent 55%); display:none }
  @media (min-width:1280px){ #hero .side-label{ display:block } }
  /* Hero highlight + blobs */
  .hl{ background: linear-gradient(90deg, var(--primary), color-mix(in oklab, var(--primary), var(--fg) 20%)); -webkit-background-clip: text; background-clip:text; color: transparent; position: relative }
  .hl::after{ content:""; position:absolute; inset:auto 0 -2px 0; height:10px; background: color-mix(in oklab, var(--primary), transparent 70%); filter: blur(6px); border-radius:999px; z-index:-1 }
  .blob{ position:absolute; inset:auto; width:520px; height:520px; border-radius:50%; filter: blur(60px); opacity:.35; pointer-events:none; transform: translateZ(0) }
  .blob.b1{ right:-140px; top:-80px; background: radial-gradient(closest-side, var(--orb-1), transparent) }
  .blob.b2{ left:-160px; bottom:-120px; background: radial-gradient(closest-side, var(--orb-2), transparent) }

  /* Section rhythm */
  :root{ --accent: var(--primary) }
  .section{ padding-block: 72px }
  .section h2{ font-weight:900; font-size: clamp(22px, 3.2vw, 34px); margin:0 0 .6rem }
  .section.alt{ background: color-mix(in oklab, var(--bg), var(--fg) 4%) }
  /* Container + responsive helpers */
  .container{ width:min(1160px, 92vw); margin-inline:auto }
  .grid-2{ display:grid; grid-template-columns: 1fr 1fr; gap:1.2rem }
  @media (max-width:1024px){ .grid-2{ grid-template-columns: 1fr } }
  .grid-3{ display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:1rem }
  @media (max-width:1024px){ .grid-3{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ .grid-3{ grid-template-columns: 1fr } }
  /* Global link styling: no default blue/underline */
  a{ color: inherit; text-decoration: none }
  a:hover{ color: var(--primary); text-decoration: none }
  /* Decorative headings */
  .title-deco{ position:relative; display:inline-flex; align-items:center; gap:.6rem }
  .title-deco::before{ content:""; width:8px; height:8px; border-radius:50%; background: var(--accent, var(--primary)) }
  .title-deco::after{ content:""; display:block; width:64px; height:2px; background: color-mix(in oklab, var(--fg), transparent 75%) }

  /* Reveal on scroll */
  .reveal{ opacity:0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease }
  .reveal.in{ opacity:1; transform: translateY(0) }

  /* Products grid */
  #services .grid-3{ grid-template-columns: repeat(3, minmax(0,1fr)); }
  @media (max-width:1024px){ #services .grid-3{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ #services .grid-3{ grid-template-columns: 1fr } }
  #services .feature{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 85%); border-radius:16px; padding:16px; box-shadow: var(--shadow-sm) }
  #services .feature h4{ font-weight:800 }
  #services .feature .btn-link{ color: var(--primary); font-weight:800 }
  /* Remove underline for link-style buttons in landing */
  .btn-link{ text-decoration: none }

  /* Why Us */
  #why-us .grid-4{ display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:1rem }
  @media (max-width:1024px){ #why-us .grid-4{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ #why-us .grid-4{ grid-template-columns: 1fr } }

  /* Two-column narrative sections */
  #vision-mission .container, #goals-values .container{ gap: 1.2rem }
  #goals-values ul{ margin:.4rem 0 0 }

  /* Contact cards */
  #contact .grid-3{ grid-template-columns: repeat(3, minmax(0,1fr)); }
  @media (max-width:1024px){ #contact .grid-3{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ #contact .grid-3{ grid-template-columns: 1fr } }
  #contact .form-card{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 85%); border-radius:16px; padding:16px }
  #contact .aside{ display:flex; flex-direction:column; gap:12px }
  #contact label{ display:block; font-weight:700; font-size:.92rem; margin-bottom:.35rem }
  #contact input, #contact select, #contact textarea{ width:100%; border:1px solid color-mix(in oklab, var(--fg), transparent 85%); background: var(--bg); color: var(--fg); border-radius:10px; padding:.6rem .7rem }
  #contact .form-grid{ display:grid; grid-template-columns: 1fr 1fr; gap:.8rem }
  #contact .form-grid .full{ grid-column: 1 / -1 }
  @media (max-width:768px){ #contact .form-grid{ grid-template-columns: 1fr } }
  #contact .info-list{ display:grid; gap:.6rem }
  #contact .info-item{ display:flex; gap:.6rem; align-items:flex-start; padding:.6rem .8rem; background: color-mix(in oklab, var(--fg), transparent 94%); border-radius:12px }
  #contact .info-item i{ color:#000; background: var(--primary); width:28px; height:28px; border-radius:8px; display:grid; place-items:center }
  /* Carousel badges */
  #products-carousel .card .badge{ margin-inline-end:.4rem }
  /* Badge color variants */
  .badge.v1{ background: color-mix(in oklab, var(--primary), #fff 35%); color:#111 }
  .badge.v2{ background: color-mix(in oklab, var(--primary), #fff 15%); color:#111 }
  .badge.v3{ background: color-mix(in oklab, var(--primary), #000 10%); color:#111 }
  /* Glow cards */
  .glow{ position:relative }
  .glow::before{ content:""; position:absolute; inset:-1px; border-radius:16px; padding:1px; background: linear-gradient(135deg, color-mix(in oklab, var(--primary), transparent 0%), color-mix(in oklab, var(--fg), transparent 85%)); -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; pointer-events:none }
  .glow:hover::before{ background: linear-gradient(135deg, var(--primary), color-mix(in oklab, var(--primary), var(--fg) 25%)) }
  /* Hide scrollbar for products carousel track */
  #products-carousel .pc-track{ scrollbar-width: none; -ms-overflow-style: none }
  #products-carousel .pc-track::-webkit-scrollbar{ width:0; height:0; display:none }
  /* Product card with thumbnail */
  .pc-card .thumb{ position:relative; overflow:hidden; border-radius:12px; margin-bottom:.6rem; aspect-ratio:4/3; background: color-mix(in oklab, var(--fg), transparent 92%) }
  .pc-card .thumb img{ width:100%; height:100%; object-fit:cover; transition: transform .35s ease }
  .pc-card:hover .thumb img{ transform: scale(1.06) }
  /* Tilt cards */
  .tilt{ transform-style: preserve-3d; transition: transform .15s ease, box-shadow .2s ease; will-change: transform }
  .tilt:hover{ box-shadow: 0 14px 34px rgba(0,0,0,.14) }
  /* Wave dividers */
  .wave{ line-height:0; height:48px; overflow:hidden }
  .wave svg{ display:block; width:100%; height:48px }
  /* Mobile CTA bar */
  #mobile-cta{ position:fixed; inset:auto 0 12px 0; z-index:70; display:none; justify-content:center }
  #mobile-cta .bar{ display:flex; gap:.5rem; background:color-mix(in oklab, var(--card), transparent 0%); border:1px solid var(--border); box-shadow: 0 8px 24px rgba(0,0,0,.12); padding:.5rem; margin-inline:auto; border-radius:999px }
  @media (max-width:768px){ #mobile-cta{ display:flex } }
  /* Button variants */
  .btn-ghost{ background:transparent; color: var(--fg); border:1px solid color-mix(in oklab, var(--fg), transparent 85%) }
  .btn-subtle{ background: color-mix(in oklab, var(--fg), transparent 94%); color: var(--fg); border:1px solid color-mix(in oklab, var(--fg), transparent 90%) }
  /* Badge micro animation */
  @keyframes popIn{ 0%{ transform: scale(.6); opacity:0 } 60%{ transform: scale(1.08); opacity:1 } 100%{ transform: scale(1) } }
  .value-card .badge, #goals .badge{ animation: popIn .45s ease both }
  /* Dark-mode fine tuning */
  [data-theme="dark"] .section.alt{ background: color-mix(in oklab, var(--bg), var(--fg) 8%) }
  [data-theme="dark"] .card{ border-color: color-mix(in oklab, var(--fg), transparent 80%) }
  .sheen{ position:relative; overflow:hidden }
  .sheen::after{ content:""; position:absolute; inset:0 -150% 0 100%; background:linear-gradient(120deg, transparent 0%, rgba(255,255,255,.22) 40%, transparent 60%); transform:skewX(-20deg); transition:transform .6s ease }
  .sheen:hover::after{ transform: translateX(-140%) skewX(-20deg) }
  #kpis .nums{ display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:1rem }
  #kpis .tile{ text-align:center }
  #kpis .num{ font-weight:900; font-size: clamp(26px,5vw,40px) }
  #kpis .lbl{ color: color-mix(in oklab, var(--fg), transparent 35%) }
  /* Clients marquee */
  #clients .marquee{ display:flex; overflow:hidden; mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); -webkit-mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent) }
  #clients .track{ display:flex; gap:2.5rem; padding-block:.6rem; animation: scrollX 22s linear infinite }
  #clients img{ height:28px; opacity:.7; filter: grayscale(100%); transition: opacity .2s }
  #clients img:hover{ opacity:1 }
  @keyframes scrollX{ from{ transform: translateX(0) } to{ transform: translateX(-50%) } }
  /* Reduced motion */
  @media (prefers-reduced-motion: reduce){
    *{ animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; scroll-behavior:auto !important }
    #clients .track{ animation:none }
  }
  /* Showcase blocks inspired by references */
  .showcase{ background:#fff; color:#111 }
  [data-theme="dark"] .showcase{ background: color-mix(in oklab, var(--bg), #fff 6%); color: var(--fg) }
  .showcase .wrap{ display:grid; grid-template-columns: 1.1fr .9fr; align-items:center; gap:2rem }
  .showcase .media{ position:relative }
  .platform{ position:absolute; inset:auto 10% -6% 10%; height:18px; border-radius:999px; background: radial-gradient(closest-side, rgba(0,0,0,.35), transparent 70%); filter: blur(6px) }
  .device{ width:100%; height:auto; border-radius:18px; box-shadow: 0 30px 80px rgba(0,0,0,.25) }
  .showcase h3{ font-size: clamp(22px,3vw,30px); margin:.4rem 0; color: var(--primary-700) }
  .showcase p{ color: #555 }
  [data-theme="dark"] .showcase p{ color: color-mix(in oklab, var(--fg), transparent 30%) }
  .showcase .actions{ display:flex; gap:.6rem; flex-wrap:wrap }
  @media (max-width:992px){ .showcase .wrap{ grid-template-columns: 1fr; text-align:center } .showcase .actions{ justify-content:center } }
  /* Before/After slider */
  .before-after{ position:relative; max-width:720px; margin-inline:auto; border-radius:16px; overflow:hidden }
  .before-after img{ display:block; width:100%; height:auto; object-fit:cover }
  .before-after .after{ position:absolute; inset:0; clip-path: inset(0 50% 0 0) }
  .before-after input[type=range]{ position:absolute; inset:auto 0 8px 0; width:60%; margin-inline:auto; display:block }
  /* Sticky sub-nav */
  #subnav{ position:sticky; top:0; z-index:60; backdrop-filter:saturate(140%) blur(8px); background: color-mix(in oklab, var(--bg), transparent 10%); border-bottom:1px solid color-mix(in oklab, var(--fg), transparent 88%); display:none }
  #subnav .inner{ display:flex; gap:.8rem; align-items:center; padding:.5rem 1rem; overflow:auto }
  #subnav a{ white-space:nowrap; padding:.35rem .7rem; border-radius:999px; color: inherit; text-decoration:none; border:1px solid transparent }
  #subnav a.active{ border-color: var(--accent); background: color-mix(in oklab, var(--accent), transparent 85%) }
  @media (min-width:768px){ #subnav{ display:block } }
  /* Cursor blob */
  #cursor-blob{ position:fixed; width:160px; height:160px; border-radius:50%; pointer-events:none; background: radial-gradient(closest-side, color-mix(in oklab, var(--accent), transparent 0%), transparent 70%); opacity:.18; transform: translate(-50%,-50%); z-index:20; display:none }
  @media (min-width:1024px){ #cursor-blob{ display:block } }
  /* FAQ */
  #faq .item{ border:1px solid color-mix(in oklab, var(--fg), transparent 85%); border-radius:12px; overflow:hidden }
  #faq summary{ list-style:none; cursor:pointer; padding:12px 14px; font-weight:800 }
  #faq summary::-webkit-details-marker{ display:none }
  #faq .content{ padding:0 14px 14px; color: color-mix(in oklab, var(--fg), transparent 35%) }
  /* Magnetic buttons */
  .magnet{ position:relative; will-change: transform }
  /* Floating FAB */
  #fab{ position:fixed; inset:auto 12px 12px auto; z-index:75; display:flex; flex-direction:column; gap:.5rem }
  #fab a{ width:48px; height:48px; display:grid; place-items:center; border-radius:999px; background: var(--primary); color:#000; box-shadow:0 10px 24px rgba(0,0,0,.18) }
  #fab a.secondary{ background: color-mix(in oklab, var(--fg), transparent 90%) }
  /* News marquee */
  #newsbar{ position:sticky; top:0; z-index:65; background: color-mix(in oklab, var(--accent), transparent 85%); color: inherit; border-bottom:1px solid color-mix(in oklab, var(--fg), transparent 85%); font-weight:700; display:none }
  #newsbar .wrap{ display:flex; gap:1rem; overflow:hidden; padding:.35rem 1rem }
  #newsbar .ticker{ white-space:nowrap; animation: tick 24s linear infinite }
  @keyframes tick{ from{ transform: translateX(0) } to{ transform: translateX(-50%) } }
  /* Values redesign */
  #values .value-grid{ display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:1rem }
  @media (max-width:1024px){ #values .value-grid{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ #values .value-grid{ grid-template-columns: 1fr } }
  #values .value-card{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 85%); border-radius:16px; padding:16px; display:flex; gap:12px; align-items:flex-start; transition: transform .2s ease, box-shadow .2s ease }
  #values .value-card:hover{ transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,.10) }
  #values .icon{ width:44px; height:44px; border-radius:12px; display:grid; place-items:center; background: color-mix(in oklab, var(--primary), #fff 22%); color:#000; flex:0 0 auto }
  #values .icon i{ font-size:20px }
  #values .content strong{ display:block; font-weight:900; margin-bottom:.2rem }
  #values .content p{ margin:0; color: color-mix(in oklab, var(--fg), transparent 35%) }
  /* Lucide icon helpers */
  .icon-inline{ display:inline-flex; align-items:center; margin-inline:.4rem }
  .card .body i.fa-solid, .card .body i.fa-regular, .card .body i.fa-brands{ font-size:18px }
  /* Template-matching styles */
  .tpl-hero{ padding-block: 72px }
  /* Enforce RTL direction for template blocks */
  .tpl-hero, .tpl-tiles, .tpl-about, .tpl-band, .tpl-services, .tpl-comm, .tpl-cta, .tpl-footerband{ direction: rtl }
  .tpl-title{ font-weight:900; font-size: clamp(28px,5vw,44px); line-height:1.15 }
  .tpl-emph{ color: var(--primary) }
  .tpl-lead{ color: color-mix(in oklab, var(--fg), transparent 35%); max-width:60ch }
  .tpl-hero-img{ width:100%; height:auto; border-radius:16px; box-shadow:0 30px 70px rgba(0,0,0,.18) }
  /* Glass overlay (merge S1) */
  .hero-media{ position:relative }
  .glass-card{ position:absolute; top:8%; right:8%; width:min(360px, 78%); background: color-mix(in oklab, var(--bg), transparent 35%); border:1px solid color-mix(in oklab, var(--fg), transparent 80%); border-radius:16px; padding:14px; backdrop-filter: blur(12px); box-shadow: 0 18px 48px rgba(0,0,0,.18) }
  .glass-card h4{ margin:0 0 .5rem; font-weight:900 }
  .glass-card .field{ display:flex; flex-direction:column; gap:.25rem; margin-bottom:.5rem }
  .glass-card input{ width:100%; border:1px solid color-mix(in oklab, var(--fg), transparent 85%); background: color-mix(in oklab, var(--bg), transparent 10%); color: var(--fg); border-radius:10px; padding:.55rem .6rem }
  .hero-actions{ display:flex; gap:.6rem; flex-wrap:wrap; margin-top:.6rem }
  .btn-ghost{ border:1px solid color-mix(in oklab, var(--fg), transparent 78%); background: transparent }
  @media (max-width:1024px){ .glass-card{ position:static; width:100%; margin-top:.8rem } }
  .tpl-tiles{ padding-block: 0 }
  .tiles{ display:grid; grid-template-columns: 1fr 1.5fr 1fr; gap:28px }
  .tiles .tile{ position:relative; padding:24px 22px; min-height:140px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; border-radius:16px; box-shadow: 0 8px 18px rgba(0,0,0,.06); transition: transform .2s ease, box-shadow .2s ease }
  .tiles .tile:hover{ transform: translateY(-6px); box-shadow: 0 16px 36px rgba(0,0,0,.12) }
  .tiles .tile .ico{ width:44px; height:44px; border-radius:12px; display:grid; place-items:center; margin-bottom:.6rem; font-size:18px; background: color-mix(in oklab, var(--primary), #fff 65%); color:#000 }
  .tiles .tile:hover .ico{ transform: scale(1.06) }
  .tiles .tile.dark{ background: linear-gradient(180deg, #0e0f12, #0b0c0f); color:#f6f7f9 }
  [data-theme="dark"] .tiles .tile.dark{ background: linear-gradient(180deg, #0c0d10, #0a0b0e) }
  .tiles .tile.light{ background: color-mix(in oklab, var(--primary), #fff 88%) }
  .tiles .tile h3{ margin:0 0 .35rem; font-weight:900 }
  .tiles .tile p{ margin:0; color: color-mix(in oklab, var(--fg), transparent 35%) }
  /* Featured middle tile */
  .tiles .tile.featured{ min-height:240px; transform: translateY(-12px) }
  .tiles .tile.featured:hover{ transform: translateY(-16px) }
  @media (max-width:1024px){ .tiles{ grid-template-columns: 1fr; gap:12px } .tiles .tile{ border-radius:16px } .tiles .tile.featured{ transform:none } }
  .tpl-about .center{ text-align:center }
  .tpl-about .narrow{ max-width:60ch; margin-inline:auto; color: color-mix(in oklab, var(--fg), transparent 35%) }
  .tpl-band{ background: color-mix(in oklab, var(--primary), transparent 88%) }
  [data-theme="dark"] .tpl-band{ background: color-mix(in oklab, var(--primary), transparent 90%) }
  .band-img{ width:100%; border-radius:16px }
  .tpl-services .center{ text-align:center }
  /* Pastel decorative shape (merge S2) */
  .tpl-services{ position:relative; overflow:hidden }
  .tpl-services::before{ content:""; position:absolute; inset:-20% auto auto -10%; width:42%; height:70%; background: radial-gradient(80% 80% at 50% 50%, color-mix(in oklab, var(--primary), #fff 86%), transparent 70%); filter: blur(10px); opacity:.6; pointer-events:none }
  .tpl-services .icons-grid{ display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:1rem; margin-block:1rem }
  @media (max-width:1024px){ .tpl-services .icons-grid{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ .tpl-services .icons-grid{ grid-template-columns: 1fr } }
  .icon-card{ background: var(--card); border:1px solid color-mix(in oklab, var(--accent, var(--primary)), transparent 70%); border-radius:14px; padding:18px; min-height:160px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center }
  .icon-card:hover{ border-color: color-mix(in oklab, var(--accent, var(--primary)), transparent 40%); box-shadow: 0 10px 26px color-mix(in oklab, var(--accent, var(--primary)), transparent 82%) }
  .icon-card i{ font-size:20px; background: var(--accent, var(--primary)); color: var(--bg); width:36px; height:36px; border-radius:10px; display:grid; place-items:center; margin-inline:auto; margin-bottom:.5rem }
  [data-theme="dark"] .icon-card i{ color: #000 }
  .tpl-comm{ background: color-mix(in oklab, var(--fg), transparent 94%) }
  [data-theme="dark"] .tpl-comm{ background: color-mix(in oklab, var(--fg), transparent 92%) }
  .tpl-cta{ background: color-mix(in oklab, var(--primary), transparent 80%); padding-block:28px }
  .tpl-cta .cta-inner{ display:flex; justify-content:space-between; align-items:center; gap:1rem }
  @media (max-width:768px){ .tpl-cta .cta-inner{ flex-direction:column; text-align:center } }
  .tpl-footerband{ padding-block: 24px; background: var(--bg) }
  .center{ text-align:center }
  .narrow{ max-width:65ch }
  /* Section spacing & separators */
  .section{ padding-block: 48px }
  .section + .section{ position:relative }
  .section + .section::before{ content:""; display:block; height:1px; background: color-mix(in oklab, var(--fg), transparent 85%); margin: 8px 0 20px; border-radius:999px }
  /* Section headers */
  .title-deco{ position:relative; display:inline-block; padding-bottom:.2rem; font-weight:900 }
  .title-deco::after{ content:""; position:absolute; inset:auto 0 -6px auto; height:3px; width:46%; background: color-mix(in oklab, var(--primary), #fff 20%); border-radius:999px }
  /* Buttons polish */
  .btn{ padding:.6rem 1rem; border-radius:10px; font-weight:800 }
  .btn-primary:hover{ filter: saturate(120%) contrast(1.05) }
  .btn-outline{ border:1px solid color-mix(in oklab, var(--fg), transparent 80%) }
  .btn-outline:hover{ background: color-mix(in oklab, var(--fg), transparent 92%) }
  /* KPIs */
  .tpl-kpis .kpi-grid{ display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:16px }
  @media (max-width:1024px){ .tpl-kpis .kpi-grid{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ .tpl-kpis .kpi-grid{ grid-template-columns: 1fr } }
  .kpi{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 88%); border-radius:14px; padding:16px; text-align:center }
  .kpi .num{ font-size: clamp(22px,3.4vw,32px); font-weight:900; display:block }
  .kpi .lbl{ color: color-mix(in oklab, var(--fg), transparent 35%) }
  /* Clients strip */
  .tpl-clients{ background: color-mix(in oklab, var(--fg), transparent 94%) }
  [data-theme="dark"] .tpl-clients{ background: color-mix(in oklab, var(--fg), transparent 92%) }
  .clients-row{ display:grid; grid-template-columns: repeat(6, minmax(0,1fr)); gap:12px; align-items:center }
  @media (max-width:1024px){ .clients-row{ grid-template-columns: repeat(3, 1fr) } }
  @media (max-width:640px){ .clients-row{ grid-template-columns: repeat(2, 1fr) } }
  .client{ background: var(--bg); border:1px dashed color-mix(in oklab, var(--fg), transparent 85%); border-radius:12px; padding:12px; display:grid; place-items:center; transition: transform .2s ease }
  .client img{ max-width:100%; max-height:40px; filter: grayscale(1); opacity:.75; transition: all .2s ease }
  .client:hover{ transform: translateY(-2px) }
  .client:hover img{ filter:none; opacity:1 }
  /* Products (static) */
  .tpl-products .center{ text-align:center }
  .products-grid{ display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:16px }
  @media (max-width:1024px){ .products-grid{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ .products-grid{ grid-template-columns: 1fr } }
  .product-card{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 88%); border-radius:14px; overflow:hidden; display:flex; flex-direction:column }
  .product-card .imgwrap{ position:relative; aspect-ratio: 4/3; background: linear-gradient(180deg, color-mix(in oklab, var(--primary), #fff 88%), transparent) }
  .product-card img{ width:100%; height:100%; object-fit:cover; display:block }
  .product-card .body{ padding:12px 14px; display:flex; flex-direction:column; gap:.35rem }
  .product-card .name{ font-weight:900; margin:0; font-size:1rem }
  .product-card .meta{ color: color-mix(in oklab, var(--fg), transparent 40%); font-size:.9rem }
  .product-card .row{ display:flex; justify-content:space-between; align-items:center; gap:.6rem; margin-top:.3rem }
  .price{ font-weight:900 }
  /* Objectives - Minimal (A) */
  .tpl-objectives{ background: none }
  .obj-head{ text-align:center; margin-bottom:.8rem }
  .obj-head h2{ font-size: clamp(22px,4vw,34px); font-weight:900; margin:0 }
  .obj-grid{ display:grid; grid-template-columns: 1fr 1fr; gap:14px }
  @media (max-width:768px){ .obj-grid{ grid-template-columns: 1fr; gap:10px } }
  .obj-card{ background: var(--bg); border:1px solid color-mix(in oklab, var(--fg), transparent 86%); border-radius:12px; padding:16px 16px }
  .obj-card .txt{ margin:0; font-size:.98rem; line-height:1.7; font-weight:700 }
  .obj-card .num{ position:absolute; inset:auto 10px 10px auto; font-weight:900; color: color-mix(in oklab, var(--primary), #000 10%); font-size:16px }
  /* Testimonials */
  .tpl-testimonials .t-grid{ display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:16px }
  @media (max-width:1024px){ .tpl-testimonials .t-grid{ grid-template-columns: 1fr 1fr } }
  @media (max-width:640px){ .tpl-testimonials .t-grid{ grid-template-columns: 1fr } }
  .t-card{ background: var(--card); border:1px solid color-mix(in oklab, var(--fg), transparent 88%); border-radius:14px; padding:16px; position:relative }
  .t-card .quote{ color: color-mix(in oklab, var(--fg), transparent 35%) }
  .t-card .author{ display:flex; align-items:center; gap:.6rem; margin-top:.8rem; font-weight:800 }
  .t-card .avatar{ width:36px; height:36px; border-radius:50%; background: color-mix(in oklab, var(--primary), #fff 55%) }
  /* Testimonials slider (mobile) */
  @media (max-width:768px){ .tpl-testimonials .t-grid{ display:flex; gap:12px; overflow-x:auto; scroll-snap-type:x mandatory; padding-bottom:4px } .tpl-testimonials .t-card{ flex:0 0 85%; scroll-snap-align:center } }
  /* Reveal on scroll */
  .reveal{ opacity:0; transform: translateY(10px); transition: opacity .45s ease, transform .45s ease }
  .reveal.reveal-in, .reveal.in{ opacity:1; transform:none }
  /* Footer */
  .site-footer{ background: color-mix(in oklab, var(--fg), transparent 94%); padding-block:44px; border-top:1px solid color-mix(in oklab, var(--fg), transparent 88%); box-shadow: inset 0 8px 24px rgba(0,0,0,.035) }
  [data-theme="dark"] .site-footer{ background: color-mix(in oklab, var(--fg), transparent 92%) }
  .site-footer .cols{ display:grid; grid-template-columns: 2fr 1fr 1fr; gap:24px; align-items:start }
  @media (max-width:1024px){ .site-footer .cols{ grid-template-columns: 1fr 1fr; gap:16px } }
  @media (max-width:640px){ .site-footer .cols{ grid-template-columns: 1fr } }
  .site-footer .muted{ color: color-mix(in oklab, var(--fg), transparent 40%) }
  .footer-heading{ font-weight:900; margin:0 0 .4rem }
  .footer-list{ list-style:none; padding:0; margin:.2rem 0 0; display:grid; gap:.25rem }
  .footer-list a{ color: inherit }
  .footer-list a:hover{ color: var(--primary) }
  .site-footer .about p{ margin:.25rem 0 0 }
  .socials{ display:flex; gap:.5rem; margin-top:.6rem }
  .socials a{ width:34px; height:34px; display:grid; place-items:center; border-radius:10px; background: color-mix(in oklab, var(--fg), transparent 92%) }
  .socials a:hover{ background: color-mix(in oklab, var(--primary), #fff 80%) }
</style>
@endsection

@section('content')
  <!-- Scroll Progress Bar -->
  <div class="scroll-progress" aria-hidden="true"><span></span></div>

<!-- Floating action buttons -->
<div id="fab" aria-label="اختصارات">
  <a href="https://wa.me/966503310071" target="_blank" rel="noopener" title="WhatsApp">WA</a>
  <a href="tel:0503310071" class="secondary" title="اتصل">☎</a>
  <div id="cursor-blob" aria-hidden="true"></div>
</div>
  

  <main>
    @php($sectionSettings = data_get($facility->customization_settings ?? [], 'sections', []))
    @php($show = function (string $key, bool $default = true) use ($sectionSettings) {
        return (bool) data_get($sectionSettings, $key, $default);
    });
    @php($variantSettings = data_get($facility->customization_settings ?? [], 'variants', []))
    @php($variant = function (string $key, int $default = 1) use ($variantSettings) {
        $v = (int) data_get($variantSettings, $key, $default);
        return max(1, min(4, $v));
    });
    {{-- Flash messages and validation errors --}}
    <div class="container mt-16">
      @if(session('ok'))
        <div class="alert alert-success mb-12" role="status" aria-live="polite">
          <strong>تم:</strong> {{ session('ok') }}
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger mb-12" role="alert">
          <strong>حدثت أخطاء:</strong>
          <ul class="list-compact">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
    <!-- Template-Matched Layout -->
    @if($show('tpl_hero'))
    @php($tplHeroVariant = $variant('tpl_hero', 1))
    @if($tplHeroVariant === 1)
      <section class="tpl-hero section">
        <div class="container grid-2" style="align-items:center">
          <div>
            <h1 class="tpl-title">{{ $facility->name }}<br><span class="tpl-emph">{{ $facility->meta_description ?? 'نحو تنفيذ احترافي وتسليم موثوق' }}</span></h1>
            <p class="tpl-lead">{{ $facility->description ?? 'نقدّم خدمات متكاملة في المقاولات والتنفيذ وإدارة المشاريع وفق أفضل الممارسات.' }}</p>
            <div class="hero-actions">
              <a href="#contact" class="btn btn-primary magnet">اطلب عرض الآن</a>
              <a href="#services" class="btn btn-subtle magnet">استعرض الخدمات</a>
            </div>
          </div>
          <div class="hero-media">
            <?php $opts = optional($facility->setting)->options ?? []; ?>
            <?php $heroOpt = data_get($opts,'hero_image'); ?>
            <?php $hero = $heroOpt ? asset('storage/'.$heroOpt) : asset('assets/hero-smart-home.jpg'); ?>
            <img src="{{ $hero }}" alt="صورة هيرو" class="tpl-hero-img" onerror="this.onerror=null; this.src='{{ asset('assets/hero-smart-home.jpg') }}'" />
          </div>
        </div>
      </section>
    @elseif($tplHeroVariant === 2)
      <section class="tpl-hero section" style="text-align:center">
        <div class="container" style="max-width: 920px">
          <h1 class="tpl-title">{{ $facility->name }}</h1>
          <p class="tpl-lead">{{ $facility->meta_description ?? 'نحو تنفيذ احترافي وتسليم موثوق' }}</p>
          <p class="narrow" style="margin-inline:auto">{{ $facility->description ?? 'نقدّم خدمات متكاملة في المقاولات والتنفيذ وإدارة المشاريع وفق أفضل الممارسات.' }}</p>
          <div class="hero-actions" style="justify-content:center">
            <a href="#contact" class="btn btn-primary magnet">اطلب عرض الآن</a>
            <a href="#projects" class="btn btn-outline magnet">المشاريع</a>
            <a href="#services" class="btn btn-subtle magnet">الخدمات</a>
          </div>
        </div>
      </section>
    @elseif($tplHeroVariant === 3)
      <section class="tpl-hero section">
        <div class="container" style="display:grid; gap:16px">
          <div>
            <h1 class="tpl-title">{{ $facility->name }} <span class="tpl-emph">{{ $facility->meta_description ?? 'نحو تنفيذ احترافي وتسليم موثوق' }}</span></h1>
            <p class="tpl-lead">{{ $facility->description ?? 'نقدّم خدمات متكاملة في المقاولات والتنفيذ وإدارة المشاريع وفق أفضل الممارسات.' }}</p>
            <div class="hero-actions">
              <a href="#contact" class="btn btn-primary magnet">اطلب عرض الآن</a>
              <a href="#works" class="btn btn-subtle magnet">أعمالنا</a>
            </div>
          </div>
          <div style="display:flex; gap:.6rem; flex-wrap:wrap">
            <span class="badge v1">التزام بالمواعيد</span>
            <span class="badge v2">جودة تنفيذ</span>
            <span class="badge v3">حلول متكاملة</span>
            <span class="badge v1">تسعير واضح</span>
          </div>
        </div>
      </section>
    @else
      <section class="tpl-hero section" style="position:relative; overflow:hidden">
        <div class="container grid-2" style="align-items:center">
          <div>
            <h1 class="tpl-title">{{ $facility->name }}<br><span class="tpl-emph">{{ $facility->meta_description ?? 'نحو تنفيذ احترافي وتسليم موثوق' }}</span></h1>
            <p class="tpl-lead">{{ $facility->description ?? 'نقدّم خدمات متكاملة في المقاولات والتنفيذ وإدارة المشاريع وفق أفضل الممارسات.' }}</p>
            <div class="hero-actions">
              <a href="#contact" class="btn btn-primary magnet">اطلب عرض الآن</a>
              <a href="#services" class="btn btn-subtle magnet">استعرض الخدمات</a>
            </div>
          </div>
          <div class="hero-media">
            <?php $opts = optional($facility->setting)->options ?? []; ?>
            <?php $heroOpt = data_get($opts,'hero_image'); ?>
            <?php $hero = $heroOpt ? asset('storage/'.$heroOpt) : asset('assets/hero-smart-home.jpg'); ?>
            <img src="{{ $hero }}" alt="صورة هيرو" class="tpl-hero-img" style="filter:saturate(1.05) contrast(1.02)" onerror="this.onerror=null; this.src='{{ asset('assets/hero-smart-home.jpg') }}'" />
          </div>
        </div>
      </section>
    @endif
    @endif

    @if($show('projects') && isset($projects) && $projects->count())
    @php($projectsVariant = $variant('projects', 1))
    @if($projectsVariant === 1)
      <section id="projects" class="tpl-projects section reveal">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">مشاريعنا</h2>
            <p class="narrow">نماذج من مشاريعنا المنفذة.</p>
          </div>
          <div class="grid-3" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px">
            @foreach($projects as $project)
              <article class="card hover glow tilt">
                <div class="body">
                  <strong>{{ $project->title }}</strong>
                  @if($project->excerpt)
                    <p class="m-0" style="margin-top:.35rem">{{ $project->excerpt }}</p>
                  @endif
                  <a class="btn-link" href="{{ route('public.facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">عرض المشروع</a>
                </div>
              </article>
            @endforeach
          </div>
          <div style="margin-top:12px; text-align:center">
            <a class="btn btn-outline" href="{{ route('public.facility.site.projects.index', $facility->slug ?? $facility->id) }}">عرض كل المشاريع</a>
          </div>
        </div>
      </section>
    @elseif($projectsVariant === 2)
      <section id="projects" class="tpl-projects section reveal">
        <div class="container">
          <div class="section-title" style="align-items:center">
            <h2 style="margin:0"><span class="title-deco">مشاريعنا</span></h2>
            <div class="chips">
              <a class="btn btn-outline" href="{{ route('public.facility.site.projects.index', $facility->slug ?? $facility->id) }}">عرض الكل</a>
            </div>
          </div>
          <div style="display:grid; gap:12px">
            @foreach($projects as $project)
              <article class="card hover">
                <div class="body" style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap">
                  <div style="min-width:220px">
                    <strong style="display:block">{{ $project->title }}</strong>
                    @if($project->excerpt)
                      <div class="meta" style="margin-top:.25rem">{{ $project->excerpt }}</div>
                    @endif
                  </div>
                  <a class="btn btn-subtle" href="{{ route('public.facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">عرض</a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @elseif($projectsVariant === 3)
      <section id="projects" class="tpl-projects section reveal">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">مشاريعنا</h2>
          </div>
          <div class="grid-3" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px">
            @foreach($projects as $project)
              <article class="card glow tilt">
                <div class="body">
                  <div style="display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:.35rem">
                    <span class="badge v2">مشروع</span>
                    <span class="badge v1">تنفيذ</span>
                  </div>
                  <strong>{{ $project->title }}</strong>
                  @if($project->excerpt)
                    <p class="m-0" style="margin-top:.35rem">{{ $project->excerpt }}</p>
                  @endif
                  <div style="margin-top:.6rem">
                    <a class="btn btn-outline" href="{{ route('public.facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">تفاصيل</a>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @else
      <section id="projects" class="tpl-projects section reveal">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">مشاريعنا</h2>
            <p class="narrow">نستعرض مجموعة من المشاريع التي نفذناها بكفاءة عالية.</p>
          </div>
          <div class="grid-3" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px">
            @foreach($projects as $project)
              <article class="card hover" style="border-left:4px solid var(--primary)">
                <div class="body">
                  <strong>{{ $project->title }}</strong>
                  @if($project->excerpt)
                    <p class="m-0" style="margin-top:.35rem">{{ $project->excerpt }}</p>
                  @endif
                  <a class="btn-link" href="{{ route('public.facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">عرض المشروع</a>
                </div>
              </article>
            @endforeach
          </div>
          <div style="margin-top:12px; text-align:center">
            <a class="btn btn-outline" href="{{ route('public.facility.site.projects.index', $facility->slug ?? $facility->id) }}">عرض كل المشاريع</a>
          </div>
        </div>
      </section>
    @endif
    @endif

    @if($show('works') && isset($products) && $products->count())
    @php($worksVariant = $variant('works', 1))
    @if($worksVariant === 1)
      <section class="tpl-products section reveal" id="works">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">أعمالنا</h2>
          </div>
          <div class="products-grid" style="margin-top:1rem">
            @foreach($products as $product)
              @php($t = $product->translations->first())
              @php($title = $t->title ?? ('منتج #' . $product->id))
              @php($img = $product->main_image ? asset('storage/'.$product->main_image) : asset('assets/products/placeholder.svg'))
              <article class="product-card">
                <div class="imgwrap">
                  <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('assets/products/placeholder.svg') }}'" />
                </div>
                <div class="body">
                  <h3 class="name">{{ $title }}</h3>
                  @if(!empty($t?->description))
                    <div class="meta">{{ \Illuminate\Support\Str::limit($t->description, 80) }}</div>
                  @endif
                  <div class="row">
                    <a class="btn btn-subtle" href="{{ route('public.products.show', $product->id) }}">عرض</a>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @elseif($worksVariant === 2)
      <section class="tpl-products section reveal" id="works">
        <div class="container">
          <div class="section-title" style="align-items:center">
            <h2 style="margin:0"><span class="title-deco">أعمالنا</span></h2>
            <div class="chips">
              <a class="btn btn-outline" href="#contact">اطلب مثلها</a>
            </div>
          </div>
          <div class="products-grid" style="grid-template-columns: repeat(3, minmax(0,1fr)); margin-top:1rem">
            @foreach($products as $product)
              @php($t = $product->translations->first())
              @php($title = $t->title ?? ('منتج #' . $product->id))
              @php($img = $product->main_image ? asset('storage/'.$product->main_image) : asset('assets/products/placeholder.svg'))
              <article class="product-card hover">
                <div class="imgwrap">
                  <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('assets/products/placeholder.svg') }}'" />
                </div>
                <div class="body">
                  <strong>{{ $title }}</strong>
                  <div class="row" style="margin-top:.6rem">
                    <a class="btn btn-subtle" href="{{ route('public.products.show', $product->id) }}">عرض</a>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @elseif($worksVariant === 3)
      <section class="tpl-products section reveal" id="works">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">أعمالنا</h2>
            <p class="narrow">نماذج مختارة من الأعمال المنفذة.</p>
          </div>
          <div style="display:grid; gap:12px; margin-top:1rem">
            @foreach($products as $product)
              @php($t = $product->translations->first())
              @php($title = $t->title ?? ('منتج #' . $product->id))
              @php($img = $product->main_image ? asset('storage/'.$product->main_image) : asset('assets/products/placeholder.svg'))
              <article class="card hover glow tilt">
                <div class="body" style="display:flex; gap:1rem; align-items:center; justify-content:space-between; flex-wrap:wrap">
                  <div style="display:flex; gap:12px; align-items:center">
                    <img src="{{ $img }}" alt="{{ $title }}" style="width:72px;height:54px;object-fit:cover;border-radius:10px" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('assets/products/placeholder.svg') }}'" />
                    <div>
                      <strong style="display:block">{{ $title }}</strong>
                      @if(!empty($t?->description))
                        <div class="meta">{{ \Illuminate\Support\Str::limit($t->description, 70) }}</div>
                      @endif
                    </div>
                  </div>
                  <a class="btn btn-outline" href="{{ route('public.products.show', $product->id) }}">التفاصيل</a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @else
      <section class="tpl-products section reveal" id="works">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">أعمالنا</h2>
          </div>
          <div class="products-grid" style="margin-top:1rem">
            @foreach($products as $product)
              @php($t = $product->translations->first())
              @php($title = $t->title ?? ('منتج #' . $product->id))
              @php($img = $product->main_image ? asset('storage/'.$product->main_image) : asset('assets/products/placeholder.svg'))
              <article class="product-card" style="border-top:4px solid var(--primary)">
                <div class="imgwrap">
                  <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('assets/products/placeholder.svg') }}'" />
                </div>
                <div class="body">
                  <h3 class="name">{{ $title }}</h3>
                  <div class="row">
                    <a class="btn btn-subtle" href="{{ route('public.products.show', $product->id) }}">عرض</a>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif
    @endif

    <!-- KPIs (toggle) -->
    <?php $opts = isset($opts) ? $opts : (optional($facility->setting)->options ?? []); ?>
    @if($show('kpis'))
    <section id="kpis" class="tpl-kpis section reveal">
      <div class="container">
        <div class="kpi-grid">
          <div class="kpi"><span class="num" data-to="10">0</span><span class="lbl">سنوات خبرة</span></div>
          <div class="kpi"><span class="num" data-to="250">0</span><span class="lbl">عميل سعيد</span></div>
          <div class="kpi"><span class="num" data-to="1500">0</span><span class="lbl">طلب مُنجز</span></div>
          <div class="kpi"><span class="num" data-to="24">0</span><span class="lbl">دعم ومتابعة</span></div>
        </div>
      </div>
    </section>
    @endif
    

    @if($show('tiles'))
    <section class="tpl-tiles section reveal">
      <div class="container tiles">
        <article class="tile light">
          <span class="ico"><i class="fa-solid fa-chart-line"></i></span>
          <h3>ابتكار ونمو</h3>
          <p>حلول مناسبة للسوق المحلي ورؤية للتوسّع الإقليمي.</p>
        </article>
        <article class="tile dark featured">
          <span class="ico" style="background: color-mix(in oklab, var(--primary), #fff 35%)"><i class="fa-solid fa-shield-check"></i></span>
          <h3>جودة وموثوقية</h3>
          <p>منتجات معتمدة تضمن إنجاز المشاريع بدقة وكفاءة.</p>
        </article>
        <article class="tile light">
          <span class="ico"><i class="fa-solid fa-receipt"></i></span>
          <h3>بيع بالآجل</h3>
          <p>دفعات ميسرة وخطط سداد مرنة لراحة عملائنا.</p>
        </article>
      </div>
    </section>
    @endif

    @if($show('about'))
    <section class="tpl-about section">
      <div class="container center">
        <h2 class="title-deco">عن الشركة</h2>
        <p class="narrow">نفتخر بتقديم مجموعة متكاملة من أدوات السباكة وأدوات البناء والأدوات الصحية والكهربائية والعدد، ونلتزم بتقديم منتجات عالية الجودة وخدمات موثوقة تسهم في نجاح مشاريع عملائنا وبناء علاقات طويلة الأمد معهم.</p>
        <a href="#services" class="btn btn-outline">اقرأ المزيد</a>
      </div>
    </section>
    @endif

    @if($show('band'))
    <section class="tpl-band section">
      <div class="container grid-2" style="align-items:center">
        <div><img src="/assets/showcase/feature-a.jpg" alt="عرض" class="band-img" loading="lazy" decoding="async" onerror="this.style.display='none'" /></div>
        <div>
          <h3 class="tpl-band-title">حلول متكاملة تواكب احتياجات السوق</h3>
          <p>نحرص على اختيار أفضل العلامات وتقديم ابتكارات تسهّل العمل وتقلل الهدر وتضمن الأمان والكفاءة.</p>
          <a href="#contact" class="btn btn-primary">تواصل الآن</a>
        </div>
      </div>
    </section>
    @endif

    @if($show('services'))
    @php($servicesVariant = $variant('services', 1))
    @php($servicesEnabled = data_get($facility->customization_settings ?? [], 'content.services.enabled', []))
    @php($servicesCatalog = [
      'general_contracting' => ['label' => 'مقاولات عامة', 'description' => 'إدارة وتنفيذ المشاريع من البداية للنهاية بجودة والتزام.'],
      'turnkey' => ['label' => 'تشطيب وتسليم مفتاح', 'description' => 'تشطيب كامل وتسليم جاهز وفق المواصفات المتفق عليها.'],
      'construction' => ['label' => 'أعمال إنشائية', 'description' => 'هيكل خرساني، قواعد، أعمدة، أسقف، ومختلف الأعمال الإنشائية.'],
      'finishing' => ['label' => 'أعمال التشطيب', 'description' => 'لياسة، دهانات، جبس، بلاط، عزل وتشطيبات نهائية.'],
      'mep' => ['label' => 'أعمال كهرباء وميكانيكا (MEP)', 'description' => 'تنفيذ شبكات الكهرباء والميكانيكا والأنظمة المساندة.'],
      'plumbing' => ['label' => 'أعمال السباكة', 'description' => 'تنفيذ التمديدات الصحية وشبكات المياه والصرف.'],
      'electrical' => ['label' => 'أعمال الكهرباء', 'description' => 'تمديدات، لوحات، إنارة، وقواطع وفق معايير السلامة.'],
      'hvac' => ['label' => 'التكييف والتهوية (HVAC)', 'description' => 'توريد وتركيب وصيانة حلول التكييف والتهوية.'],
      'steel' => ['label' => 'الهياكل المعدنية', 'description' => 'تصنيع وتركيب الهياكل المعدنية والسواتر والمستودعات.'],
      'renovation' => ['label' => 'ترميم وتجديد', 'description' => 'رفع كفاءة المباني وتجديدها وتحسين التشطيبات والخدمات.'],
      'interior' => ['label' => 'تصميم وتنفيذ داخلي', 'description' => 'حلول تصميم وتنفيذ داخلي للمكاتب والمنازل والمحلات.'],
      'exterior' => ['label' => 'واجهات خارجية', 'description' => 'تنفيذ واجهات حجر/كلادينج/زجاج حسب الهوية المعمارية.'],
    ])
    @php($enabledServices = [])
    @foreach($servicesCatalog as $key => $item)
      @php($enabledValue = data_get($servicesEnabled, $key, null))
      @php($isEnabled = is_null($enabledValue) ? true : (bool) $enabledValue)
      @if($isEnabled)
        @php($enabledServices[$key] = $item)
      @endif
    @endforeach

    @if($servicesVariant === 1)
      <section id="services" class="tpl-services section reveal">
        <div class="container center">
          <h2 class="title-deco">خدماتنا</h2>
          <p class="narrow">اخترنا لك أبرز خدمات المقاولات والتنفيذ التي نقدمها حسب تخصص المنشأة.</p>
          <div class="icons-grid">
            @foreach($enabledServices as $item)
              <div class="icon-card">
                <i class="fa-solid fa-briefcase"></i>
                <strong>{{ data_get($item, 'label') }}</strong>
                <p>{{ data_get($item, 'description') }}</p>
              </div>
            @endforeach
          </div>
          <a href="#contact" class="btn btn-subtle">اعرف المزيد</a>
        </div>
      </section>
    @elseif($servicesVariant === 2)
      <section id="services" class="tpl-services section reveal">
        <div class="container">
          <div class="section-title" style="align-items:center">
            <h2 style="margin:0"><span class="title-deco">خدماتنا</span></h2>
            <div class="chips"><a href="#contact" class="btn btn-outline">اطلب عرض</a></div>
          </div>
          <div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px">
            @foreach($enabledServices as $item)
              <div class="card hover"><div class="body" style="display:flex; gap:.8rem; align-items:flex-start">
                <i class="fa-solid fa-check" style="margin-top:.15rem"></i>
                <div>
                  <strong style="display:block">{{ data_get($item, 'label') }}</strong>
                  <div class="meta">{{ data_get($item, 'description') }}</div>
                </div>
              </div></div>
            @endforeach
          </div>
        </div>
      </section>
    @elseif($servicesVariant === 3)
      <section id="services" class="tpl-services section reveal">
        <div class="container">
          <div class="grid-2" style="align-items:start">
            <div>
              <h2 class="title-deco">خدماتنا</h2>
              <p class="narrow">خدمات تنفيذ وتطوير للمشاريع السكنية والتجارية حسب المواصفات.</p>
              <a href="#contact" class="btn btn-primary">ابدأ الآن</a>
            </div>
            <div style="display:grid; gap:10px">
              @foreach($enabledServices as $item)
                <div class="card glow tilt"><div class="body">
                  <strong>{{ data_get($item, 'label') }}</strong>
                  <p class="m-0" style="margin-top:.35rem">{{ data_get($item, 'description') }}</p>
                </div></div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
    @else
      <section id="services" class="tpl-services section reveal">
        <div class="container">
          <div class="center">
            <h2 class="title-deco">خدماتنا</h2>
            <p class="narrow">قائمة بالخدمات التي نقدمها — اختر ما يناسب مشروعك.</p>
          </div>
          <div style="display:grid; gap:10px; margin-top:1rem">
            @foreach($enabledServices as $item)
              <details class="item" style="border:1px solid color-mix(in oklab, var(--fg), transparent 85%); border-radius:12px; overflow:hidden; background:var(--card)">
                <summary style="list-style:none; cursor:pointer; padding:12px 14px; font-weight:800">{{ data_get($item, 'label') }}</summary>
                <div class="content" style="padding:0 14px 14px">{{ data_get($item, 'description') }}</div>
              </details>
            @endforeach
          </div>
          <div style="margin-top:12px; text-align:center">
            <a href="#contact" class="btn btn-outline">تواصل معنا</a>
          </div>
        </div>
      </section>
    @endif
    @endif

    <!-- Clients strip (toggle) -->
    @if($show('clients'))
    <section class="tpl-clients section reveal">
      <div class="container">
        <div class="clients-row">
          <div class="client"><img src="/assets/clients/1.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/2.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/3.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/4.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/5.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/6.svg" alt="Client" onerror="this.style.opacity=.35"></div>
        </div>
      </div>
    </section>
    @endif

    @if($show('band'))
    <section class="tpl-comm section">
      <div class="container grid-2" style="align-items:center">
        <div>
          <h3>نسعى للتميّز بخدمة احترافية</h3>
          <p>فريقنا المتخصص يعمل بروح التعاون والالتزام لتحقيق رضا العملاء وبناء علاقات متينة ومستدامة معهم.</p>
          <a href="#contact" class="btn btn-primary">اتصل بنا</a>
        </div>
        <div><img src="/assets/showcase/feature-b.jpg" alt="تواصل" class="band-img" loading="lazy" decoding="async" onerror="this.style.display='none'" /></div>
      </div>
    </section>
    @endif


    <!-- Clients strip duplicate (toggle) -->
    @if($show('clients'))
    <section class="tpl-clients section">
      <div class="container">
        <div class="clients-row">
          <div class="client"><img src="/assets/clients/1.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/2.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/3.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/4.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/5.svg" alt="Client" onerror="this.style.opacity=.35"></div>
          <div class="client"><img src="/assets/clients/6.svg" alt="Client" onerror="this.style.opacity=.35"></div>
        </div>
      </div>
    </section>
    @endif

    <!-- Testimonials -->
    @if($show('testimonials'))
    <section class="tpl-testimonials section">
      <div class="container">
        <h2 class="title-deco">ماذا يقول عملاؤنا</h2>
        <div class="t-grid">
          <article class="t-card">
            <p class="quote">خدمة احترافية وتوريد سريع وجودة ممتازة، تجربة موثوقة نوصي بها.</p>
            <div class="author"><span class="avatar"></span><span>عميل من قطاع المقاولات</span></div>
          </article>
          <article class="t-card">
            <p class="quote">تنوع كبير في المنتجات وأسعار تنافسية، والتزام عالي بالمواعيد.</p>
            <div class="author"><span class="avatar"></span><span>شركة تطوير مشاريع</span></div>
          </article>
          <article class="t-card">
            <p class="quote">التواصل ممتاز وخيارات التقسيط ساعدتنا في سير المشروع بسلاسة.</p>
            <div class="author"><span class="avatar"></span><span>مكتب استشارات هندسية</span></div>
          </article>
        </div>
      </div>
    </section>
    @endif

    @if($show('cta'))
    <section class="tpl-cta">
      <div class="container cta-inner">
        <div>
          <h4>{{ data_get($opts,'cta_title','لنبدأ اليوم!') }}</h4>
          <p>{{ data_get($opts,'cta_subtitle','أخبرنا باحتياجك لنقدّم لك أفضل عرض.') }}</p>
        </div>
        <div class="cta-actions">
          <a href="#contact" class="btn btn-primary">اطلب عرض</a>
          <a href="https://wa.me/966503310071" class="btn btn-outline" target="_blank" rel="noopener">WhatsApp</a>
        </div>
      </div>
    </section>
    @endif

    <!-- New Contact section (toggle) -->
    @if($show('contact'))
    <section id="contact" class="section">
      <div class="container grid-2">
        <div class="aside">
          <h2><span class="title-deco">تواصل معنا</span></h2>
          <p>يسعدنا خدمتك والإجابة على استفساراتك وتقديم أفضل عرض يناسب مشروعك.</p>
          <div class="info-list">
            <div class="info-item"><i class="fa-solid fa-phone"></i><div><strong>الجوال</strong><div><a href="tel:0503310071">0503310071</a></div></div></div>
            <div class="info-item"><i class="fa-brands fa-whatsapp"></i><div><strong>واتساب</strong><div><a href="https://wa.me/966503310071" target="_blank" rel="noopener">راسلنا مباشرة</a></div></div></div>
            <div class="info-item"><i class="fa-regular fa-envelope"></i><div><strong>البريد</strong><div><a href="mailto:tour@tourcons.com">tour@tourcons.com</a></div></div></div>
          </div>
        </div>
        <div class="form-card">
          <form id="contact-form-new" action="{{ route('contact.home.store') }}" method="POST" novalidate>
            @csrf
            <div class="form-grid">
              <div>
                <label for="name">الاسم</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="اسمك الكامل" required class="@error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div>
                <label for="phone">رقم الجوال</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="05xxxxxxxx" required class="@error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="full">
                <label>المنتجات المضافة للطلب</label>
                <div id="selected-products-new" style="display:flex; gap:.4rem; flex-wrap:wrap"></div>
                <div style="margin-top:.4rem">
                  <button type="button" class="btn btn-subtle" onclick="clearSelectedProducts()">تفريغ الطلب</button>
                </div>
                <div id="selected-products-hidden-new"></div>
              </div>
              <div class="full">
                <label for="email">البريد الإلكتروني (اختياري)</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" class="@error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="full">
                <label for="type">فئة الطلب</label>
                <select id="type" name="type" required class="@error('type') is-invalid @enderror">
                  <option value="">اختر الفئة</option>
                  <option {{ old('type')==='أدوات البناء' ? 'selected' : '' }}>أدوات البناء</option>
                  <option {{ old('type')==='أدوات السباكة' ? 'selected' : '' }}>أدوات السباكة</option>
                  <option {{ old('type')==='الأدوات الصحية' ? 'selected' : '' }}>الأدوات الصحية</option>
                  <option {{ old('type')==='الأدوات الكهربائية' ? 'selected' : '' }}>الأدوات الكهربائية</option>
                  <option {{ old('type')==='العدد اليدوية والكهربائية' ? 'selected' : '' }}>العدد اليدوية والكهربائية</option>
                  <option {{ old('type')==='أخرى' ? 'selected' : '' }}>أخرى</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="full">
                <label for="message">رسالتك</label>
                <textarea id="message" name="message" rows="5" placeholder="اكتب تفاصيل طلبك أو استفسارك" required class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div style="margin-top:.8rem; display:flex; gap:.6rem; flex-wrap:wrap">
              <button class="btn btn-primary" type="submit">إرسال</button>
              <a class="btn btn-outline" href="https://wa.me/966503310071" target="_blank" rel="noopener">WhatsApp</a>
            </div>
          </form>
        </div>
      </div>
    </section>

    @endif

    <!-- 1. Hero (Redesigned) -->
    <div id="legacy" hidden>
    @if($show('tpl_hero'))
    <section id="hero" class="section hero">
      <div class="container grid-2">
        <div class="hero-copy">
          <span class="eyebrow">{{ $facility->name }}</span>
          <h1>{{ $facility->meta_description ?? 'حلول متكاملة للمقاولات والتنفيذ' }}</h1>
          <p>{{ $facility->description ?? 'نقدّم خدمات تنفيذ احترافية تُسهم في إنجاز المشاريع بدقة وكفاءة.' }}</p>
          <ul>
            <li class="glow">تنفيذ</li>
            <li class="glow">جودة</li>
            <li class="glow">التزام</li>
            <li class="glow">سلامة</li>
            <li class="glow">تسليم</li>
          </ul>
          <div class="cta">
            <a class="btn btn-primary" href="#services">تعرّف على أعمالنا</a>
            <a class="btn btn-outline" href="#contact">تواصل معنا</a>
          </div>
        </div>
        <div class="hero-media" aria-hidden="true">
          <div class="surface-box">
            <img class="hero-media-img" src="/assets/hero-smart-home.jpg" alt="منزل ذكي — لوحة تحكم وأنظمة متصلة" />
          </div>
        </div>
      </div>
      <div class="blob b1" aria-hidden="true"></div>
      <div class="blob b2" aria-hidden="true"></div>
      <span class="side-label">{{ strtoupper((string) ($facility->slug ?? $facility->name)) }}</span>
    </section>
    @endif
    
    <!-- Sub Navigation -->
    @if($show('tpl_hero'))
    <nav id="subnav" class="fade-in reveal" aria-label="روابط الأقسام">
      <div class="inner">
        <a href="#projects">المشاريع</a>
        <a href="#products-carousel">الأعمال</a>
        <a href="#showcase-a">العروض</a>
        <a href="#goals">أهدافنا</a>
        <a href="#values">قيمنا</a>
        <a href="#contact">تواصل</a>
      </div>
    </nav>
    @endif


    <!-- Why Choose Us (numbered cards) -->
    @if($show('why_choose'))
    <section id="why-choose" class="section alt fade-in reveal">
      <div class="container">
        <h2><span class="title-deco">لماذا تختار {{ $facility->name }}؟</span></h2>
        <div class="grid-4" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem">
          <div class="card hover"><div class="body"><div class="badge">01</div><span class="icon-inline"><i class="fa-solid fa-box-open"></i></span><strong>أفضل تشكيلة</strong><p class="m-0">كل ما تحتاجه من أدوات البناء والسباكة والصحية والكهربائية والعدد.</p></div></div>
          <div class="card hover"><div class="body"><div class="badge">02</div><span class="icon-inline"><i class="fa-solid fa-shield-halved"></i></span><strong>جودة موثوقة</strong><p class="m-0">علامات معروفة ومطابقة للمواصفات.</p></div></div>
          <div class="card hover"><div class="body"><div class="badge">03</div><span class="icon-inline"><i class="fa-solid fa-truck-fast"></i></span><strong>توريد سريع</strong><p class="m-0">توفير المواد مع خدمة توصيل مرنة.</p></div></div>
          <div class="card hover"><div class="body"><div class="badge">04</div><span class="icon-inline"><i class="fa-solid fa-handshake"></i></span><strong>شراكة طويلة</strong><p class="m-0">نضع العميل أولًا ونبني علاقة مستدامة.</p></div></div>
        </div>
      </div>
    </section>
    @endif

    <!-- FAQ -->
    @if($show('faq'))
    <section id="faq" class="section fade-in reveal">
      <div class="container">
        <h2><span class="title-deco">الأسئلة الشائعة</span></h2>
        <details class="item">
          <summary>هل توفّرون البيع بالآجل؟</summary>
          <div class="content">نعم، نوفر خطط دفع مرنة حسب فئة الطلب والكمية بعد دراسة بسيطة لاحتياج العميل.</div>
        </details>
        <details class="item">
          <summary>ما المدن التي تخدمونها؟</summary>
          <div class="content">نغطي معظم مدن المملكة عبر شركاء لوجستيين موثوقين مع خيارات شحن سريع.</div>
        </details>
        <details class="item">
          <summary>هل المنتجات أصلية ومضمونة؟</summary>
          <div class="content">جميع منتجاتنا معتمدة ومطابقة للمواصفات وبضمان مصنّع حيث ينطبق.</div>
        </details>
      </div>
    </section>
    @endif

    <!-- KPIs / Counters -->
    @if($show('kpis'))
    <section id="kpis" class="section fade-in reveal">
      <div class="container">
        <div class="nums">
          <div class="tile card glow tilt sheen"><div class="body">
            <div class="num" data-to="12">0</div>
            <div class="lbl">سنوات خبرة</div>
          </div></div>
          <div class="tile card glow tilt sheen"><div class="body">
            <div class="num" data-to="1200">0</div>
            <div class="lbl">منتج وفئة</div>
          </div></div>
          <div class="tile card glow tilt sheen"><div class="body">
            <div class="num" data-to="850">0</div>
            <div class="lbl">طلبات منجزة</div>
          </div></div>
          <div class="tile card glow tilt sheen"><div class="body">
            <div class="num" data-to="18">0</div>
            <div class="lbl">مدن مخدومة</div>
          </div></div>
        </div>
      </div>
    </section>
    @endif

    <!-- Projects -->
    @if($show('projects') && isset($projects) && $projects->count())
    <section id="projects" class="section fade-in reveal">
      <div class="container">
        <div class="section-title" style="align-items:center">
          <h2 style="margin:0"><span class="title-deco">مشاريعنا</span></h2>
          <div class="chips">
            <a class="btn btn-outline" href="{{ route('public.facility.site.projects.index', $facility->slug ?? $facility->id) }}">عرض الكل</a>
          </div>
        </div>
        <div class="grid-3" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem">
          @foreach($projects as $project)
            <article class="card hover glow tilt">
              <div class="body">
                <strong>{{ $project->title }}</strong>
                @if($project->excerpt)
                  <p class="m-0" style="margin-top:.35rem">{{ $project->excerpt }}</p>
                @endif
                <a class="btn-link" href="{{ route('public.facility.site.projects.show', [$facility->slug ?? $facility->id, $project->slug]) }}">عرض المشروع</a>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    <!-- Products Carousel -->
    @if($show('products_carousel'))
    <section id="products-carousel" class="section fade-in reveal">
      <div class="container">
        <div class="section-title" style="align-items:center">
          <h2 style="margin:0"><span class="title-deco">اكتشف أعمالنا</span></h2>
          <div class="chips">
            <button class="btn btn-outline" id="pc-prev" aria-label="سابق">‹</button>
            <button class="btn" id="pc-next" aria-label="التالي">›</button>
          </div>
        </div>
        <div class="pc-track" id="pc-track" style="display:flex; gap:1rem; overflow:auto; scroll-snap-type:x mandatory; padding-bottom:.5rem">
          @if(isset($products) && $products->count())
            @foreach($products as $product)
              @php($t = $product->translations->first())
              @php($title = $t->title ?? ('منتج #' . $product->id))
              @php($img = $product->main_image ? asset('storage/'.$product->main_image) : asset('assets/products/placeholder.svg'))
              <article class="card hover glow tilt pc-card" style="min-width:280px; scroll-snap-align:start">
                <div class="body">
                  <div class="thumb">
                    <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('assets/products/placeholder.svg') }}'">
                  </div>
                  <strong>{{ $title }}</strong>
                  @if(!empty($t?->description))
                    <p class="m-0">{{ \Illuminate\Support\Str::limit($t->description, 90) }}</p>
                  @endif
                  <a href="{{ route('public.products.show', $product->id) }}" class="btn-link">عرض</a>
                </div>
              </article>
            @endforeach
          @else
            <div class="card" style="min-width:280px"><div class="body">لا توجد منتجات لعرضها حاليًا.</div></div>
          @endif
        </div>
      </div>
    </section>
    @endif

    <!-- Showcase A -->
    @if($show('showcase_a'))
    <section id="showcase-a" class="section showcase fade-in reveal">
      <div class="container wrap">
        <div class="media">
          <div class="before-after" id="beforeAfter">
            <img class="before" src="/assets/showcase/before.jpg" alt="قبل" loading="lazy" onerror="this.src='/assets/showcase/feature-a.jpg'" />
            <img class="after" src="/assets/showcase/after.jpg" alt="بعد" loading="lazy" onerror="this.style.display='none'" />
            <input type="range" min="0" max="100" value="50" aria-label="مقارنة قبل وبعد" />
          </div>
          <div class="platform" aria-hidden="true"></div>
        </div>
        <div class="copy">
          <h3>حلول متكاملة للمشاريع</h3>
          <p>توريد أدوات السباكة والبناء والصحية والكهربائية والعدد بجودة موثوقة وتسليم سريع. خطط دفع مرنة تناسب احتياجاتك.</p>
          <div class="actions">
            <a href="#services" class="btn btn-primary sheen magnet">استكشف أعمالنا</a>
            <a href="#contact" class="btn btn-ghost magnet">اطلب عرض سعر</a>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- Showcase B (inverted layout) -->
    @if($show('showcase_b'))
    <section id="showcase-b" class="section showcase alt fade-in reveal">
      <div class="container wrap" style="direction: rtl">
        <div class="copy">
          <h3>مظهر أنيق وتجربة احترافية</h3>
          <p>واجهات حديثة بتفاصيل مدروسة تعكس هوية العلامة وتمنح المستخدم تجربة تصفح راقية.</p>
          <div class="actions">
            <a href="#clients" class="btn btn-subtle">شاهد شركاءنا</a>
            <a href="#contact" class="btn btn-outline">تواصل معنا</a>
          </div>
        </div>
        <div class="media">
          <img class="device" src="/assets/showcase/feature-b.jpg" alt="عرض بصري أنيق" loading="lazy" onerror="this.style.display='none'" />
          <div class="platform" aria-hidden="true"></div>
        </div>
      </div>
    </section>
    @endif

    <!-- Testimonials (single highlighted) -->
    @if($show('testimony'))
    <section id="testimony" class="section alt fade-in reveal">
      <div class="container grid-2">
        <div>
          <h2><span class="title-deco">ماذا يقول عملاؤنا</span></h2>
          <p class="pro-lead">“تعامل ممتاز وتوريد سريع، المنتجات كانت مطابقة لما نحتاجه وأسعار منافسة. تجربة رائعة.”</p>
          <p class="m-0"><strong>عميل مؤسسة</strong> • قطاع المقاولات</p>
        </div>
        <div class="illustration small" aria-hidden="true">
          <img src="/assets/testimonial.jpg" alt="عميل سعيد" onerror="this.style.display='none'" />
        </div>
      </div>
    </section>
    @endif

    <!-- 4. Services -->
    @if($show('services'))
    <section id="services" class="section services fade-in reveal">
      <div class="container">
        <h2>أعمالنا</h2>
        <div class="grid-3">
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M7 12h10M12 7v10" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">أدوات البناء (Construction Tools)</h4>
            <p>مجموعة واسعة من الأدوات والمعدات عالية الجودة للمشاريع الإنشائية: المطارق، المسامير، المثاقب، المجارف، أجهزة القياس، الرافعات، وغيرها.</p>
            <ul class="list-compact">
              <li>اختيارات متنوعة تناسب المشاريع السكنية والتجارية</li>
              <li>توريد سريع وكميات مرنة</li>
              <li>علامات تجارية موثوقة</li>
            </ul>
            <a href="#contact" class="btn btn-link">اطلب عرض أسعار</a>
          </div>
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 12h10" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">أدوات السباكة (Plumbing Tools)</h4>
            <p>أنابيب، صمامات، محابس، وصلات، وغيرها — منتجات متينة وموثوقة لضمان كفاءة العمل وسلامة المنشآت.</p>
            <ul class="list-compact">
              <li>تشكيلة كاملة من المقاسات والمواد</li>
              <li>مطابقة لمعايير الجودة والسلامة</li>
              <li>دعم فني لاختيار الحلول المناسبة</li>
            </ul>
            <a href="#contact" class="btn btn-link">اطلب عرض أسعار</a>
          </div>
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3l8 4v6c0 4.418-3.582 8-8 8s-8-3.582-8-8V7l8-4z" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">الأدوات الصحية (Sanitary Ware)</h4>
            <p>حلول متكاملة للحمامات والمطابخ: صنابير، مغاسل، أحواض استحمام، دشات، وأنظمة تسخين وتبريد المياه تجمع بين الأناقة والوظيفية.</p>
            <ul class="list-compact">
              <li>تصاميم عصرية وعملية</li>
              <li>مواد عالية التحمل ومقاومة للتكلس</li>
              <li>خيارات اقتصادية وممتازة</li>
            </ul>
            <a href="#contact" class="btn btn-link">اطلب عرض أسعار</a>
          </div>
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 10h16M6 6h12M8 14h8M10 18h4" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">الأدوات الكهربائية (Electrical Tools)</h4>
            <p>أسلاك وكابلات، مفاتيح كهربائية، قواطع، أجهزة تحكم وسلامة، مصابيح ولوحات كهربائية بمعايير أمان وكفاءة عالية.</p>
            <ul class="list-compact">
              <li>مطابقة للمواصفات القياسية</li>
              <li>حلول للإنارة والتوزيع والحماية</li>
              <li>دعم مشاريع سكنية وتجارية وصناعية</li>
            </ul>
            <a href="#contact" class="btn btn-link">اطلب عرض أسعار</a>
          </div>
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 8h8v8H8z" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">العدد اليدوية والكهربائية (Hand & Power Tools)</h4>
            <p>مجموعة متنوعة من العدد اليدوية والكهربائية: مفكات، مثاقب، مناشير، مفاتيح وغيرها — جودة ومتانه لأداء موثوق.</p>
            <ul class="list-compact">
              <li>أداء ثابت في ظروف العمل المختلفة</li>
              <li>خيارات احترافية وهواة</li>
              <li>خدمة ما بعد البيع</li>
            </ul>
            <a href="#contact" class="btn btn-link">اطلب عرض أسعار</a>
          </div>
          <div class="feature">
            <div class="icon" aria-hidden="true" style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-inline-end:6px">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12a5 5 0 1010 0 5 5 0 10-10 0z" stroke="currentColor" stroke-width="1.5"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3" stroke="currentColor" stroke-width="1.5"/></svg>
            </div>
            <h4 style="display:inline">البيع بالآجل ودفعات ميسرة</h4>
            <p>نوفّر إمكانية البيع بالآجل مع خطط سداد ميسّرة لتلبية احتياجاتكم المختلفة بسهولة ومرونة.</p>
            <ul class="list-compact">
              <li>خطط تقسيط مرنة</li>
              <li>إجراءات سهلة وسريعة</li>
              <li>حلول تناسب الأفراد والشركات</li>
            </ul>
            <a href="#contact" class="btn btn-link">استفسر عن الخيارات</a>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- Installments Banner (placed early) -->
    @if($show('installments'))
    <section id="installments" class="section alt fade-in reveal">
      <div class="container">
        <div class="card" style="padding:16px; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap">
          <div>
            <h2 style="margin:.2rem 0">البيع بالآجل والسداد على دفعات ميسّرة</h2>
            <p class="m-0">اختر الخطة المناسبة وابدأ بتجهيز مشروعك اليوم مع خيارات دفع مرنة وسهلة.</p>
          </div>
          <div>
            <a href="#contact" class="btn">اطلب تفاصيل التقسيط</a>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- Why Us -->
    @if($show('why_us'))
    <section id="why-us" class="section fade-in">
      <div class="container">
        <h2>لماذا تختارنا</h2>
        <div class="grid-4">
          <div class="card"><div class="body"><strong>جودة مضمونة</strong><p class="m-0">منتجات موثوقة بمعايير عالية.</p></div></div>
          <div class="card"><div class="body"><strong>توريد سريع</strong><p class="m-0">كميات مرنة وتسليم في الوقت.</p></div></div>
          <div class="card"><div class="body"><strong>تشكيلة واسعة</strong><p class="m-0">كل ما تحتاجه من الأدوات والعدد.</p></div></div>
          <div class="card"><div class="body"><strong>تقسيط ميسر</strong><p class="m-0">خطط دفع تناسب احتياجك.</p></div></div>
        </div>
      </div>
    </section>
    @endif

    <!-- Company Overview -->
    @if($show('about_brief'))
    <section id="about-brief" class="section fade-in reveal">
      <div class="container">
        <h2>عن {{ $facility->name }}</h2>
        <div class="grid-2" style="align-items:start">
          <div>
            <p class="m-0">نؤمن بأن الثقة تُبنى على الجودة والالتزام. نقدم خدمات ومنتجات تساعد عملاءنا على إنجاز مشاريعهم بسلاسة وكفاءة، مع حلول مرنة للدفع.</p>
          </div>
          <div>
            <p class="m-0">نقدّم خدمات متكاملة في المقاولات والتنفيذ وإدارة المشاريع وفق أفضل الممارسات. تلبية احتياجات عملائنا المتنوعة. نسعى لتوفير منتجات عالية الجودة وموثوقة تسهم في إنجاز المشاريع بدقة وكفاءة. كما نتيح لعملائنا <strong>البيع بالآجل والسداد على دفعات ميسّرة</strong>.</p>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- Vision & Mission (2 cols) -->
    @if($show('vision_mission'))
    <section id="vision-mission" class="section alt fade-in reveal">
      <div class="container grid-2">
        <div>
          <h2>رؤيتنا</h2>
          <p>أن نكون الرواد في مجال توفير أدوات السباكة وأدوات البناء والأدوات الصحية والكهربائية والعدد في السوق المحلي والإقليمي، عبر الالتزام بأعلى معايير الجودة والابتكار.</p>
        </div>
        <div>
          <h2>رسالتنا</h2>
          <p>نسعى لتحقيق التميز من خلال الابتكار المستمر وتطوير حلول متكاملة تسهم في إنجاح مشاريع عملائنا. نلتزم بخدمات استثنائية بفريق محترف ومتخصص يعمل بروح التعاون والالتزام.</p>
        </div>
      </div>
    </section>
    @endif

    <!-- Goals (separated, redesigned) -->
    @if($show('goals'))
    <section id="goals" class="section fade-in reveal">
      <div class="container">
        <h2><span class="title-deco">أهدافنا</span></h2>
        <div class="grid-4" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem">
          <div class="card hover glow tilt"><div class="body"><div class="badge">01</div><span class="icon-inline"><i data-lucide="target"></i></span><strong>الريادة</strong><p class="m-0">أن نكون الخيار الأول في مجالات السباكة والبناء والصحية والكهربائية والعدد.</p></div></div>
          <div class="card hover glow tilt"><div class="body"><div class="badge">02</div><span class="icon-inline"><i data-lucide="award"></i></span><strong>الجودة</strong><p class="m-0">تقديم منتجات عالية الجودة وخدمات متميزة بثقة واستمرارية.</p></div></div>
          <div class="card hover glow tilt"><div class="body"><div class="badge">03</div><span class="icon-inline"><i data-lucide="expand"></i></span><strong>التوسّع</strong><p class="m-0">توسيع مجموعة المنتجات لتلبية احتياجات قطاعات السوق المختلفة.</p></div></div>
          <div class="card hover glow tilt"><div class="body"><div class="badge">04</div><span class="icon-inline"><i data-lucide="gauge"></i></span><strong>الكفاءة</strong><p class="m-0">تحسين العمليات الداخلية لضمان جودة عالية وأسعار تنافسية.</p></div></div>
        </div>
      </div>
    </section>
    @endif

    <!-- Values (separated, redesigned) -->
    @if($show('values'))
    <section id="values" class="section alt fade-in reveal">
      <div class="container">
        <h2><span class="title-deco">قيمنا</span></h2>
        <div class="value-grid">
          <div class="value-card">
            <div class="icon"><i data-lucide="shield-check"></i></div>
            <div class="content"><strong>الجودة</strong><p>معايير صارمة ومواد موثوقة.</p></div>
          </div>
          <div class="value-card">
            <div class="icon"><i data-lucide="badge-check"></i></div>
            <div class="content"><strong>الاحترافية</strong><p>التزام بالمواعيد ووضوح في التعامل.</p></div>
          </div>
          <div class="value-card">
            <div class="icon"><i data-lucide="sparkles"></i></div>
            <div class="content"><strong>الابتكار</strong><p>حلول متجددة تناسب احتياجات المشاريع.</p></div>
          </div>
          <div class="value-card">
            <div class="icon"><i data-lucide="heart"></i></div>
            <div class="content"><strong>النزاهة</strong><p>شفافية ومسؤولية في كل تعامل.</p></div>
          </div>
          <div class="value-card">
            <div class="icon"><i data-lucide="timer"></i></div>
            <div class="content"><strong>السرعة</strong><p>توريد وتسليم ضمن الوقت المتفق عليه.</p></div>
          </div>
          <div class="value-card">
            <div class="icon"><i data-lucide="life-buoy"></i></div>
            <div class="content"><strong>دعم ما بعد البيع</strong><p>متابعة فنية وخدمة عملاء سريعة.</p></div>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- 15. Contact CTA -->
    <section id="contact-legacy" class="section contact fade-in reveal">
      <div class="container grid-2">
        <div>
          <h2><span class="title-deco">تواصل معنا</span></h2>
          <p>لنبدأ بتجهيز طلبك اليوم. املأ النموذج وسنعود إليك بعرض مناسب خلال 24 ساعة.</p>
          <form id="contact-form" action="{{ route('contact.home.store') }}" method="POST" novalidate>
            @csrf
            <div class="form-grid">
              <label>
                الاسم الكامل
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="الاسم" class="@error('name') is-invalid @enderror" />
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </label>
              <label>
                رقم الجوال
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="05XXXXXXXX" class="@error('phone') is-invalid @enderror" />
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </label>
              <label class="full">
                البريد الإلكتروني (اختياري)
                <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" class="@error('email') is-invalid @enderror" />
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </label>
              <label class="full">
                فئة الطلب
                <select name="type" required class="@error('type') is-invalid @enderror">
                  <option value="">اختر الفئة</option>
                  <option {{ old('type')==='أدوات البناء' ? 'selected' : '' }}>أدوات البناء</option>
                  <option {{ old('type')==='أدوات السباكة' ? 'selected' : '' }}>أدوات السباكة</option>
                  <option {{ old('type')==='الأدوات الصحية' ? 'selected' : '' }}>الأدوات الصحية</option>
                  <option {{ old('type')==='الأدوات الكهربائية' ? 'selected' : '' }}>الأدوات الكهربائية</option>
                  <option {{ old('type')==='العدد اليدوية والكهربائية' ? 'selected' : '' }}>العدد اليدوية والكهربائية</option>
                  <option {{ old('type')==='أخرى' ? 'selected' : '' }}>أخرى</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </label>
              <div class="full">
                <label>المنتجات المضافة للطلب</label>
                <div id="selected-products-legacy" style="display:flex; gap:.4rem; flex-wrap:wrap"></div>
                <div style="margin-top:.4rem">
                  <button type="button" class="btn btn-subtle" onclick="clearSelectedProducts()">تفريغ الطلب</button>
                </div>
                <div id="selected-products-hidden-legacy"></div>
              </div>
              <label class="full">
                وصف مختصر
                <textarea name="message" rows="4" placeholder="اخبرنا عن احتياجك..." class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </label>
            </div>
            <button type="submit" class="btn btn-primary">إرسال الطلب</button>
            <?php $hasSettings = \Illuminate\Support\Facades\Schema::hasTable('settings'); ?>
            <?php $wa = $hasSettings ? \App\Models\Setting::getValue('whatsapp_number') : null; ?>
          </form>
        </div>
        <div>
          <div class="grid-3">
            <div class="card"><div class="body"><strong>السجل التجاري</strong><p class="m-0">1010851048</p></div></div>
            <div class="card"><div class="body"><strong>البريد</strong><p class="m-0"><a href="mailto:tour@tourcons.com">tour@tourcons.com</a></p></div></div>
            <div class="card"><div class="body"><strong>الجوال</strong><p class="m-0"><a href="tel:0503310071">0503310071</a></p></div></div>
          </div>
          <div class="card mt-1"><div class="body"><strong>الموقع الإلكتروني</strong><p class="m-0"><a href="https://touralbina.com/" target="_blank" rel="noopener">https://touralbina.com/</a></p></div></div>
        </div>
      </div>
    </section>
    </div>
  </main>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const track = document.getElementById('pc-track');
    const prev = document.getElementById('pc-prev');
    const next = document.getElementById('pc-next');
    if(track && prev && next){
      const step = 320;
      prev.addEventListener('click', () => track.scrollBy({ left: -step, behavior: 'smooth' }));
      next.addEventListener('click', () => track.scrollBy({ left: step, behavior: 'smooth' }));
    }
    // Reveal on scroll
    const ro = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{
        if(e.isIntersecting){ e.target.classList.add('in'); ro.unobserve(e.target); }
      });
    }, { threshold: .12 });
    document.querySelectorAll('.reveal').forEach(el=>ro.observe(el));
    // Tilt on hover (pointer devices)
    const tilts = Array.from(document.querySelectorAll('.tilt'));
    const max = 10;
    tilts.forEach(card=>{
      card.addEventListener('mousemove', (ev)=>{
        const r = card.getBoundingClientRect();
        const x = ev.clientX - r.left; const y = ev.clientY - r.top;
        const rx = ((y / r.height) - .5) * -2 * max;
        const ry = ((x / r.width) - .5) * 2 * max;
        card.style.transform = `perspective(800px) rotateX(${rx}deg) rotateY(${ry}deg)`;
      });
      card.addEventListener('mouseleave', ()=>{
        card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg)';
      });
    });
    // KPI counters
    const kpiNums = Array.from(document.querySelectorAll('#kpis .num'));
    if(kpiNums.length){
      const once = new Set();
      const ko = new IntersectionObserver((entries)=>{
        entries.forEach(en=>{
          if(en.isIntersecting){
            const el = en.target; if(once.has(el)) return; once.add(el);
            const to = parseInt(el.getAttribute('data-to')||'0',10);
            const dur = 1200; const t0 = performance.now();
            const tick = (now)=>{
              const p = Math.min(1, (now - t0)/dur);
              const val = Math.floor(to * (1 - Math.pow(1-p, 3)));
              el.textContent = val.toLocaleString('ar-SA');
              if(p<1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
            ko.unobserve(el);
          }
        });
      }, { threshold: .3 });
      kpiNums.forEach(n=>ko.observe(n));
    }
    // Mobile CTA
    const ctabar = document.getElementById('mobile-cta');
    if(ctabar){ setTimeout(()=> ctabar.hidden = false, 600); }
    // Sticky subnav active state + accent updates
    const subnav = document.getElementById('subnav');
    const links = subnav ? Array.from(subnav.querySelectorAll('a')) : [];
    const sections = links.map(a=>document.querySelector(a.getAttribute('href'))).filter(Boolean);
    const so = new IntersectionObserver((ents)=>{
      ents.forEach(en=>{
        if(en.isIntersecting){
          const id = '#' + en.target.id;
          links.forEach(l=>l.classList.toggle('active', l.getAttribute('href')===id));
          document.documentElement.style.setProperty('--accent', getComputedStyle(document.documentElement).getPropertyValue('--primary'));
        }
      })
    }, { threshold:.4 });
    sections.forEach(s=>so.observe(s));
    // Cursor blob follow
    const blob = document.getElementById('cursor-blob');
    if(blob){
      window.addEventListener('pointermove', (e)=>{
        blob.style.left = e.clientX + 'px';
        blob.style.top = e.clientY + 'px';
      }, { passive:true });
    }
    // Magnetic buttons
    document.querySelectorAll('.magnet').forEach(btn=>{
      btn.addEventListener('mousemove', (ev)=>{
        const r = btn.getBoundingClientRect();
        const dx = (ev.clientX - (r.left + r.width/2)) / r.width;
        const dy = (ev.clientY - (r.top + r.height/2)) / r.height;
        btn.style.transform = `translate(${dx*6}px, ${dy*6}px)`;
      });
      btn.addEventListener('mouseleave', ()=>{ btn.style.transform = 'translate(0,0)'; });
    });
    // Before/After slider control
    const ba = document.querySelector('#beforeAfter');
    if(ba){
      const range = ba.querySelector('input[type=range]');
      const after = ba.querySelector('.after');
      const update = ()=>{ const v = +range.value; after.style.clipPath = `inset(0 ${100-v}% 0 0)`; };
      range.addEventListener('input', update); update();
    }
    // Show newsbar when JS ready
    const nb = document.getElementById('newsbar'); if(nb){ nb.style.display = 'block'; }
    // Lucide icons init
    if(window.lucide && typeof window.lucide.createIcons === 'function'){
      window.lucide.createIcons();
    }
    // Selected products sync (localStorage -> forms) with quantities
    try{
      const KEY = 'selected_products';
      const spNew = document.getElementById('selected-products-new');
      const spLegacy = document.getElementById('selected-products-legacy');
      const hidNew = document.getElementById('selected-products-hidden-new');
      const hidLegacy = document.getElementById('selected-products-hidden-legacy');
      function getCart(){
        try{ const v = JSON.parse(localStorage.getItem(KEY)||'[]'); return Array.isArray(v)? v.map(o=>({name:o.name||o, qty: parseInt(o.qty||1,10)||1})) : []; }catch{ return []; }
      }
      function setCart(arr){ localStorage.setItem(KEY, JSON.stringify(arr)); window.dispatchEvent(new StorageEvent('storage', { key: KEY })); }
      function incByName(name, d){ const a=getCart(); const i=a.findIndex(x=>x.name===name); if(i>=0){ a[i].qty = Math.max(1, Math.min(9999, (a[i].qty||1)+d)); if(a[i].qty===0){ a.splice(i,1); } setCart(a); render(); } }
      function removeAt(i){ const a=getCart(); a.splice(i,1); setCart(a); render(); }
      // Import product from query (?product=...)
      (function(){
        const qs = new URLSearchParams(location.search);
        const qp = qs.get('product');
        if(qp){
          const a = getCart(); const i = a.findIndex(x=>x.name===qp);
          if(i>=0){ a[i].qty = Math.min(9999, (a[i].qty||1)+1); }
          else{ a.push({ name: qp, qty: 1 }); }
          setCart(a);
        }
      })();
      function chipEl(item, i){
        const span = document.createElement('span');
        span.style.cssText = 'display:inline-flex;gap:.4rem;align-items:center;padding:.25rem .5rem;border:1px solid #ddd;border-radius:999px;background:var(--card)';
        const txt = document.createElement('strong'); txt.textContent = item.name; txt.style.fontWeight='800'; txt.style.fontSize='.88rem';
        const minus = document.createElement('button'); minus.type='button'; minus.textContent='−'; minus.title='إنقاص'; minus.style.cssText='border:1px solid #ddd;background:#fff;border-radius:6px;width:24px;height:24px;cursor:pointer';
        const qty = document.createElement('span'); qty.textContent = item.qty; qty.style.cssText='min-width:22px;text-align:center;font-weight:900';
        const plus = document.createElement('button'); plus.type='button'; plus.textContent='+'; plus.title='زيادة'; plus.style.cssText='border:1px solid #ddd;background:#fff;border-radius:6px;width:24px;height:24px;cursor:pointer';
        const x = document.createElement('button'); x.type='button'; x.textContent='×'; x.title='إزالة'; x.style.cssText='border:none;background:transparent;cursor:pointer;font-weight:900';
        minus.addEventListener('click', ()=> incByName(item.name, -1));
        plus.addEventListener('click', ()=> incByName(item.name, +1));
        x.addEventListener('click', ()=> removeAt(i));
        span.appendChild(txt); span.appendChild(minus); span.appendChild(qty); span.appendChild(plus); span.appendChild(x);
        return span;
      }
      function render(){
        const arr = getCart();
        if(spNew){ spNew.innerHTML=''; arr.forEach((it,i)=> spNew.appendChild(chipEl(it,i))); }
        if(spLegacy){ spLegacy.innerHTML=''; arr.forEach((it,i)=> spLegacy.appendChild(chipEl(it,i))); }
        // hidden inputs aligned
        function makeHidden(container){
          if(!container) return; container.innerHTML='';
          arr.forEach(it=>{
            const p = document.createElement('input'); p.type='hidden'; p.name='products[]'; p.value=it.name; container.appendChild(p);
            const q = document.createElement('input'); q.type='hidden'; q.name='qty[]'; q.value=it.qty; container.appendChild(q);
          });
        }
        makeHidden(hidNew); makeHidden(hidLegacy);
      }
      // Public helper for featured grid
      window.addFeatured = function(name){
        const a = getCart();
        const i = a.findIndex(x=>x.name===name);
        if(i>=0){ a[i].qty = Math.min(9999, (a[i].qty||1)+1); }
        else{ a.push({ name, qty: 1 }); }
        setCart(a); render();
        try{ const t=document.createElement('div'); t.textContent='تمت إضافة المنتج إلى الطلب'; t.style.cssText='position:fixed;inset:auto 50% 24px auto;transform:translateX(-50%);background:#111;color:#fff;padding:.5rem .8rem;border-radius:999px;z-index:90;'; document.body.appendChild(t); setTimeout(()=>t.remove(),1200);}catch{}
      }
      window.clearSelectedProducts = function(){ setCart([]); render(); };
      render();
      ['contact-form-new','contact-form'].forEach(id=>{ const f=document.getElementById(id); if(!f) return; f.addEventListener('submit', ()=> render()); });
    }catch(e){ console.warn('selected products init failed', e); }
  });
</script>

<!-- Mobile sticky CTA bar -->
<div id="mobile-cta" hidden>
  <div class="bar">
    <a class="btn" href="tel:0503310071">اتصل الآن</a>
    <a class="btn btn-outline" href="https://wa.me/966503310071" target="_blank" rel="noopener">WhatsApp</a>
    <a class="btn btn-outline" href="#contact">اطلب عرض</a>
  </div>
</div>
