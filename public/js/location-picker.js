// Plain global script for the Mitra panel (Filament has its own asset pipeline, separate
// from the public site's Vite bundle) -- relies on the Leaflet CDN script (registered
// alongside this file via FilamentAsset::register()) having already defined window.L.
window.locationPicker = function (lat, long) {
    return {
        // lat/long arrive as strings (Eloquent decimal casts serialize that way via @js()).
        lat: lat ? Number(lat) : null,
        long: long ? Number(long) : null,
        map: null,
        marker: null,

        init() {
            // Defensive guard: the wrapping element has wire:ignore so Livewire won't morph
            // it after first render, but if init() ever runs twice on the same node anyway,
            // L.map() throws on an already-bound container -- don't let that abort startup.
            try {
                this.map = L.map(this.$refs.map, { scrollWheelZoom: false });
            } catch (e) {
                return;
            }

            const hasPin = this.lat && this.long;
            const startLat = hasPin ? this.lat : -2.5;
            const startLong = hasPin ? this.long : 118;

            this.map.setView([startLat, startLong], hasPin ? 16 : 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(this.map);

            if (hasPin) this.setMarker(this.lat, this.long);

            this.map.on('click', (event) => this.setMarker(event.latlng.lat, event.latlng.lng));

            setTimeout(() => this.map.invalidateSize(), 150);
        },

        setMarker(lat, long) {
            this.lat = lat;
            this.long = long;

            // Writing straight into the Livewire component here (rather than an Alpine
            // $watch in the blade view) because $watch registered from x-init didn't
            // reliably fire on mutations coming from Leaflet's own event handlers.
            this.$wire.set('data.lat', lat);
            this.$wire.set('data.long', long);

            if (this.marker) {
                this.marker.setLatLng([lat, long]);
            } else {
                this.marker = L.marker([lat, long]).addTo(this.map);
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
