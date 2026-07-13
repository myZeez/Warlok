window.stackedCarousel = function (items) {
    return {
        items,
        dragging: false,
        startX: 0,
        deltaX: 0,

        cardTransform(index) {
            const rotations = [0, -6, 6, -10];
            const translateY = [0, 10, 20, 28];
            const scales = [1, 0.96, 0.92, 0.88];

            let transform = '';
            if (index === 0 && this.deltaX !== 0) {
                transform += `translateX(${this.deltaX}px) rotate(${this.deltaX / 20}deg) `;
            }
            transform += `translateY(${translateY[index] ?? 30}px) rotate(${rotations[index] ?? -10}deg) scale(${scales[index] ?? 0.88})`;

            return transform;
        },

        cardZIndex(index) {
            return 10 - index;
        },

        cardTransition(index) {
            return this.dragging && index === 0 ? 'none' : 'transform 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
        },

        onPointerDown(event) {
            this.dragging = true;
            this.startX = event.clientX ?? event.touches?.[0]?.clientX ?? 0;
        },

        onPointerMove(event) {
            if (!this.dragging) return;
            const x = event.clientX ?? event.touches?.[0]?.clientX ?? this.startX;
            this.deltaX = x - this.startX;
        },

        onPointerUp() {
            if (!this.dragging) return;
            this.dragging = false;

            if (Math.abs(this.deltaX) > 80) {
                this.items.push(this.items.shift());
            }

            this.deltaX = 0;
        },
    };
};

window.featuredCarousel = function (count) {
    return {
        count,
        activeIndex: 0,
        timer: null,

        init() {
            const track = this.$refs.track;
            track.addEventListener('scroll', () => this.syncActiveFromScroll());
            track.addEventListener('scrollend', () => this.loopIfNeeded());
            this.syncActiveFromScroll();

            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.play();
            }
        },

        play() {
            this.stop();
            this.timer = setInterval(() => this.advance(), 2800);
        },

        stop() {
            if (this.timer) clearInterval(this.timer);
            this.timer = null;
        },

        advance() {
            this.scrollToIndex(this.activeIndex + 1, true);
        },

        scrollToIndex(index, smooth) {
            const track = this.$refs.track;
            const child = track.children[index];
            if (!child) return;

            const target = child.offsetLeft + child.offsetWidth / 2 - track.clientWidth / 2;
            track.scrollTo({ left: target, behavior: smooth ? 'smooth' : 'auto' });
        },

        loopIfNeeded() {
            if (this.activeIndex >= this.count) {
                this.scrollToIndex(this.activeIndex - this.count, false);
            }
        },

        syncActiveFromScroll() {
            const track = this.$refs.track;
            const center = track.scrollLeft + track.clientWidth / 2;
            let closest = 0;
            let closestDistance = Infinity;

            Array.from(track.children).forEach((child, index) => {
                const childCenter = child.offsetLeft + child.offsetWidth / 2;
                const distance = Math.abs(childCenter - center);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closest = index;
                }
            });

            this.activeIndex = closest;
        },

        isActive(index) {
            return index === this.activeIndex
                || index === this.activeIndex - this.count
                || index === this.activeIndex + this.count;
        },
    };
};

document.addEventListener('alpine:init', () => {
    Alpine.store('favorites', {
        ids: JSON.parse(localStorage.getItem('warlok_favorites') || '[]'),

        has(id) {
            return this.ids.includes(id);
        },

        toggle(id) {
            this.ids = this.has(id)
                ? this.ids.filter((favId) => favId !== id)
                : [...this.ids, id];

            localStorage.setItem('warlok_favorites', JSON.stringify(this.ids));
        },
    });
});
