<!DOCTYPE html>
@php($isArabic = app()->getLocale() === 'ar')
<html lang="{{ app()->getLocale() }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($cmsSetting->site_name ?? 'Azure Luxury') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root{--bg:#050505;--panel:#0f1114;--panel-2:#15181d;--surface:#1b2028;--surface-2:#222831;--text:#f4efe6;--muted:#aa9f90;--line:rgba(255,255,255,.08);--gold:#c9a46a;--gold-soft:#dfc08a;--shadow:0 18px 45px rgba(0,0,0,.45);--radius-xl:34px;--radius-lg:24px;--radius-md:16px}
        *{box-sizing:border-box} html{scroll-behavior:smooth} body{margin:0;color:var(--text);background:radial-gradient(circle at top left, rgba(201,164,106,.16), transparent 24%),radial-gradient(circle at bottom right, rgba(90,120,110,.12), transparent 22%),linear-gradient(180deg,#090909 0%,#0b0d10 48%,#060707 100%);font-family:"Manrope",sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
        a{text-decoration:none;color:inherit} img,video{max-width:100%;display:block} button,input,select,textarea{font:inherit}
        .page-shell{max-width:1380px;margin:0 auto;padding:18px 20px 56px}
        .site-header{position:sticky;top:0;z-index:20;margin-bottom:18px;backdrop-filter:blur(16px);background:rgba(8,9,11,.76);border:1px solid var(--line);border-radius:30px;box-shadow:var(--shadow)}
        .site-header-inner{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:18px;padding:14px 22px}
        .header-links,.header-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap}.header-actions{justify-content:flex-end}
        .menu-toggle-btn{width:46px;height:46px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.03);color:var(--text);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:.22s ease}
        .menu-toggle-btn:hover{border-color:rgba(201,164,106,.5);transform:translateY(-1px)}
        .menu-toggle-btn i{font-size:18px}
        .cart-icon{width:46px;height:46px;padding:0;display:inline-flex;align-items:center;justify-content:center}
        .cart-icon i{font-size:17px}
        .menu-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.56);backdrop-filter:blur(6px);z-index:35}
        .menu-drawer{position:fixed;top:20px;left:20px;bottom:20px;width:min(88vw,340px);background:linear-gradient(180deg, rgba(12,13,15,.98), rgba(9,10,12,.98));border:1px solid var(--line);border-radius:28px;box-shadow:var(--shadow);z-index:40;overflow:auto}
        .menu-drawer-inner{padding:18px}
        .menu-drawer-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:6px 4px 18px;border-bottom:1px solid var(--line);margin-bottom:14px}
        .menu-drawer-brand small{display:block;letter-spacing:.42em;text-transform:uppercase;color:var(--muted);font-size:11px;margin-bottom:4px}
        .menu-drawer-brand strong{display:block;font-family:"Cormorant Garamond",serif;font-size:30px;letter-spacing:.08em;color:#fff}
        .drawer-close{width:42px;height:42px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.03);color:var(--text);display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
        .drawer-links{display:grid;gap:10px}
        .drawer-link{display:flex;align-items:center;gap:12px;padding:13px 14px;border-radius:18px;border:1px solid var(--line);background:rgba(255,255,255,.03);color:var(--text);transition:.22s ease}
        .drawer-link:hover{border-color:rgba(201,164,106,.5);transform:translateY(-1px)}
        .drawer-link i{width:18px;text-align:center;color:var(--gold-soft)}
        .nav-link,.icon-pill,.sign-btn,.gold-btn,.ghost-btn,.qty-btn{border:1px solid var(--line);border-radius:999px;background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));color:var(--text);transition:.22s ease}
        .nav-link,.sign-btn,.icon-pill,.ghost-btn,.gold-btn,.qty-btn{padding:11px 16px}.nav-link:hover,.icon-pill:hover,.sign-btn:hover,.ghost-btn:hover,.qty-btn:hover{border-color:rgba(201,164,106,.5);transform:translateY(-1px)}
        .gold-btn{background:linear-gradient(135deg,var(--gold) 0%,#e6c892 100%);color:#1b140b;border-color:transparent;box-shadow:0 12px 24px rgba(201,164,106,.18)}
        .ghost-btn{background:rgba(255,255,255,.03)}
        .brand-mark{text-align:center}.brand-mark small{display:block;letter-spacing:.42em;text-transform:uppercase;color:var(--muted);font-size:11px;margin-bottom:4px}.brand-mark strong{display:block;font-family:"Cormorant Garamond",serif;font-size:34px;letter-spacing:.08em;color:#fff}
        .muted{color:var(--muted)} .eyebrow{text-transform:uppercase;letter-spacing:.26em;color:var(--gold-soft);font-size:12px}
        .display-title,.section-title{font-family:"Cormorant Garamond",serif;font-weight:600;line-height:.95;margin:0}.display-title{font-size:76px}.section-title{font-size:42px}
        .video-stage{position:relative;min-height:520px;margin-bottom:22px;border-radius:0;overflow:hidden;background:#030303;border:1px solid var(--line);box-shadow:var(--shadow)}
        .video-frame{position:absolute;inset:0}.video-frame iframe,.video-frame video{width:100%;height:100%;border:0;object-fit:cover;display:block}.video-stage::after{content:"";position:absolute;inset:auto 0 0;height:45%;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.88));pointer-events:none}
        .home-category-strip{position:absolute;left:0;right:0;bottom:14px;z-index:2;display:flex;justify-content:center;gap:18px;overflow-x:auto;padding:0 22px 8px;scrollbar-width:thin}.home-category-item{width:104px;flex:0 0 104px;text-align:center;color:#fff}.home-category-thumb{width:86px;height:86px;margin:0 auto 8px;border-radius:50%;display:block;overflow:hidden;border:1px solid rgba(255,255,255,.25);background:#070707;box-shadow:0 10px 24px rgba(0,0,0,.38)}.home-category-thumb img{width:100%;height:100%;object-fit:cover}.home-category-name{display:block;font-size:12px;font-weight:700;line-height:1.2;text-shadow:0 2px 8px rgba(0,0,0,.9)}
        .hero{display:grid;grid-template-columns:minmax(0,1.28fr) minmax(300px,.72fr);gap:22px;align-items:stretch}.moved-collection{margin-top:18px}
        .hero-banner,.hero-side,.promo-banner,.category-spotlight,.category-card,.feature-card,.product-card,.surface,.cart-card,.spec-table,.summary-card,.checkout-card{box-shadow:var(--shadow)}
        .hero-banner,.hero-side{border-radius:var(--radius-xl);overflow:hidden;position:relative;min-height:500px;background:linear-gradient(180deg,#101317,#070809)}
        .hero-banner::after,.promo-banner::after,.hero-video::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg, rgba(5,5,5,.88) 0%, rgba(5,5,5,.62) 40%, rgba(5,5,5,.18) 100%)}
        .hero-banner > img{position:absolute;inset:0}.hero-banner img,.hero-side img,.promo-banner img,.category-spotlight img,.hero-video video{width:100%;height:100%;object-fit:cover}
        .hero-video{position:absolute;inset:0}
        .hero-video video{filter:saturate(.9) contrast(1.02) brightness(.72)}
        .hero-copy,.promo-copy{position:absolute;inset:0;z-index:1;display:flex;flex-direction:column;justify-content:center;padding:44px;max-width:54%}
        .hero-copy p,.section-copy{color:#c2b7a7;line-height:1.75}
        .hero-side::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.08),rgba(0,0,0,.58))}
        .hero-side-copy{position:absolute;left:28px;right:28px;bottom:28px;z-index:1;color:#fff}
        .hero-side-copy h2,.promo-copy h2{font-family:"Cormorant Garamond",serif;font-size:54px;margin:0 0 8px;line-height:.92}
        .toolbar{margin:22px 0;display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap}
        .toolbar.has-bg{position:relative;padding:34px 26px;border-radius:var(--radius-lg);overflow:hidden;background-size:cover;background-position:center;box-shadow:var(--shadow)}.toolbar.has-bg::before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(5,5,5,.82),rgba(5,5,5,.5))}.toolbar.has-bg > *{position:relative;z-index:1}
        .category-strip.has-bg{position:relative;padding:26px;border-radius:var(--radius-lg);background-size:cover;background-position:center}.category-strip.has-bg::before{content:"";position:absolute;inset:0;border-radius:var(--radius-lg);background:rgba(5,5,5,.62)}.category-strip.has-bg > *{position:relative;z-index:1}
        .filter-form{display:flex;gap:12px;flex-wrap:wrap;align-items:center;background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:999px;padding:10px;box-shadow:var(--shadow)}
        .filter-input,.filter-select{min-width:170px;border:none;outline:none;background:transparent;padding:10px 12px;color:var(--text)}
        .category-strip,.feature-strip,.product-grid,.mini-grid,.checkout-layout,.detail-layout,.cart-layout{display:grid;gap:18px}
        .category-strip{grid-template-columns:repeat(6,minmax(0,1fr))}.feature-strip{grid-template-columns:repeat(4,minmax(0,1fr))}.product-grid{grid-template-columns:repeat(4,minmax(0,1fr))}.mini-grid{grid-template-columns:repeat(8,minmax(180px,1fr));overflow-x:auto;padding-bottom:8px}
        .category-card,.feature-card,.product-card,.surface,.cart-card,.spec-table,.summary-card,.checkout-card{background:linear-gradient(180deg,var(--panel),var(--panel-2));border:1px solid var(--line);border-radius:var(--radius-lg)}
        .category-card{overflow:hidden;min-height:190px;position:relative}.category-card img{width:100%;height:100%;object-fit:cover;filter:contrast(1.03) brightness(.82)}.category-card span{position:absolute;left:14px;right:14px;bottom:14px;padding:10px 12px;border-radius:999px;background:rgba(10,11,12,.82);border:1px solid var(--line);font-weight:700;font-size:13px}
        .section-block{margin-top:32px}.section-head{display:flex;justify-content:space-between;align-items:end;gap:16px;flex-wrap:wrap;margin-bottom:16px}.section-head p{margin:6px 0 0}
        .product-card{overflow:hidden}.product-thumb{position:relative;background:linear-gradient(180deg,#0f1216,#161b20);aspect-ratio:1/1;overflow:hidden}.product-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .35s ease, opacity .35s ease}.product-card:hover .product-thumb img{transform:scale(1.04)} .product-thumb .hover-image{position:absolute;inset:0;opacity:0}.product-card:hover .product-thumb .hover-image{opacity:1}
        .wishlist-badge{position:absolute;top:14px;right:14px;width:36px;height:36px;border-radius:50%;display:grid;place-items:center;background:rgba(7,8,10,.88);border:1px solid var(--line);color:var(--gold-soft);font-size:16px}
        .product-thumb-caption{position:absolute;left:0;right:0;bottom:0;z-index:3;padding:30px 14px 12px;color:#fff;text-shadow:0 1px 3px rgba(0,0,0,.7);background:linear-gradient(180deg,rgba(7,8,10,0) 0%,rgba(7,8,10,.82) 100%);pointer-events:none;display:flex;flex-direction:column;gap:3px}
        .product-thumb-caption .thumb-name{font-size:15px;font-weight:600;line-height:1.25;letter-spacing:.2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .product-thumb-caption .thumb-price{font-size:14px;font-weight:700;color:var(--gold-soft,#e6c78a)}
        .product-body{padding:16px 16px 18px}.product-body h3{margin:0 0 6px;font-size:16px}.product-meta{display:flex;justify-content:space-between;gap:14px;color:var(--muted);font-size:13px}.price-row{display:flex;align-items:end;gap:8px;margin:14px 0 16px}.price-row strong{font-size:24px}
        .product-actions{display:flex;gap:10px;align-items:center}.product-actions form{display:flex;gap:10px;align-items:center;width:100%}
        .qty-pill{display:flex;align-items:center;justify-content:center;min-width:58px;padding:10px 12px;border:1px solid var(--line);border-radius:999px;background:var(--surface)}
        .feature-card{padding:18px;display:flex;gap:14px;align-items:flex-start}.feature-icon{width:46px;height:46px;border-radius:16px;display:grid;place-items:center;background:linear-gradient(180deg,#2a2218,#17150f);color:var(--gold-soft);font-size:20px;flex:0 0 auto}
        .promo-banner,.category-spotlight{border-radius:var(--radius-xl);overflow:hidden;position:relative;min-height:280px;margin-top:28px}.promo-banner img,.category-spotlight img{position:absolute;inset:0;filter:brightness(.75)}.promo-copy{max-width:44%;justify-content:center}
        .category-spotlight::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(5,5,5,.92) 0%,rgba(5,5,5,.72) 46%,rgba(5,5,5,.12) 100%)}.category-spotlight .promo-copy h2{font-size:62px}
        .detail-layout{grid-template-columns:minmax(0,1.05fr) minmax(360px,.95fr);align-items:start}.detail-gallery{display:grid;grid-template-columns:128px minmax(0,1fr);gap:16px}.thumb-list{display:grid;gap:14px}.thumb-button,.main-visual,.summary-card,.checkout-card,.cart-card{overflow:hidden}
        .thumb-button{border:1px solid var(--line);border-radius:20px;background:var(--surface);padding:0;cursor:pointer}.thumb-button.active{border-color:var(--gold);box-shadow:0 0 0 2px rgba(201,164,106,.18)}.thumb-button img,.main-visual img{width:100%;height:100%;object-fit:cover}.thumb-button img{aspect-ratio:1/1}
        .main-visual{border:1px solid var(--line);border-radius:28px;background:linear-gradient(180deg,#0f1114,#14181d);min-height:540px}.detail-panel,.cart-card,.summary-card,.checkout-card{padding:24px}.detail-panel h1{font-size:58px;margin:10px 0 12px}.detail-info-line{display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap}.detail-price{font-size:54px;color:var(--gold-soft);font-family:"Cormorant Garamond",serif}
        .note-box,.field,.promo-input{width:100%;border:1px solid var(--line);background:rgba(255,255,255,.03);color:var(--text);border-radius:16px;padding:15px 16px;outline:none}.note-box{min-height:96px;resize:vertical}
        .social-row,.breadcrumb{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.social-dot{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;border:1px solid var(--line);background:rgba(255,255,255,.03)}
        .spec-table{overflow:hidden}.spec-row{display:grid;grid-template-columns:240px 1fr;border-top:1px solid var(--line)}.spec-row:first-child{border-top:none}.spec-row div{padding:16px 22px}.spec-row div:first-child{background:rgba(255,255,255,.03);font-weight:700}
        .cart-layout,.checkout-layout{grid-template-columns:minmax(0,1.2fr) minmax(340px,.8fr);align-items:start}.cart-item{display:grid;grid-template-columns:100px minmax(0,1fr) auto;gap:18px;align-items:center;padding:18px 0;border-top:1px solid var(--line)}.cart-item:first-child{border-top:none;padding-top:0}.cart-thumb{width:100px;height:100px;border-radius:22px;overflow:hidden;background:#101419}.cart-thumb img{width:100%;height:100%;object-fit:cover}.qty-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.qty-input{width:74px;padding:11px 12px;border:1px solid var(--line);border-radius:999px;text-align:center;background:rgba(255,255,255,.03);color:var(--text)}
        .summary-card h3,.checkout-card h3,.cart-card h3{margin:0 0 16px}.summary-line{display:flex;justify-content:space-between;gap:16px;padding:12px 0;border-top:1px solid var(--line)}.summary-line:first-of-type{border-top:none;padding-top:0}.summary-line.total{font-size:24px;font-weight:700;color:var(--gold-soft)}
        .checkout-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.checkout-fields .field.full{grid-column:1 / -1}.pager{margin-top:22px}.pager nav{display:flex;justify-content:center}.pager .pagination{display:inline-flex;align-items:center;gap:8px;flex-wrap:wrap;list-style:none;padding:0;margin:0}.pager .page-item{list-style:none}.pager .page-link{display:inline-flex;align-items:center;justify-content:center;min-width:42px;height:42px;padding:0 14px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.03);color:var(--text)}.pager .page-link:hover{border-color:rgba(201,164,106,.5);background:rgba(255,255,255,.06)}.pager .page-item.active .page-link{background:var(--gold);border-color:var(--gold);color:#1b140b}.pager .page-item.disabled .page-link{opacity:.45;pointer-events:none}
        .empty-state{padding:34px;border:1px dashed var(--line);border-radius:var(--radius-lg);background:rgba(255,255,255,.02);text-align:center;color:var(--muted)}.flash{margin:0 0 18px;padding:14px 18px;border-radius:18px;background:rgba(31,52,37,.64);border:1px solid rgba(108,173,130,.25);color:#d7f0de}
        .stock-pill{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:999px;font-size:13px;font-weight:700;border:1px solid var(--line)}.stock-pill.in{background:rgba(31,52,37,.5);border-color:rgba(108,173,130,.35);color:#bfe8c9}.stock-pill.out{background:rgba(92,33,33,.45);border-color:rgba(220,104,104,.3);color:#ffd6d6}
        .related-grid{display:grid;gap:18px;grid-template-columns:repeat(4,minmax(0,1fr))}
        .category-section{margin-top:40px}.category-banner{min-height:220px;margin-top:0;margin-bottom:20px}.category-banner .promo-copy{max-width:60%}.category-banner h2{font-size:48px}
        .section-more{display:flex;justify-content:center;margin-top:20px}.section-more .ghost-btn{padding:13px 26px}
        .socials-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}.social-tile{position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;min-height:220px;padding:24px;text-align:center;border:1px solid var(--line);border-radius:var(--radius-lg);background:linear-gradient(180deg,var(--panel),var(--panel-2));color:var(--text);transition:.22s ease}.social-tile:hover{border-color:rgba(201,164,106,.5);transform:translateY(-2px)}.social-tile .social-ico{width:64px;height:64px;border-radius:50%;display:grid;place-items:center;font-size:28px;background:linear-gradient(180deg,#2a2218,#17150f);color:var(--gold-soft)}.social-tile strong{font-size:17px}.social-tile span{color:var(--muted);font-size:13px}
        .site-footer{margin-top:38px;padding:28px 0 0;border-top:1px solid var(--line)}.footer-grid{display:grid;grid-template-columns:1.2fr .9fr .9fr;gap:20px}.footer-title{margin:0 0 12px;font-size:20px;font-family:"Cormorant Garamond",serif}.footer-links{display:grid;gap:10px;color:var(--muted)}.inline-icon{font-size:16px;margin-right:8px}
        @media (max-width:1120px){.site-header-inner,.hero,.detail-layout,.cart-layout,.checkout-layout,.footer-grid{grid-template-columns:1fr}.brand-mark{text-align:left}.header-actions{justify-content:flex-end}.hero-copy,.promo-copy{max-width:100%}.category-strip{grid-template-columns:repeat(3,minmax(0,1fr))}.product-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.related-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.feature-strip{grid-template-columns:repeat(2,minmax(0,1fr))}.socials-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.category-banner .promo-copy{max-width:100%}.detail-gallery{grid-template-columns:1fr}.thumb-list{grid-template-columns:repeat(3,minmax(0,1fr))}.main-visual{min-height:420px}}
        /* ===== Client feedback (2026-08) overrides ===== */
        /* Header: inline search + language + cart, badge on cart */
        .header-actions{gap:10px}
        .header-search{display:flex;align-items:center;gap:8px;padding:9px 14px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.04);min-width:190px;max-width:230px}
        .header-search i{color:var(--gold-soft);font-size:14px}
        .header-search input{border:none;outline:none;background:transparent;color:var(--text);width:100%;min-width:0}
        .header-search input::placeholder{color:var(--muted)}
        .header-search-btn{display:none}
        .cart-icon-wrap{position:relative}
        .cart-badge{position:absolute;top:-5px;right:-5px;min-width:20px;height:20px;padding:0 5px;border-radius:999px;background:linear-gradient(135deg,var(--gold),#e6c892);color:#1b140b;font-size:11px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 3px 8px rgba(0,0,0,.45)}
        /* Floating WhatsApp button on every page */
        .whatsapp-fab{position:fixed;right:22px;bottom:22px;z-index:60;width:60px;height:60px;border-radius:50%;background:#25d366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:30px;box-shadow:0 12px 28px rgba(0,0,0,.45);animation:waPulse 2.6s infinite;transition:transform .2s ease}
        .whatsapp-fab:hover{transform:translateY(-2px);color:#fff}
        @keyframes waPulse{0%{box-shadow:0 12px 28px rgba(0,0,0,.45),0 0 0 0 rgba(37,211,102,.55)}70%{box-shadow:0 12px 28px rgba(0,0,0,.45),0 0 0 16px rgba(37,211,102,0)}100%{box-shadow:0 12px 28px rgba(0,0,0,.45),0 0 0 0 rgba(37,211,102,0)}}
        /* Shop by Category grid: 4 per row (Img K) */
        .category-strip{grid-template-columns:repeat(4,minmax(0,1fr))}
        /* Hero category thumbnails bigger (Img R) */
        .home-category-item{width:132px;flex:0 0 132px}
        .home-category-thumb{width:112px;height:112px}
        .home-category-name{font-size:14px}
        /* Product thumbnails: show the WHOLE item, no side-cropping (Img J/M/N/O/P) */
        .product-thumb{background:radial-gradient(circle at 50% 32%, #16191f 0%, #0a0c0f 78%)}
        .product-thumb img{object-fit:contain;padding:12px}
        /* Size dropdown: dark theme so options are readable (Img E) */
        select.note-box,.filter-select,select{color:var(--text)}
        select option,.note-box option,.filter-select option{background:#15181d;color:#f4efe6}
        /* Socials: bigger tabs + embedded reel per platform (Img G) */
        .socials-grid{gap:22px}
        .social-tile{min-height:300px;padding:22px 20px;gap:14px}
        .social-tile .social-ico{width:76px;height:76px;font-size:32px}
        .social-tile strong{font-size:20px}
        .social-media{width:100%;aspect-ratio:1/1;border-radius:16px;overflow:hidden;border:1px solid var(--line);background:#050505;margin-top:6px}
        .social-media iframe,.social-media video{width:100%;height:100%;border:0;object-fit:cover;display:block}
        /* Category section banner: plain black, no bg image, larger title (Img Q) */
        .category-banner.plain{background:linear-gradient(180deg,#111418,#070809)}
        .category-banner.plain::after{display:none}
        .category-banner.plain img{display:none}
        .category-banner.plain h2{font-size:56px}
        .category-banner.plain .promo-copy{max-width:100%}
        @media (max-width:760px){.page-shell{padding:14px 14px 44px}.site-header{margin-bottom:14px;border-radius:18px}.site-header-inner{grid-template-columns:auto 1fr auto;gap:10px;padding:12px}.brand-mark strong{font-size:30px}.menu-toggle-btn,.cart-icon{width:40px;height:40px}.video-stage{min-height:430px}.home-category-strip{gap:12px;padding-left:14px;padding-right:14px;justify-content:flex-start}.home-category-item{width:86px;flex-basis:86px}.home-category-thumb{width:70px;height:70px}.display-title{font-size:52px}.section-title{font-size:34px}.hero-copy,.promo-copy,.detail-panel,.cart-card,.summary-card,.checkout-card{padding:22px}.toolbar,.filter-form,.product-actions form,.qty-form,.social-row{align-items:stretch}.filter-form{border-radius:28px}.filter-input,.filter-select{min-width:100%}.category-strip,.product-grid,.feature-strip,.checkout-fields,.socials-grid{grid-template-columns:1fr}.category-banner h2{font-size:36px}.cart-item{grid-template-columns:1fr}.spec-row{grid-template-columns:1fr}.detail-panel h1{font-size:44px}.detail-price{font-size:42px}}
        @media (max-width:900px){.header-search{min-width:150px;max-width:170px}}
        @media (max-width:760px){
            .header-search{display:none}
            .header-search-btn{display:inline-flex}
            .lang-pill{display:none}
            .product-grid,.related-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
            .category-strip{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
            .home-category-item{width:100px;flex-basis:100px}
            .home-category-thumb{width:88px;height:88px}
            .socials-grid{grid-template-columns:repeat(1,minmax(0,1fr))}
            .whatsapp-fab{width:54px;height:54px;font-size:27px;right:16px;bottom:16px}
            .category-banner.plain h2{font-size:38px}
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <div class="site-header-inner">
                <div class="header-links">
                    <button type="button" class="menu-toggle-btn" id="storefront-menu-open" aria-label="Open menu" aria-controls="storefront-menu" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                </div>
                <a class="brand-mark" href="{{ route('storefront.index') }}">
                    <small>{{ __('app.fine_jewellery') ?? 'Fine Jewellery' }}</small>
                    <strong>{{ $cmsSetting->logo_text ?? 'AZURE' }}</strong>
                </a>
                <div class="header-actions">
                    <form class="header-search" method="get" action="{{ route('storefront.search') }}" role="search">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.search') ?? 'Search' }}" aria-label="{{ __('app.search') ?? 'Search' }}">
                    </form>
                    <a class="icon-pill cart-icon header-search-btn" href="{{ route('storefront.search', ['q' => request('q')]) }}" aria-label="{{ __('app.search') ?? 'Search' }}">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                    <a class="icon-pill cart-icon lang-pill" href="{{ route('language.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}" aria-label="{{ app()->getLocale() === 'en' ? __('app.arabic') : __('app.english') }}" title="{{ app()->getLocale() === 'en' ? __('app.arabic') : __('app.english') }}">
                        <i class="fa fa-language" aria-hidden="true"></i>
                    </a>
                    <a class="icon-pill cart-icon cart-icon-wrap" href="{{ route('storefront.cart') }}" aria-label="{{ __('app.cart') ?? 'Cart' }}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        @if((int) ($cartCount ?? 0) > 0)
                            <span class="cart-badge">{{ (int) $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </header>
        <div class="menu-backdrop" id="storefront-menu-backdrop" hidden></div>
        <aside class="menu-drawer" id="storefront-menu" hidden aria-hidden="true">
            <div class="menu-drawer-inner">
                <div class="menu-drawer-head">
                    <div class="menu-drawer-brand">
                        <small>{{ __('app.fine_jewellery') ?? 'Fine Jewellery' }}</small>
                        <strong>{{ $cmsSetting->logo_text ?? 'AZURE' }}</strong>
                    </div>
                    <button type="button" class="drawer-close" id="storefront-menu-close" aria-label="Close menu">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="drawer-links">
                    <a class="drawer-link" href="{{ route('storefront.index') }}">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>{{ __('app.home') ?? 'Home' }}</span>
                    </a>
                    <a class="drawer-link" href="{{ route('storefront.categories') }}">
                        <i class="fa fa-th-large" aria-hidden="true"></i>
                        <span>{{ __('app.categories') ?? 'Categories' }}</span>
                    </a>
                    <a class="drawer-link" href="{{ route('storefront.contact') }}">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                        <span>{{ __('app.contact_us') ?? 'Contact Us' }}</span>
                    </a>
                    <a class="drawer-link" href="{{ route('language.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}">
                        <i class="fa fa-language" aria-hidden="true"></i>
                        <span>{{ app()->getLocale() === 'en' ? __('app.arabic') : __('app.english') }}</span>
                    </a>
                    <a class="drawer-link" href="{{ route('login') }}">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                        <span>{{ __('app.sign_in') ?? 'Sign In' }}</span>
                    </a>
                </div>
            </div>
        </aside>

        @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="flash" style="background:rgba(92,33,33,.56);border-color:rgba(220,104,104,.28);color:#ffd6d6">{{ $errors->first() }}</div>
        @endif

        @yield('content')

        <footer class="site-footer">
            <div class="footer-grid">
                <div>
                    <div class="brand-mark" style="text-align:left">
                        <strong>{{ $cmsSetting->logo_text ?? 'AZURE' }}</strong>
                    </div>
                    <p class="muted">{{ $cmsSetting->copyright_text ?? '2026 Azure Fashion - Qatar' }}</p>
                    <div class="footer-links">
                        @php($footerEmail = $cmsSetting->footer_email ?? 'info@azure-fashion.com')
                        @php($footerPhone = $cmsSetting->footer_phone ?? '+974 72 23 23 24')
                        <div><a href="mailto:{{ $footerEmail }}"><i class="fa fa-envelope-o inline-icon" aria-hidden="true"></i>{{ $footerEmail }}</a></div>
                        <div><a href="tel:{{ preg_replace('/[^0-9+]/', '', (string) $footerPhone) }}"><i class="fa fa-phone inline-icon" aria-hidden="true"></i>{{ $footerPhone }}</a></div>
                        <div>{{ $cmsSetting->footer_address ?? 'Qatar' }}</div>
                    </div>
                </div>
                <div>
                    <h3 class="footer-title">{{ __('app.useful_links') ?? 'Useful Links' }}</h3>
                    <div class="footer-links">
                        <a href="{{ route('storefront.page', 'about-us') }}">{{ __('app.about_us') ?? 'About Us' }}</a>
                        <a href="{{ route('storefront.contact') }}">{{ __('app.contact_us') ?? 'Contact Us' }}</a>
                        <a href="{{ route('storefront.categories') }}">{{ __('app.categories') ?? 'Categories' }}</a>
                        <a href="{{ route('storefront.page', 'privacy-policy') }}">{{ __('app.privacy_security') ?? 'Privacy and Security' }}</a>
                        <a href="{{ route('storefront.page', 'shipping-returns') }}">{{ __('app.shipping_returns') ?? 'Shipping & returns' }}</a>
                        <a href="{{ route('storefront.page', 'terms-conditions') }}">{{ __('app.terms_conditions') ?? 'Terms and Conditions' }}</a>
                    </div>
                </div>
                <div>
                    <h3 class="footer-title">{{ __('app.payment_methods') ?? 'Payment Methods' }}</h3>
                    <div class="footer-links">
                        <div>PayPal</div>
                        <div>Visa</div>
                        <div>Mastercard</div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @php($waPhone = $cmsSetting->footer_phone ?? '+974 72 23 23 24')
    @php($waDigits = preg_replace('/[^0-9]/', '', (string) $waPhone))
    @if($waDigits !== '')
        <a class="whatsapp-fab" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener" aria-label="WhatsApp">
            <i class="fa fa-whatsapp" aria-hidden="true"></i>
        </a>
    @endif

    <script>
        (function () {
            const openBtn = document.getElementById('storefront-menu-open');
            const closeBtn = document.getElementById('storefront-menu-close');
            const drawer = document.getElementById('storefront-menu');
            const backdrop = document.getElementById('storefront-menu-backdrop');

            if (!openBtn || !closeBtn || !drawer || !backdrop) {
                return;
            }

            const openMenu = () => {
                drawer.hidden = false;
                backdrop.hidden = false;
                drawer.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            };

            const closeMenu = () => {
                drawer.hidden = true;
                backdrop.hidden = true;
                drawer.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            };

            openBtn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            backdrop.addEventListener('click', closeMenu);
            drawer.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>
