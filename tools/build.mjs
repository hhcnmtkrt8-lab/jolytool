import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";
import {
  CONTACT,
  COMPANY,
  IMAGES,
  NEWS,
  PRODUCTS,
  T,
} from "./content.mjs";

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..");
const SITE = "https://www.asiagaopeng.com";

const esc = (s = "") =>
  String(s)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;");

const logicalToFile = (lang, logical) => {
  const base = logical === "index" ? "index" : logical;
  const file = `${base}.html`;
  return lang === "cn" ? `cn/${file}` : file;
};

const rel = (fromDir, target) =>
  path.posix.relative(fromDir === "" ? "." : fromDir, target);

function makeCtx(lang, logical, active) {
  const depth = logical.split("/").length - 1;
  const fromDir = [lang === "cn" ? "cn" : "", ...Array(depth).fill("x")]
    .filter(Boolean)
    .map(() => "")
    .join("");
  const dir =
    lang === "cn"
      ? depth === 0
        ? "cn/"
        : "cn/" + logical.split("/").slice(0, depth).join("/") + "/"
      : depth === 0
        ? ""
        : logical.split("/").slice(0, depth).join("/") + "/";

  const url = (lg, lgLogical) => rel(dir, logicalToFile(lg, lgLogical));
  const asset = (p) => rel(dir, `assets/${p}`);
  const other = lang === "cn" ? "en" : "cn";

  return {
    lang,
    dir,
    asset,
    url,
    href: (lgLogical) => url(lang, lgLogical),
    altLang: other,
    altHref: url(other, logical),
    active,
    fromDir,
  };
}

function header(ctx) {
  const t = T[ctx.lang];
  const nav = t.nav
    .map((item) => {
      const current = item.key === ctx.active ? ' aria-current="page"' : "";
      return `<li><a class="nav__link" href="${ctx.href(item.href)}"${current}>${esc(
        item.label
      )}</a></li>`;
    })
    .join("");

  return `<header class="header">
  <div class="shell header__inner">
    <a class="brand" href="${ctx.href("index")}" aria-label="${esc(
      COMPANY[ctx.lang].name
    )}">
      <span class="brand__mark">GOPEX</span>
      <span class="brand__sub">${esc(t.brandSub)}</span>
    </a>
    <nav class="nav" id="nav" aria-label="${ctx.lang === "cn" ? "主导航" : "Main"}">
      <ul class="nav__list">${nav}</ul>
    </nav>
    <div class="header__actions">
      <a class="lang" href="${ctx.altHref}" hreflang="${
        ctx.altLang === "cn" ? "zh-CN" : "en"
      }">${esc(t.langLabel)}</a>
      <a class="btn" href="${ctx.href("contact")}">${esc(t.quote)}<span class="arrow" aria-hidden="true">→</span></a>
      <button class="nav-toggle" type="button" aria-controls="nav" aria-expanded="false" aria-label="${
        ctx.lang === "cn" ? "打开菜单" : "Open menu"
      }"><span></span></button>
    </div>
  </div>
</header>`;
}

function footer(ctx) {
  const t = T[ctx.lang];
  const c = CONTACT;
  const companyLinks = t.nav
    .filter((n) => n.key !== "home")
    .map((n) => `<li><a href="${ctx.href(n.href)}">${esc(n.label)}</a></li>`)
    .join("");

  const productLinks = PRODUCTS.slice(0, 4)
    .map(
      (p) =>
        `<li><a href="${ctx.href(`products/${p.slug}`)}">${esc(
          p[ctx.lang].name
        )}</a></li>`
    )
    .join("");

  return `<footer class="footer">
  <div class="shell">
    <div class="footer__grid">
      <div class="footer__brand">
        <span class="brand__mark">GOPEX</span>
        <p>${esc(t.footerAbout)}</p>
      </div>
      <div class="footer__col">
        <h4>${esc(t.footerCompany)}</h4>
        <ul>${companyLinks}</ul>
      </div>
      <div class="footer__col">
        <h4>${esc(t.footerProducts)}</h4>
        <ul>${productLinks}</ul>
      </div>
      <div class="footer__col">
        <h4>${esc(t.footerContact)}</h4>
        <ul>
          <li><a href="mailto:${c.email}">${c.email}</a></li>
          <li><a href="${c.telHref}">${esc(
            ctx.lang === "cn" ? c.telCN : c.tel
          )}</a></li>
          <li>${esc(
            ctx.lang === "cn" ? c.addressCN : c.addressEN
          )}</li>
        </ul>
      </div>
    </div>
    <div class="footer__bottom">
      <span>© <span data-year>2025</span> ${esc(
        COMPANY[ctx.lang].name
      )} ${esc(t.footerRights)}</span>
      <span>${esc(t.footerNote)}</span>
    </div>
  </div>
</footer>`;
}

function layout(ctx, meta) {
  const t = T[ctx.lang];
  const canonical = `${SITE}/${logicalToFile(ctx.lang, meta.logical)}`;
  const desc = meta.description;

  return `<!DOCTYPE html>
<html lang="${ctx.lang === "cn" ? "zh-CN" : "en"}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>${esc(meta.title)}</title>
<meta name="description" content="${esc(desc)}">
${meta.keywords ? `<meta name="keywords" content="${esc(meta.keywords)}">` : ""}
<link rel="canonical" href="${canonical}">
<meta property="og:type" content="website">
<meta property="og:title" content="${esc(meta.title)}">
<meta property="og:description" content="${esc(desc)}">
<meta property="og:image" content="${meta.image || `${SITE}/assets/${IMAGES.meshRolls}`}">
<meta property="og:url" content="${canonical}">
<meta name="theme-color" content="#15171a">
<link rel="preconnect" href="https://coresg-normal.trae.ai">
<link rel="stylesheet" href="${ctx.asset("css/main.css")}">
</head>
<body>
${header(ctx)}
<main>
${meta.body}
</main>
${footer(ctx)}
<script src="${ctx.asset("js/main.js")}" defer></script>
${meta.jsonld ? `<script type="application/ld+json">${JSON.stringify(meta.jsonld)}</script>` : ""}
</body>
</html>
`;
}

/* ---------- shared blocks ---------- */

function factStrip(ctx) {
  const t = T[ctx.lang];
  const items = t.facts
    .map(
      (f) => `<div class="facts__item">
      <span class="facts__value">${esc(f.value)}</span>
      <span class="facts__label">${esc(f.label)}</span>
    </div>`
    )
    .join("");
  return `<div class="shell"><div class="facts" data-reveal>${items}</div></div>`;
}

function capabilityBlock(ctx) {
  const t = T[ctx.lang];
  const items = t.capabilities
    .map(
      (c) => `<article class="capability" data-reveal>
      <span class="capability__no">${c.no}</span>
      <h3 class="h3">${esc(c.title)}</h3>
      <p>${esc(c.text)}</p>
    </article>`
    )
    .join("");

  return `<section class="section section--tight">
  <div class="shell">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.productsEyebrow === "产品中心" ? "制造" : "Manufacturing")}</span>
      <h2 class="h2">${esc(t.capabilitiesTitle)}</h2>
    </div>
    <div class="capabilities" style="margin-top: clamp(28px, 4vw, 48px)">${items}</div>
  </div>
</section>`;
}

function qualityBlock(ctx) {
  const t = T[ctx.lang];
  const rows = t.qualityItems
    .map(
      (q) => `<div class="quality__item"><dt>${esc(q.k)}</dt><dd>${esc(q.v)}</dd></div>`
    )
    .join("");

  return `<section class="section section--dark">
  <div class="shell quality">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.qualityEyebrow)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(t.qualityTitle)}</h2>
      <p class="lede" style="margin-top:20px">${esc(t.qualityLede)}</p>
    </div>
    <div data-reveal style="--reveal-delay:120ms">
      <dl class="quality__list" style="margin:0">${rows}</dl>
    </div>
  </div>
</section>`;
}

function productRows(ctx, products) {
  return products
    .map((p, i) => {
      const d = p[ctx.lang];
      return `<a class="product-row" href="${ctx.href(`products/${p.slug}`)}" data-reveal>
      <span class="product-row__no">${String(i + 1).padStart(2, "0")}</span>
      <span>
        <span class="product-row__name">${esc(d.name)}</span>
        <span class="product-row__meta">${esc(d.short)}</span>
      </span>
      <span class="product-row__arrow" aria-hidden="true">→</span>
    </a>`;
    })
    .join("");
}

function ctaBand(ctx) {
  const t = T[ctx.lang];
  return `<section class="cta-band">
  <div class="shell cta-band__inner">
    <div data-reveal>
      <h2 class="h2">${esc(t.ctaTitle)}</h2>
      <p>${esc(t.ctaText)}</p>
    </div>
    <div class="hero__actions" data-reveal>
      <a class="btn" href="${ctx.href("contact")}">${esc(t.ctaBtn)}<span class="arrow" aria-hidden="true">→</span></a>
      <a class="btn btn--ghost" href="${CONTACT.whatsappHref}" target="_blank" rel="noopener">${esc(
        t.ctaBtn2
      )}</a>
    </div>
  </div>
</section>`;
}

/* Graphic tile used where no photograph exists yet. */
function tile(no, name) {
  return `<div class="media-tile">
      <span class="media-tile__no">${esc(String(no).padStart(2, "0"))}</span>
      <span class="media-tile__name">${esc(name)}</span>
    </div>`;
}

/* ---------- pages ---------- */

function homePage(ctx) {
  const t = T[ctx.lang];
  const c = COMPANY[ctx.lang];

  const body = `<section class="hero">
  <div class="hero__pattern" aria-hidden="true"></div>
  <div class="shell hero__inner">
    <div class="hero__grid">
      <div class="hero__copy">
        <span class="eyebrow hero__eyebrow">${esc(t.heroEyebrow)}</span>
        <h1 class="hero__title">${esc(t.heroTitle)}</h1>
        <div class="hero__bottom">
          <p class="hero__lede">${esc(t.heroLede)}</p>
          <div class="hero__actions">
            <a class="btn" href="${ctx.href("products")}">${esc(t.heroCta)}<span class="arrow" aria-hidden="true">→</span></a>
            <a class="btn btn--ghost" href="${ctx.href("contact")}">${esc(t.heroCta2)}</a>
          </div>
        </div>
      </div>
      <figure class="hero__plate">
        <img src="${ctx.asset(IMAGES.meshRolls)}" alt="${esc(
          ctx.lang === "cn"
            ? "高朋建材生产的玻纤网格胶带成品卷"
            : "Fiberglass mesh tape rolls produced by Gaopeng"
        )}" width="1024" height="683">
        <figcaption><span>Fiberglass mesh tape</span><span>8x8 – 10x10 / inch</span></figcaption>
      </figure>
    </div>
  </div>
</section>

<section class="section--tight" style="padding-block:0">${factStrip(ctx)}</section>

<section class="section">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.introEyebrow)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(t.introTitle)}</h2>
      <p class="lede">${esc(c.intro)}</p>
      <p style="color:var(--ink-2)">${esc(c.intro2)}</p>
      <p style="color:var(--ink-2)">${esc(c.intro3)}</p>
      <p><a class="link-arrow" href="${ctx.href("about")}">${esc(
        ctx.lang === "cn" ? "了解公司详情" : "More about the company"
      )}<span aria-hidden="true">→</span></a></p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      ${tile(1, ctx.lang === "cn" ? "玻纤网格布" : "Fiberglass mesh")}
    </div>
  </div>
</section>

${capabilityBlock(ctx)}

<section class="section">
  <div class="shell products-layout">
    <div class="products-intro" data-reveal>
      <span class="eyebrow">${esc(t.productsEyebrow)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(t.productsTitle)}</h2>
      <p class="lede" style="margin-top:18px">${esc(t.productsLede)}</p>
      <p style="margin-top:26px"><a class="link-arrow" href="${ctx.href(
        "products"
      )}">${esc(t.allProducts)}<span aria-hidden="true">→</span></a></p>
    </div>
    <div class="product-rows">${productRows(ctx, PRODUCTS)}</div>
  </div>
</section>

${qualityBlock(ctx)}

<section class="section section--tight">
  <div class="shell">
    <div class="copy" data-reveal style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between">
      <div>
        <span class="eyebrow">${esc(t.newsEyebrow)}</span>
        <h2 class="h2" style="margin-top:18px">${esc(t.newsTitle)}</h2>
      </div>
      <a class="link-arrow" href="${ctx.href("news")}">${esc(t.newsAll)}<span aria-hidden="true">→</span></a>
    </div>
    <div class="product-rows" style="margin-top:clamp(28px,4vw,44px)">${newsRows(
      ctx
    )}</div>
  </div>
</section>

${ctaBand(ctx)}`;

  return {
    logical: "index",
    title:
      ctx.lang === "cn"
        ? "南通高朋建材有限公司｜玻璃纤维自粘胶带·网格布·金属护角带"
        : "GOPEX | Fiberglass Mesh Tape & Alkali-Resistant Mesh Manufacturer",
    description: c.intro,
    keywords:
      ctx.lang === "cn"
        ? "玻璃纤维自粘胶带,玻纤网格布,金属护角带,接缝纸带,金属补墙板,南通高朋建材"
        : "fiberglass mesh tape, alkali-resistant fiberglass mesh, metal corner tape, paper tape, wall patch, drywall joint tape manufacturer",
    body,
    jsonld: {
      "@context": "https://schema.org",
      "@type": "Organization",
      name: COMPANY[ctx.lang].name,
      alternateName: "GOPEX",
      url: SITE,
      foundingDate: "2004",
      email: CONTACT.email,
      telephone: CONTACT.tel,
      address: {
        "@type": "PostalAddress",
        streetAddress:
          ctx.lang === "cn" ? CONTACT.addressCN : "Motou Industrial Park",
        addressLocality: ctx.lang === "cn" ? "如皋市" : "Rugao",
        addressRegion: ctx.lang === "cn" ? "江苏省" : "Jiangsu",
        postalCode: "226551",
        addressCountry: "CN",
      },
    },
  };
}

function aboutPage(ctx) {
  const t = T[ctx.lang];
  const c = COMPANY[ctx.lang];

  const body = `<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb"><a href="${ctx.href(
        "index"
      )}">${esc(t.breadcrumbHome)}</a><span>/</span><span>${esc(
        ctx.lang === "cn" ? "关于我们" : "About"
      )}</span></nav>
      <h1 class="page-head__title">${esc(t.aboutTitle)}</h1>
    </div>
    <p class="page-head__aside" data-reveal>${esc(t.aboutAside)}</p>
  </div>
</section>

<section class="section">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.introEyebrow)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(c.shortName)}</h2>
      <p class="lede">${esc(c.intro)}</p>
      <p style="color:var(--ink-2)">${esc(c.intro2)}</p>
      <p style="color:var(--ink-2)">${esc(c.intro3)}</p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      ${tile(1, ctx.lang === "cn" ? "自 2004 年 · 江苏如皋" : "Since 2004 · Rugao, Jiangsu")}
    </div>
  </div>
</section>

<section class="section--tight" style="padding-block:0">${factStrip(ctx)}</section>

${capabilityBlock(ctx)}

<section class="section section--paper2">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.productsEyebrow)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(t.productsTitle)}</h2>
      <p class="lede" style="margin-top:18px">${esc(t.productsLede)}</p>
      <p style="margin-top:26px"><a class="btn" href="${ctx.href(
        "products"
      )}">${esc(t.allProducts)}<span class="arrow" aria-hidden="true">→</span></a></p>
    </div>
    <div class="product-rows" style="border-top-color:var(--line-strong)">${productRows(
      ctx,
      PRODUCTS.slice(0, 5)
    )}</div>
  </div>
</section>

${qualityBlock(ctx)}

<section class="section section--tight">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow">${esc(t.footerContact)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(
        ctx.lang === "cn" ? "如皋磨头工业园区" : "Motou Industrial Park, Rugao"
      )}</h2>
      <p style="color:var(--ink-2)">${esc(
        ctx.lang === "cn" ? c.intro3 : "Two and a half hours from the port of Shanghai."
      )}</p>
      <p><a class="link-arrow" href="${ctx.href("contact")}">${esc(
        ctx.lang === "cn" ? "获取联系方式" : "Contact details"
      )}<span aria-hidden="true">→</span></a></p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      ${tile(2, CONTACT.addressCN)}
    </div>
  </div>
</section>

${ctaBand(ctx)}`;

  return {
    logical: "about",
    title:
      ctx.lang === "cn"
        ? `关于我们｜${COMPANY.cn.name}`
        : `About | ${COMPANY.en.name}`,
    description: c.intro,
    body,
  };
}

function productsPage(ctx) {
  const t = T[ctx.lang];

  const body = `<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb"><a href="${ctx.href(
        "index"
      )}">${esc(t.breadcrumbHome)}</a><span>/</span><span>${esc(
        t.productsPageTitle
      )}</span></nav>
      <h1 class="page-head__title">${esc(t.productsPageTitle)}</h1>
    </div>
    <p class="page-head__aside" data-reveal>${esc(t.productsPageAside)}</p>
  </div>
</section>

<section class="section">
  <div class="shell">
    <div class="product-rows">${productRows(ctx, PRODUCTS)}</div>
  </div>
</section>

${qualityBlock(ctx)}

${ctaBand(ctx)}`;

  return {
    logical: "products",
    title:
      ctx.lang === "cn"
        ? "产品中心｜玻纤自粘胶带·网格布·金属护角带·接缝纸带"
        : "Products | Fiberglass Tape, Mesh, Corner Tape & Wall Patch",
    description: t.productsPageAside,
    body,
  };
}

function productPage(ctx, product) {
  const t = T[ctx.lang];
  const d = product[ctx.lang];
  const index = PRODUCTS.indexOf(product) + 1;
  const photo = `img/products/${product.slug}.jpg`;
  const hasPhoto = fs.existsSync(path.join(root, "assets", photo));
  const others = PRODUCTS.filter((p) => p.slug !== product.slug).slice(0, 3);

  const bullets = d.bullets.length
    ? `<div>
        <h2 class="h3">${esc(d.heading)}</h2>
        <ul class="feature-list" style="margin-top:16px">${d.bullets
          .map((b) => `<li>${esc(b)}</li>`)
          .join("")}</ul>
      </div>`
    : "";

  const uses = d.uses
    ? `<div>
        <h2 class="h3">${esc(d.usesTitle)}</h2>
        <ul class="feature-list" style="margin-top:16px">${d.uses
          .map((u) => `<li>${esc(u)}</li>`)
          .join("")}</ul>
      </div>`
    : "";

  const specs = `<div>
      <div class="table-scroll">
        <table class="specs">
          <caption>${esc(d.specTitle)}</caption>
          <thead><tr>${d.cols
            .map((col) => `<th scope="col">${esc(col)}</th>`)
            .join("")}</tr></thead>
          <tbody>${d.rows
            .map(
              (row) =>
                `<tr>${row.map((cell) => `<td>${esc(cell)}</td>`).join("")}</tr>`
            )
            .join("")}</tbody>
        </table>
      </div>
      <p class="muted" style="font-size:13.5px;margin-top:14px">${esc(t.specNote)}</p>
    </div>`;

  const body = `<section class="section--tight" style="padding-top:clamp(28px,4vw,48px)">
  <div class="shell">
    <nav class="crumbs" style="margin-bottom:clamp(24px,3vw,36px)" aria-label="Breadcrumb">
      <a href="${ctx.href("index")}">${esc(t.breadcrumbHome)}</a><span>/</span>
      <a href="${ctx.href("products")}">${esc(t.productsPageTitle)}</a><span>/</span>
      <span>${esc(d.name)}</span>
    </nav>
    <div class="pd">
      <div class="pd__media" data-reveal>
        ${
          hasPhoto
            ? `<img src="${ctx.asset(photo)}" alt="${esc(
                d.name
              )}" width="1000" height="1000">`
            : tile(index, d.name)
        }
      </div>
      <div class="pd__body" data-reveal style="--reveal-delay:100ms">
        <span class="eyebrow">${esc(t.productsEyebrow)}</span>
        <h1 class="display" style="font-size:clamp(2rem,3.6vw,3.2rem)">${esc(
          d.name
        )}</h1>
        <div class="pd__keywords">${d.keywords
          .map((k) => `<span class="tag">${esc(k)}</span>`)
          .join("")}</div>
        <p class="lede">${esc(d.intro)}</p>
        ${bullets}
        ${uses}
        ${specs}
        <div class="hero__actions">
          <a class="btn" href="${ctx.href("contact")}">${esc(t.quote)}<span class="arrow" aria-hidden="true">→</span></a>
          <a class="btn btn--ghost" href="${CONTACT.whatsappHref}" target="_blank" rel="noopener">WhatsApp</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--paper2">
  <div class="shell">
    <div class="copy" data-reveal style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap">
      <h2 class="h2">${esc(t.relatedTitle)}</h2>
      <a class="link-arrow" href="${ctx.href("products")}">${esc(t.backToProducts)}<span aria-hidden="true">→</span></a>
    </div>
    <div class="product-rows" style="margin-top:clamp(24px,3vw,36px)">${productRows(
      ctx,
      others
    )}</div>
  </div>
</section>

${ctaBand(ctx)}`;

  const ogImage = hasPhoto ? `${SITE}/assets/${photo}` : `${SITE}/assets/${IMAGES.meshRolls}`;

  return {
    logical: `products/${product.slug}`,
    title: `${d.name} | ${COMPANY[ctx.lang].shortName}`,
    description: d.intro.slice(0, 155),
    keywords: d.keywords.join(", "),
    image: ogImage,
    body,
    jsonld: {
      "@context": "https://schema.org",
      "@type": "Product",
      name: d.name,
      description: d.intro,
      image: ogImage,
      brand: { "@type": "Brand", name: "GOPEX" },
      manufacturer: { "@type": "Organization", name: COMPANY[ctx.lang].name },
    },
  };
}

function newsRows(ctx) {
  return NEWS.map((item) => {
    const d = item[ctx.lang];
    return `<a class="product-row product-row--news" href="${ctx.href(
      `news/${item.slug}`
    )}" data-reveal>
      <span class="product-row__no">${esc(item.date)}</span>
      <span>
        <span class="product-row__name">${esc(d.title)}</span>
        <span class="product-row__meta">${esc(d.excerpt)}</span>
      </span>
      <span class="product-row__arrow" aria-hidden="true">→</span>
    </a>`;
  }).join("");
}

function newsPage(ctx) {
  const t = T[ctx.lang];
  const rows = newsRows(ctx);

  const body = `<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb"><a href="${ctx.href(
        "index"
      )}">${esc(t.breadcrumbHome)}</a><span>/</span><span>${esc(
        t.newsPageTitle
      )}</span></nav>
      <h1 class="page-head__title">${esc(t.newsPageTitle)}</h1>
    </div>
    <p class="page-head__aside" data-reveal>${esc(t.newsPageAside)}</p>
  </div>
</section>

<section class="section">
  <div class="shell"><div class="product-rows">${rows}</div></div>
</section>

${ctaBand(ctx)}`;

  return {
    logical: "news",
    title: `${t.newsPageTitle} | ${COMPANY[ctx.lang].shortName}`,
    description: t.newsPageAside,
    body,
  };
}

function newsDetailPage(ctx, item) {
  const t = T[ctx.lang];
  const d = item[ctx.lang];

  const body = `<section class="section">
  <div class="shell">
    <nav class="crumbs" style="margin-bottom:clamp(24px,3vw,36px)" aria-label="Breadcrumb">
      <a href="${ctx.href("index")}">${esc(t.breadcrumbHome)}</a><span>/</span>
      <a href="${ctx.href("news")}">${esc(t.newsPageTitle)}</a><span>/</span>
      <span>${esc(d.title)}</span>
    </nav>
    <article class="article" data-reveal>
      <div class="article__meta"><span>${esc(t.publishLabel)}</span><span>${esc(
        item.date
      )}</span></div>
      <h1 class="display" style="font-size:clamp(1.9rem,3.4vw,3rem);margin-top:18px">${esc(
        d.title
      )}</h1>
      <div class="article__body" style="margin-top:28px">${d.body
        .map((p) => `<p>${esc(p)}</p>`)
        .join("")}</div>
      <p style="margin-top:36px"><a class="link-arrow" href="${ctx.href(
        "news"
      )}">${esc(t.newsAll)}<span aria-hidden="true">→</span></a></p>
    </article>
  </div>
</section>

${ctaBand(ctx)}`;

  return {
    logical: `news/${item.slug}`,
    title: `${d.title} | ${COMPANY[ctx.lang].shortName}`,
    description: d.excerpt,
    body,
  };
}

function contactPage(ctx) {
  const t = T[ctx.lang];
  const c = CONTACT;
  const L = t.contactLabels;
  const cn = ctx.lang === "cn";

  const item = (label, valueHtml, note = "") => `<div class="contact-item">
      <span class="contact-item__label">${esc(label)}</span>
      <p class="contact-item__value">${valueHtml}</p>
      ${note ? `<p class="contact-item__note">${esc(note)}</p>` : ""}
    </div>`;

  const body = `<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb"><a href="${ctx.href(
        "index"
      )}">${esc(t.breadcrumbHome)}</a><span>/</span><span>${esc(
        t.contactPageTitle
      )}</span></nav>
      <h1 class="page-head__title">${esc(t.contactPageTitle)}</h1>
    </div>
    <p class="page-head__aside" data-reveal>${esc(t.contactPageAside)}</p>
  </div>
</section>

<section class="section">
  <div class="shell split">
    <div data-reveal>
      <span class="eyebrow">${esc(L.company)}</span>
      <h2 class="h2" style="margin-top:20px">${esc(COMPANY[ctx.lang].name)}</h2>
      <p class="lede" style="margin-top:18px">${esc(
        cn ? COMPANY.cn.intro : COMPANY.en.intro2
      )}</p>
      <div class="contact-grid" style="margin-top:clamp(28px,4vw,44px)">
        ${item(L.tel, `<a href="${c.telHref}">${esc(cn ? c.telCN : c.tel)}</a>`)}
        ${item(L.fax, `<a href="${c.faxHref}">${esc(cn ? c.faxCN : c.fax)}</a>`)}
        ${item(L.email, `<a href="mailto:${c.email}">${c.email}</a>`)}
        ${item(
          L.whatsapp,
          `<a href="${c.whatsappHref}" target="_blank" rel="noopener">${esc(
            c.whatsapp
          )}</a>`,
          t.contactNoteWhatsapp
        )}
        ${item(L.skype, esc(c.skype))}
      </div>
    </div>
    <div data-reveal style="--reveal-delay:120ms">
      ${tile(3, cn ? c.addressCN : c.addressEN)}
      <div class="contact-item" style="border-top:1px solid var(--line);margin-top:28px">
        <span class="contact-item__label">${esc(L.address)}</span>
        <p class="contact-item__value">${esc(cn ? c.addressCN : c.addressEN)}</p>
      </div>
    </div>
  </div>
</section>

${ctaBand(ctx)}`;

  return {
    logical: "contact",
    title: `${t.contactPageTitle} | ${COMPANY[ctx.lang].shortName}`,
    description: `${COMPANY[ctx.lang].name} · ${c.email} · ${
      cn ? c.addressCN : c.addressEN
    }`,
    body,
  };
}

/* ---------- emit ---------- */

const pages = [];

for (const lang of ["en", "cn"]) {
  pages.push([makeCtx(lang, "index", "home"), homePage(makeCtx(lang, "index", "home"))]);
  pages.push([makeCtx(lang, "about", "about"), aboutPage(makeCtx(lang, "about", "about"))]);
  pages.push([
    makeCtx(lang, "products", "products"),
    productsPage(makeCtx(lang, "products", "products")),
  ]);
  pages.push([makeCtx(lang, "news", "news"), newsPage(makeCtx(lang, "news", "news"))]);
  pages.push([
    makeCtx(lang, "contact", "contact"),
    contactPage(makeCtx(lang, "contact", "contact")),
  ]);

  for (const product of PRODUCTS) {
    const logical = `products/${product.slug}`;
    const ctx = makeCtx(lang, logical, "products");
    pages.push([ctx, productPage(ctx, product)]);
  }

  for (const item of NEWS) {
    const logical = `news/${item.slug}`;
    const ctx = makeCtx(lang, logical, "news");
    pages.push([ctx, newsDetailPage(ctx, item)]);
  }
}

let written = 0;
for (const [ctx, page] of pages) {
  const target = path.join(root, logicalToFile(ctx.lang, page.logical));
  fs.mkdirSync(path.dirname(target), { recursive: true });
  fs.writeFileSync(target, layout(ctx, page), "utf8");
  written += 1;
}

console.log(`Generated ${written} pages.`);
