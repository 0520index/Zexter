/* 株式会社 zexter — shared interactions */

(() => {
  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const isTouch = matchMedia("(hover: none), (pointer: coarse)").matches;

  if (isTouch) document.body.classList.add("is-touch");

  /* ---- Page transition curtain ---- */
  const transition = document.createElement("div");
  transition.className = "page-transition";
  transition.setAttribute("aria-hidden", "true");
  for (let i = 0; i < 10; i++) {
    transition.appendChild(document.createElement("span"));
  }
  document.body.prepend(transition);

  document.querySelectorAll("a[href]").forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      if (!href || href.startsWith("#") || href.startsWith("mailto:") || href.startsWith("tel:")) return;
      if (link.target === "_blank" || /^(https?:)?\/\//i.test(href)) return;
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      const url = new URL(href, window.location.href);
      if (url.origin !== window.location.origin) return;
      if (url.pathname === window.location.pathname && url.hash) return;
      e.preventDefault();
      transition.classList.add("is-leaving");
      setTimeout(() => {
        window.location.href = href;
      }, 620);
    });
  });

  requestAnimationFrame(() => {
    transition.classList.add("is-entering");
    setTimeout(() => transition.classList.remove("is-entering"), 700);
  });

  /* ---- Custom cursor ---- */
  if (!isTouch && !prefersReduced) {
    const cursor = document.createElement("div");
    const follower = document.createElement("div");
    cursor.className = "cursor";
    follower.className = "cursor-follower";
    document.body.append(cursor, follower);

    let x = window.innerWidth / 2;
    let y = window.innerHeight / 2;
    let fx = x;
    let fy = y;

    window.addEventListener("mousemove", (e) => {
      x = e.clientX;
      y = e.clientY;
      cursor.style.transform = `translate(${x}px, ${y}px) translate(-50%, -50%)`;
    });

    const tick = () => {
      fx += (x - fx) * 0.16;
      fy += (y - fy) * 0.16;
      follower.style.transform = `translate(${fx}px, ${fy}px) translate(-50%, -50%)`;
      requestAnimationFrame(tick);
    };
    tick();

    const hoverables = "a, button, .service-tile, .orbit-node, .service-panel__trigger, input, textarea, select";
    document.querySelectorAll(hoverables).forEach((el) => {
      el.addEventListener("mouseenter", () => {
        cursor.classList.add("is-hover");
        follower.classList.add("is-hover");
      });
      el.addEventListener("mouseleave", () => {
        cursor.classList.remove("is-hover");
        follower.classList.remove("is-hover");
      });
    });
  }

  /* ---- Scroll progress ---- */
  const bar = document.querySelector(".scroll-progress");
  if (bar) {
    const update = () => {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      const p = max > 0 ? (window.scrollY / max) * 100 : 0;
      bar.style.width = `${p}%`;
    };
    window.addEventListener("scroll", update, { passive: true });
    update();
  }

  /* ---- Header scroll state ---- */
  const header = document.querySelector(".site-header");
  if (header) {
    const onScroll = () => {
      header.classList.toggle("is-scrolled", window.scrollY > 24);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  /* ---- Mobile nav ---- */
  const toggle = document.querySelector(".nav-toggle");
  const nav = document.querySelector(".nav");
  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      const open = toggle.classList.toggle("is-open");
      nav.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
    });
    nav.querySelectorAll("a").forEach((a) => {
      a.addEventListener("click", () => {
        toggle.classList.remove("is-open");
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  /* ---- Reveal on scroll ---- */
  const reveals = document.querySelectorAll(".reveal");
  if (reveals.length) {
    if (prefersReduced || !("IntersectionObserver" in window)) {
      reveals.forEach((el) => el.classList.add("is-in"));
    } else {
      const io = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-in");
              io.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.18, rootMargin: "0px 0px -8% 0px" }
      );
      reveals.forEach((el) => io.observe(el));
    }
  }

  /* ---- Magnetic buttons ---- */
  if (!isTouch && !prefersReduced) {
    document.querySelectorAll(".btn").forEach((btn) => {
      btn.addEventListener("mousemove", (e) => {
        const rect = btn.getBoundingClientRect();
        const dx = e.clientX - (rect.left + rect.width / 2);
        const dy = e.clientY - (rect.top + rect.height / 2);
        btn.style.transform = `translate(${dx * 0.18}px, ${dy * 0.22}px)`;
      });
      btn.addEventListener("mouseleave", () => {
        btn.style.transform = "";
      });
    });
  }

  /* ---- Accordion (services overview on home) ---- */
  document.querySelectorAll(".service-panel").forEach((panel) => {
    const trigger = panel.querySelector(".service-panel__trigger");
    if (!trigger) return;
    trigger.addEventListener("click", () => {
      const willOpen = !panel.classList.contains("is-open");
      panel.parentElement?.querySelectorAll(".service-panel.is-open").forEach((p) => {
        if (p !== panel) {
          p.classList.remove("is-open");
          p.querySelector(".service-panel__trigger")?.setAttribute("aria-expanded", "false");
        }
      });
      panel.classList.toggle("is-open", willOpen);
      trigger.setAttribute("aria-expanded", String(willOpen));
    });
  });

  /* ---- Contact form (demo) ---- */
  const form = document.querySelector("#contact-form");
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const status = form.querySelector(".form-status");
      const required = form.querySelectorAll("[required]");
      let ok = true;
      required.forEach((field) => {
        if (!String(field.value || "").trim()) ok = false;
      });
      if (!ok) {
        if (status) {
          status.textContent = "必須項目をご入力ください。";
          status.classList.add("is-visible");
        }
        return;
      }
      if (status) {
        status.textContent = "送信イメージです。本番ではメール送信などに接続します。ありがとうございます。";
        status.classList.add("is-visible");
      }
      form.reset();
    });
  }
})();
