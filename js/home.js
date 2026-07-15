/* Home-only: intro + orbit parallax */

(() => {
  const intro = document.querySelector(".intro");
  if (intro) {
    const seen = sessionStorage.getItem("zexter-intro-v3");
    const percentEl = intro.querySelector("[data-intro-percent]");
    const barEl = intro.querySelector("[data-intro-bar]");
    const prefersReduced = matchMedia("(prefers-reduced-motion: reduce)").matches;

    const finish = () => {
      if (percentEl) percentEl.textContent = "100";
      if (barEl) barEl.style.width = "100%";
      intro.classList.add("is-done");
      sessionStorage.setItem("zexter-intro-v3", "1");
    };

    if (seen) {
      intro.classList.add("is-done");
    } else if (prefersReduced) {
      finish();
    } else {
      const duration = 2000;
      const start = performance.now();
      const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

      const tick = (now) => {
        const t = Math.min(1, (now - start) / duration);
        const p = easeOutCubic(t);
        const value = Math.round(p * 100);
        if (percentEl) percentEl.textContent = String(value);
        if (barEl) barEl.style.width = `${p * 100}%`;
        if (t < 1) {
          requestAnimationFrame(tick);
        } else {
          setTimeout(finish, 280);
        }
      };
      requestAnimationFrame(tick);
    }
  }

  const constellation = document.querySelector(".constellation");
  if (!constellation) return;
  if (matchMedia("(hover: none), (pointer: coarse)").matches) return;
  if (matchMedia("(prefers-reduced-motion: reduce)").matches) return;

  const nodes = constellation.querySelectorAll(".orbit-node");
  constellation.addEventListener("mousemove", (e) => {
    const rect = constellation.getBoundingClientRect();
    const nx = (e.clientX - rect.left) / rect.width - 0.5;
    const ny = (e.clientY - rect.top) / rect.height - 0.5;
    nodes.forEach((node, i) => {
      const depth = (i % 3) * 6 + 8;
      const base = getComputedStyle(node).getPropertyValue("--a") || "0deg";
      node.style.transform = `rotate(${base}) translateY(calc(min(210px, 39vw) * -1)) rotate(calc(${base} * -1)) translate(${nx * depth}px, ${ny * depth}px)`;
    });
  });

  constellation.addEventListener("mouseleave", () => {
    nodes.forEach((node) => {
      node.style.transform = "";
    });
  });
})();
