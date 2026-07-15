const OSM_TILE_URL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
const OSM_ATTRIBUTION = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

// Leaflet (~150KB) was previously loaded on every single page via a static top-level
// import, even pages with no map at all -- this loads it only when a map component
// actually mounts, as its own separate chunk fetched on demand.
let leafletPromise = null;

function loadLeaflet() {
    if (!leafletPromise) {
        leafletPromise = Promise.all([
            import('leaflet'),
            import('leaflet/dist/leaflet.css'),
            import('leaflet/dist/images/marker-icon.png'),
            import('leaflet/dist/images/marker-icon-2x.png'),
            import('leaflet/dist/images/marker-shadow.png'),
        ]).then(([leafletModule, , markerIcon, markerIcon2x, markerShadow]) => {
            const L = leafletModule.default;

            // Leaflet's default marker icon URLs are relative paths baked in at build time
            // and don't resolve through Vite -- markers render as broken images unless
            // explicitly reconfigured.
            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: markerIcon2x.default,
                iconUrl: markerIcon.default,
                shadowUrl: markerShadow.default,
            });

            return L;
        });
    }

    return leafletPromise;
}

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

window.productGallery = function (images) {
    return {
        images,
        activeIndex: 0,
        startX: 0,
        deltaX: 0,

        onPointerDown(event) {
            this.startX = event.clientX ?? event.touches?.[0]?.clientX ?? 0;
            this.deltaX = 0;
        },

        onPointerMove(event) {
            if (this.startX === 0 && this.deltaX === 0) return;
            const x = event.clientX ?? event.touches?.[0]?.clientX ?? this.startX;
            this.deltaX = x - this.startX;
        },

        onPointerUp() {
            if (this.deltaX < -50) this.next();
            else if (this.deltaX > 50) this.prev();

            this.startX = 0;
            this.deltaX = 0;
        },

        next() {
            this.activeIndex = Math.min(this.activeIndex + 1, this.images.length - 1);
        },

        prev() {
            this.activeIndex = Math.max(this.activeIndex - 1, 0);
        },

        goTo(index) {
            this.activeIndex = index;
        },
    };
};

window.locationPicker = function (lat, long) {
    return {
        lat: lat || null,
        long: long || null,
        map: null,
        marker: null,
        L: null,

        async init() {
            const hasPin = this.lat && this.long;
            const startLat = hasPin ? this.lat : -2.5;
            const startLong = hasPin ? this.long : 118;

            this.L = await loadLeaflet();

            this.map = this.L.map(this.$refs.map, { scrollWheelZoom: false }).setView([startLat, startLong], hasPin ? 16 : 4);
            this.L.tileLayer(OSM_TILE_URL, { attribution: OSM_ATTRIBUTION }).addTo(this.map);

            if (hasPin) this.setMarker(this.lat, this.long);

            this.map.on('click', (event) => this.setMarker(event.latlng.lat, event.latlng.lng));

            // A picker embedded in a hidden/just-shown container (e.g. a Filament tab) can
            // initialize with a zero-size viewport -- force Leaflet to remeasure once visible.
            setTimeout(() => this.map.invalidateSize(), 150);
        },

        setMarker(lat, long) {
            this.lat = lat;
            this.long = long;

            if (this.marker) {
                this.marker.setLatLng([lat, long]);
            } else {
                this.marker = this.L.marker([lat, long]).addTo(this.map);
            }
        },

        useMyLocation() {
            if (!navigator.geolocation) return;

            navigator.geolocation.getCurrentPosition((pos) => {
                this.setMarker(pos.coords.latitude, pos.coords.longitude);
                this.map.setView([pos.coords.latitude, pos.coords.longitude], 16);
            }, () => {});
        },
    };
};

window.umkmMap = function (lat, long, name) {
    return {
        async init() {
            const L = await loadLeaflet();
            const map = L.map(this.$refs.map, { scrollWheelZoom: false }).setView([lat, long], 16);
            L.tileLayer(OSM_TILE_URL, { attribution: OSM_ATTRIBUTION }).addTo(map);
            L.marker([lat, long]).addTo(map).bindPopup(name);
        },
    };
};

window.wilayahCascade = function (old) {
    return {
        selected: {
            provinsi: old.provinsi || '',
            kabupaten: old.kabupaten || '',
            kecamatan: old.kecamatan || '',
            kelurahan: old.kelurahan || '',
        },
        kabupatenOptions: [],
        kecamatanOptions: [],
        kelurahanOptions: [],

        async init() {
            // Re-hydrate dependent lists so old() selections survive a validation-failure re-render.
            if (this.selected.provinsi) await this.loadKabupaten(this.selected.provinsi, true);
            if (this.selected.kabupaten) await this.loadKecamatan(this.selected.kabupaten, true);
            if (this.selected.kecamatan) await this.loadKelurahan(this.selected.kecamatan, true);
        },

        async onProvinsiChange() {
            this.selected.kabupaten = '';
            this.selected.kecamatan = '';
            this.selected.kelurahan = '';
            this.kecamatanOptions = [];
            this.kelurahanOptions = [];
            await this.loadKabupaten(this.selected.provinsi);
        },

        async onKabupatenChange() {
            this.selected.kecamatan = '';
            this.selected.kelurahan = '';
            this.kelurahanOptions = [];
            await this.loadKecamatan(this.selected.kabupaten);
        },

        async onKecamatanChange() {
            this.selected.kelurahan = '';
            await this.loadKelurahan(this.selected.kecamatan);
        },

        async loadKabupaten(id, preserve = false) {
            const response = await fetch(`/wilayah/regencies/${id}`);
            this.kabupatenOptions = response.ok ? await response.json() : [];
            if (!preserve) this.selected.kabupaten = '';
        },

        async loadKecamatan(id, preserve = false) {
            const response = await fetch(`/wilayah/districts/${id}`);
            this.kecamatanOptions = response.ok ? await response.json() : [];
            if (!preserve) this.selected.kecamatan = '';
        },

        async loadKelurahan(id, preserve = false) {
            const response = await fetch(`/wilayah/villages/${id}`);
            this.kelurahanOptions = response.ok ? await response.json() : [];
            if (!preserve) this.selected.kelurahan = '';
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

    Alpine.store('cart', {
        items: JSON.parse(localStorage.getItem('warlok_cart') || '[]'),

        persist() {
            localStorage.setItem('warlok_cart', JSON.stringify(this.items));
        },

        find(productId) {
            return this.items.find((item) => item.productId === productId);
        },

        qtyFor(productId) {
            return this.find(productId)?.qty ?? 0;
        },

        add(productId, qty = 1) {
            const existing = this.find(productId);

            if (existing) {
                existing.qty += qty;
            } else {
                this.items.push({ productId, qty });
            }

            this.persist();
        },

        remove(productId) {
            this.items = this.items.filter((item) => item.productId !== productId);
            this.persist();
        },

        setQty(productId, qty) {
            if (qty <= 0) {
                this.remove(productId);

                return;
            }

            const existing = this.find(productId);
            if (existing) existing.qty = qty;

            this.persist();
        },

        clear() {
            this.items = [];
            this.persist();
        },

        totalCount() {
            return this.items.reduce((sum, item) => sum + item.qty, 0);
        },
    });
});
