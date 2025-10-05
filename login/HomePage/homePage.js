document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll('.slider-nav a');
    const slides = document.querySelectorAll('.slider img');
    const slider = document.querySelector('.slider');
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slideInfo = document.querySelector('.slide-info');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    function escapeHtml(str = "") {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    // returns index of slide closest to slider left edge
    function getActiveIndex() {
        if (!slider || slides.length === 0) return 0;
        const sliderLeft = slider.getBoundingClientRect().left;
        let minDistance = Infinity;
        let activeIndex = 0;
        slides.forEach((slide, i) => {
            const rect = slide.getBoundingClientRect();
            const distance = Math.abs(rect.left - sliderLeft);
            if (distance < minDistance) {
                minDistance = distance;
                activeIndex = i;
            }
        });
        return activeIndex;
    }

    // render slide-info content based on data attributes
    function renderSlideInfoFor(slide) {
        if (!slide || !slideInfo) return;
        // read data attributes (fallbacks shown)
        const title = slide.dataset.title || slide.alt || "";
        const subtitle = slide.dataset.subtitle || "";
        const ctaText = slide.dataset.ctaText || "";
        const ctaLink = slide.dataset.ctaLink || "";
        // build safe HTML
        let html = `<h2 class="slide-title">${escapeHtml(title)}</h2>`;
        if (subtitle) html += `<p class="slide-subtitle">${escapeHtml(subtitle)}</p>`;
        if (ctaText && ctaLink) {
            html += `<a class="slide-cta" href="${escapeHtml(ctaLink)}">${escapeHtml(ctaText)}</a>`;
        }
        slideInfo.innerHTML = html;
    }

    function setActiveNav() {
        const activeIndex = getActiveIndex();
        navLinks.forEach((link, i) => {
            link.classList.toggle('active', i === activeIndex);
        });

        // update overlay/slide-info
        const activeSlide = slides[activeIndex];
        if (activeSlide && sliderWrapper) {
            const overlay = activeSlide.dataset.overlay || 'rgba(255,255,255,0.3)';
            sliderWrapper.style.setProperty('--overlay-bg', overlay);
        }
        if (activeSlide && slideInfo) {
            const infoColor = activeSlide.dataset.info || '';
            if (infoColor) slideInfo.style.backgroundColor = infoColor;
            renderSlideInfoFor(activeSlide);
        }
    }

    // throttle updates with rAF
    let rafId = null;
    if (slider) {
        slider.addEventListener('scroll', () => {
            if (rafId) cancelAnimationFrame(rafId);
            rafId = requestAnimationFrame(() => {
                setActiveNav();
                rafId = null;
            });
        });
    }

    // nav clicks — prevent default and scroll the slider horizontally
    navLinks.forEach((link, i) => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // stop fragment navigation / vertical jump
            if (!slider || !slides[i]) return;
            // compute left position inside slider and smooth-scroll only horizontally
            const left = slides[i].offsetLeft;
            slider.scrollTo({ left, behavior: 'smooth' });
            // update active state after a short delay
            setTimeout(setActiveNav, 160);
        });
    });

    // goTo uses horizontal scroll as well
    function goTo(index) {
        const target = Math.max(0, Math.min(slides.length - 1, index));
        if (!slider || !slides[target]) return;
        slider.scrollTo({ left: slides[target].offsetLeft, behavior: 'smooth' });
        setTimeout(setActiveNav, 160);
    }
    if (prevBtn) prevBtn.addEventListener('click', () => goTo(getActiveIndex() - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(getActiveIndex() + 1));

    // initial activation
    setActiveNav();
});
