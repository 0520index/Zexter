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

  /* ---- Gold foil particles ---- */
  const field = document.querySelector(".foil-field");
  if (field && !prefersReduced) {
    const canvas = document.createElement("canvas");
    field.appendChild(canvas);
    const ctx = canvas.getContext("2d");
    let w = 0;
    let h = 0;
    let mx = 0.5;
    let my = 0.5;

    const flakes = Array.from({ length: isTouch ? 28 : 55 }, () => ({
      x: Math.random(),
      y: Math.random(),
      z: Math.random() * 0.8 + 0.2,
      r: Math.random() * 2.2 + 0.6,
      spin: Math.random() * Math.PI * 2,
      speed: Math.random() * 0.00035 + 0.00012,
      drift: (Math.random() - 0.5) * 0.00025,
    }));

    const resize = () => {
      w = canvas.width = window.innerWidth * devicePixelRatio;
      h = canvas.height = window.innerHeight * devicePixelRatio;
      canvas.style.width = `${window.innerWidth}px`;
      canvas.style.height = `${window.innerHeight}px`;
      ctx.setTransform(devicePixelRatio, 0, 0, devicePixelRatio, 0, 0);
    };
    resize();
    window.addEventListener("resize", resize);

    window.addEventListener("mousemove", (e) => {
      mx = e.clientX / window.innerWidth;
      my = e.clientY / window.innerHeight;
    });

    const draw = () => {
      ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
      flakes.forEach((f) => {
        f.y += f.speed * f.z;
        f.x += f.drift + (mx - 0.5) * 0.0004 * f.z;
        f.spin += 0.02 * f.z;
        if (f.y > 1.05) {
          f.y = -0.05;
          f.x = Math.random();
        }
        if (f.x < -0.05) f.x = 1.05;
        if (f.x > 1.05) f.x = -0.05;

        const px = f.x * window.innerWidth + (mx - 0.5) * 28 * f.z;
        const py = f.y * window.innerHeight + (my - 0.5) * 18 * f.z;
        const size = f.r * f.z * 2.2;

        ctx.save();
        ctx.translate(px, py);
        ctx.rotate(f.spin);
        ctx.beginPath();
        ctx.moveTo(0, -size);
        ctx.lineTo(size * 0.7, 0);
        ctx.lineTo(0, size);
        ctx.lineTo(-size * 0.7, 0);
        ctx.closePath();
        const g = ctx.createLinearGradient(-size, -size, size, size);
        g.addColorStop(0, "rgba(240, 215, 140, 0.95)");
        g.addColorStop(0.5, "rgba(224, 184, 58, 0.8)");
        g.addColorStop(1, "rgba(138, 112, 32, 0.4)");
        ctx.fillStyle = g;
        ctx.globalAlpha = 0.35 + f.z * 0.45;
        ctx.fill();
        ctx.restore();
      });
      requestAnimationFrame(draw);
    };
    draw();
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

  /* ---- Accordion (services) ---- */
  document.querySelectorAll(".service-panel").forEach((panel) => {
    const trigger = panel.querySelector(".service-panel__trigger");
    if (!trigger) return;
    trigger.addEventListener("click", () => {
      const willOpen = !panel.classList.contains("is-open");
      document.querySelectorAll(".service-panel.is-open").forEach((p) => {
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
