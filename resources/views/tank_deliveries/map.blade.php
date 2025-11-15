<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Tank Delivery Map</h2>
                <p class="text-gray-600 text-sm mt-1">Sale: #{{ $delivery->sale?->id ?? 'N/A' }} → {{ $delivery->customer?->full_name ?? 'N/A' }} ({{ $delivery->sale?->tanks->count() ?? 0 }} tank{{ ($delivery->sale?->tanks->count() ?? 0) === 1 ? '' : 's' }})</p>
            </div>
            <a href="{{ route('tank.deliveries.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Back to Deliveries</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div id="map" class="w-full h-96 md:h-screen bg-gray-100"></div>
                    </div>
                </div>

                <!-- Delivery Details Section -->
                <div class="space-y-4">
                    <!-- Route Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Route</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Start:</span>
                                <p class="font-medium break-words">{{ $delivery->start_location ?: 'Legal Street, Pantukan, Davao de Oro' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Drop-off:</span>
                                @php
                                    $c = $delivery->customer;
                                    $dropStreet = $c?->dropoff_street;
                                    $dropLandmark = $c?->dropoff_landmark;
                                    $dropCity = $c?->dropoff_city;
                                    $composed = collect([$dropStreet, $dropLandmark, $dropCity])->filter()->implode(', ');
                                    $displayDrop = $delivery->dropoff_location ?: ($composed ?: ($c?->dropoff_location ?: 'N/A'));
                                @endphp
                                <p class="font-medium break-words">{{ $displayDrop }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">ETA:</span>
                                <p id="eta-route" class="font-medium">—</p>
                            </div>
                        </div>
                    </div>
                    <!-- Tank Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Tank Details</h3>
                        <div class="space-y-2 text-sm">
                            @if($delivery->sale && $delivery->sale->tanks->isNotEmpty())
                                @foreach($delivery->sale->tanks as $t)
                                    <div>
                                        <span class="text-gray-600">Serial Code:</span>
                                        <p class="font-medium">{{ $t->serial_code ?? 'N/A' }} — {{ $t->brand ?? 'N/A' }} ({{ $t->size ?? 'N/A' }})</p>
                                    </div>
                                @endforeach
                            @else
                                <div>
                                    <span class="text-gray-600">Tank:</span>
                                    <p class="font-medium">{{ $delivery->tank?->serial_code ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Customer Details</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <p class="font-medium">{{ $delivery->customer?->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <p class="font-medium">{{ $delivery->customer?->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Phone:</span>
                                <p class="font-medium">{{ $delivery->customer?->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Dropoff Location:</span>
                                <p class="font-medium break-words">{{ $delivery->customer?->dropoff_location ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Driver Details</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <p class="font-medium">{{ trim(($delivery->driver?->first_name ?? '').' '.($delivery->driver?->last_name ?? '')) ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Contact:</span>
                                <p class="font-medium">{{ $delivery->driver?->contact_info ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">License:</span>
                                <p class="font-medium">{{ $delivery->driver?->license ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Vehicle Details</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Plate Number:</span>
                                <p class="font-medium">{{ $delivery->vehicle?->plate_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Model:</span>
                                <p class="font-medium">{{ $delivery->vehicle?->model ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Color:</span>
                                <p class="font-medium">{{ $delivery->vehicle?->color ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status + ETA Card -->
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-bold text-lg">Status</h3>
                                        <span id="eta-badge" class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">ETA: —</span>
                                    </div>
                                    <form action="{{ route('tank.deliveries.status.update', ['tank_delivery' => $delivery->getRouteKey()]) }}" method="POST" class="flex flex-wrap items-center gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="border-2 rounded-md px-3 py-2.5 text-base leading-6 min-w-[12rem] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="pending" {{ ($delivery->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="started" {{ ($delivery->status ?? 'pending') === 'started' ? 'selected' : '' }}>Started</option>
                                            <option value="completed" {{ ($delivery->status ?? 'pending') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-base leading-6 px-4 py-2.5 rounded-md">Update</button>
                                    </form>
                                </div>


                    <!-- Delivery Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Delivery Info</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Date Delivered:</span>
                                <p class="font-medium">{{ $delivery->date_delivered ? \App\Providers\AppServiceProvider::formatPrettyDate($delivery->date_delivered, true) : 'Pending' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <p class="font-medium">{{ $delivery->created_at ? \App\Providers\AppServiceProvider::formatPrettyDate($delivery->created_at, true) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        let map;
        let driverMarker;
        let customerMarker;
        let deliveryLine;
        let startMarker;
        let startToCustomerLine;
        const deliveryId = {{ $delivery->id }};

        // Initialize map
        function initMap() {
            // Default center (Manila, Philippines - adjust as needed)
            const defaultCenter = [14.5995, 120.9842];
            
            map = L.map('map').setView(defaultCenter, 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add start and customer markers if locations available
            addStartMarker();
            addCustomerMarker();
            
            // Add driver marker if available
            const driverLat = @json($delivery->driver_latitude);
            const driverLon = @json($delivery->driver_longitude);
            if (driverLat !== null && driverLon !== null) {
                addDriverMarker(driverLat, driverLon);
            }
        }

        // Geocode and add start marker
        function addStartMarker() {
            const startLoc = "{{ e($delivery->start_location ?: 'Legal Street, Pantukan, Davao de Oro') }}";
            if (!startLoc) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(startLoc)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        startMarker = L.marker([lat, lon], {
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            })
                        }).addTo(map).bindPopup(`<strong>Start Location</strong><br>${startLoc}`);

                        drawStartToCustomer();
                        updateEta();
                    }
                })
                .catch(err => console.error('Geocoding error (start):', err));
        }

        // Try to geocode and add customer marker
        function addCustomerMarker() {
            const legacy = "{{ e($delivery->customer?->dropoff_location ?? '') }}";
            const street = "{{ e($delivery->customer?->dropoff_street ?? '') }}";
            const city = "{{ e($delivery->customer?->dropoff_city ?? '') }}";
            const landmark = "{{ e($delivery->customer?->dropoff_landmark ?? '') }}";

            // Build a list of increasingly broad queries, preferring precise combinations.
            const queries = [];
            const composed = [street, landmark, city].filter(Boolean).join(', ');
            if (composed) queries.push(composed + ', Philippines');
            if (landmark && city) queries.push([landmark, city, 'Philippines'].join(', '));
            if (street && city) queries.push([street, city, 'Philippines'].join(', '));
            if (legacy) queries.push(legacy + ', Philippines');

            async function geocodeSequential(qs){
                for (const q of qs){
                    try {
                        const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=ph&q=${encodeURIComponent(q)}`;
                        const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
                        if (!res.ok) continue;
                        const data = await res.json();
                        if (Array.isArray(data) && data.length > 0){
                            return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon), used: q };
                        }
                    } catch (e) {
                        // try next
                    }
                }
                return null;
            }

            geocodeSequential(queries).then(result => {
                if (!result) return; // no marker if not found
                customerMarker = L.marker([result.lat, result.lon], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map).bindPopup(`<strong>Customer:</strong><br>{{ $delivery->customer?->name ?? 'N/A' }}<br><strong>Location:</strong><br>${queries[0] || legacy}`);

                map.setView([result.lat, result.lon], 14);
                drawStartToCustomer();
                updateEta();
            }).catch(err => console.error('Geocoding error:', err));
        }

        function drawStartToCustomer() {
            if (startMarker && customerMarker) {
                if (startToCustomerLine) map.removeLayer(startToCustomerLine);
                startToCustomerLine = L.polyline([
                    startMarker.getLatLng(),
                    customerMarker.getLatLng()
                ], {
                    color: 'red',
                    weight: 3,
                    opacity: 0.6
                }).addTo(map);
                updateEta();
            }
        }

        // Add/update driver marker
        function addDriverMarker(lat, lon) {
            if (driverMarker) {
                driverMarker.setLatLng([lat, lon]);
            } else {
                driverMarker = L.marker([lat, lon], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map).bindPopup('<strong>Driver Location</strong>');
            }

            // Draw line from driver to customer
            if (customerMarker) {
                if (deliveryLine) map.removeLayer(deliveryLine);
                deliveryLine = L.polyline([
                    driverMarker.getLatLng(),
                    customerMarker.getLatLng()
                ], {
                    color: 'blue',
                    weight: 2,
                    opacity: 0.7,
                    dashArray: '5, 5'
                }).addTo(map);
                updateEta();
            }
        }

        // GPS tracking removed: no client geolocation or server updates

        // Initialize map on page load
        document.addEventListener('DOMContentLoaded', initMap);
                    // Haversine distance + ETA calculator
                    // Update ETA in UI selecting origin: driver if available else start
                    function updateEta() {
                        const etaBadge = document.getElementById('eta-badge');
                        const etaRoute = document.getElementById('eta-route');
                        if (!customerMarker) {
                            if (etaBadge) etaBadge.textContent = 'ETA: —';
                            if (etaRoute) etaRoute.textContent = '—';
                            return;
                        }
                        const origin = driverMarker ? driverMarker.getLatLng() : (startMarker ? startMarker.getLatLng() : null);
                        if (!origin) {
                            if (etaBadge) etaBadge.textContent = 'ETA: —';
                            if (etaRoute) etaRoute.textContent = '—';
                            return;
                        }
                        // Try road-distance first; fall back to straight-line if routing fails
                        const dest = customerMarker.getLatLng();
                        if (etaBadge) etaBadge.textContent = 'ETA: calculating…';
                        if (etaRoute) etaRoute.textContent = 'calculating…';
                        roadDistanceKm(origin, dest)
                            .then(distanceKm => {
                                if (distanceKm != null) {
                                    const etaText = computeEtaFromDistance(distanceKm);
                                    if (etaBadge) etaBadge.textContent = `ETA: ${etaText}`;
                                    if (etaRoute) etaRoute.textContent = etaText;
                                } else {
                                    const etaText = computeEtaHaversine(origin, dest);
                                    if (etaBadge) etaBadge.textContent = `ETA: ${etaText}`;
                                    if (etaRoute) etaRoute.textContent = etaText;
                                }
                            })
                            .catch(() => {
                                const etaText = computeEtaHaversine(origin, dest);
                                if (etaBadge) etaBadge.textContent = `ETA: ${etaText}`;
                                if (etaRoute) etaRoute.textContent = etaText;
                            });
                    }

                    // Road distance (km) via OSRM demo server; returns Promise<number|null>
                    function roadDistanceKm(p1, p2) {
                        const url = `https://router.project-osrm.org/route/v1/driving/${p1.lng},${p1.lat};${p2.lng},${p2.lat}?overview=false&alternatives=false&steps=false`;
                        return fetch(url)
                            .then(res => res.ok ? res.json() : Promise.reject())
                            .then(json => {
                                if (json && json.code === 'Ok' && json.routes && json.routes.length > 0) {
                                    const meters = json.routes[0].distance;
                                    return meters / 1000.0;
                                }
                                return null;
                            })
                            .catch(() => null);
                    }

                    // Haversine distance + ETA string (fallback)
                    function computeEtaHaversine(p1, p2) {
                        const R = 6371; // km
                        const toRad = (d) => d * Math.PI / 180;
                        const dLat = toRad(p2.lat - p1.lat);
                        const dLon = toRad(p2.lng - p1.lng);
                        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                  Math.cos(toRad(p1.lat)) * Math.cos(toRad(p2.lat)) *
                                  Math.sin(dLon/2) * Math.sin(dLon/2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                        const distanceKm = R * c;
                        const avgSpeedKmh = 35; // heuristic average speed (distance-based ETA)
                        const hours = distanceKm / avgSpeedKmh;
                        const minutes = Math.round(hours * 60);
                        if (minutes < 60) return `${minutes} min (${distanceKm.toFixed(1)} km)`;
                        const h = Math.floor(minutes / 60);
                        const m = minutes % 60;
                        return `${h}h ${m}m (${distanceKm.toFixed(1)} km)`;
                    }

                    // Compute ETA string from given road distance (km)
                    function computeEtaFromDistance(distanceKm) {
                        const avgSpeedKmh = 35; // heuristic average speed (distance-based ETA)
                        const hours = distanceKm / avgSpeedKmh;
                        const minutes = Math.round(hours * 60);
                        if (minutes < 60) return `${minutes} min (${distanceKm.toFixed(1)} km road)`;
                        const h = Math.floor(minutes / 60);
                        const m = minutes % 60;
                        return `${h}h ${m}m (${distanceKm.toFixed(1)} km road)`;
                    }
    </script>
</x-app-layout>
