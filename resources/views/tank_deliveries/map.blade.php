<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Tank Delivery Map</h2>
                <p class="text-gray-600 text-sm mt-1">Tank: {{ $delivery->tank?->serial_code ?? 'N/A' }} → {{ $delivery->customer->name ?? 'N/A' }}</p>
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
                    <!-- Tank Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Tank Details</h3>
                        @if($delivery->tank)
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Serial Code:</span>
                                <p class="font-medium">{{ $delivery->tank->serial_code }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Brand:</span>
                                <p class="font-medium">{{ $delivery->tank->brand ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Size:</span>
                                <p class="font-medium">{{ $delivery->tank->size ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <p class="font-medium">{{ ucfirst($delivery->tank->status) }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Tank information not available</p>
                        @endif
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Customer Details</h3>
                        @if($delivery->customer)
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <p class="font-medium">{{ $delivery->customer->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <p class="font-medium">{{ $delivery->customer->email }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Phone:</span>
                                <p class="font-medium">{{ $delivery->customer->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Dropoff Location:</span>
                                <p class="font-medium break-words">{{ $delivery->customer->dropoff_location ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Customer information not available</p>
                        @endif
                    </div>

                    <!-- Driver Details -->
                    @if($delivery->driver)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Driver Details</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <p class="font-medium">{{ $delivery->driver->first_name }} {{ $delivery->driver->last_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Contact:</span>
                                <p class="font-medium">{{ $delivery->driver->contact_info ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">License:</span>
                                <p class="font-medium">{{ $delivery->driver->license ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Start Tracking Button -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <button onclick="startTracking({{ $delivery->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded transition">
                            📍 Start GPS Tracking
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Enable real-time driver location updates</p>
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-gray-500 text-sm">No driver assigned to this delivery</p>
                    </div>
                    @endif

                    <!-- Delivery Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Delivery Info</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Date Delivered:</span>
                                <p class="font-medium">{{ $delivery->date_delivered ? $delivery->date_delivered->format('M d, Y H:i') : 'Pending' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <p class="font-medium">{{ $delivery->created_at ? $delivery->created_at->format('M d, Y H:i') : 'N/A' }}</p>
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
        let trackingInterval;
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

            // Add customer marker if location available
            addCustomerMarker();
            
            // Add driver marker if available
            if ({{ $delivery->driver_latitude ?? 'null' }} && {{ $delivery->driver_longitude ?? 'null' }}) {
                addDriverMarker({{ $delivery->driver_latitude }}, {{ $delivery->driver_longitude }});
            }
        }

        // Try to geocode and add customer marker
        function addCustomerMarker() {
            const location = "{{ e($delivery->customer->dropoff_location ?? '') }}";
            if (!location) return;

            // Using Nominatim API (OpenStreetMap's geocoding service)
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        
                        customerMarker = L.marker([lat, lon], {
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            })
                        }).addTo(map).bindPopup(`<strong>Customer:</strong><br>{{ $delivery->customer->name }}<br><strong>Location:</strong><br>${location}`);

                        map.setView([lat, lon], 14);
                    }
                })
                .catch(err => console.error('Geocoding error:', err));
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
            }
        }

        // Start GPS tracking
        function startTracking(id) {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            // Request permission first
            if (navigator.permissions && navigator.permissions.query) {
                navigator.permissions.query({ name: 'geolocation' }).then(permission => {
                    if (permission.state === 'granted' || permission.state === 'prompt') {
                        startContinuousTracking();
                    } else {
                        alert('Location permission denied');
                    }
                });
            } else {
                // Fallback for browsers that don't support permissions API
                startContinuousTracking();
            }
        }

        // Continuous tracking
        function startContinuousTracking() {
            navigator.geolocation.getCurrentPosition(pos => {
                updateLocation(pos.coords.latitude, pos.coords.longitude);
            });

            // Update location every 10 seconds
            trackingInterval = setInterval(() => {
                navigator.geolocation.getCurrentPosition(pos => {
                    updateLocation(pos.coords.latitude, pos.coords.longitude);
                });
            }, 10000);

            alert('GPS tracking started! Updates every 10 seconds.');
            window.addEventListener('beforeunload', () => clearInterval(trackingInterval));
        }

        // Update location on server
        function updateLocation(lat, lon) {
            fetch(`{{ route('tank.deliveries.location.update', $delivery) }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ latitude: lat, longitude: lon })
            }).then(res => res.json())
              .then(data => {
                  if (data.success) {
                      addDriverMarker(lat, lon);
                  }
              })
              .catch(err => console.error('Location update error:', err));
        }

        // Initialize map on page load
        document.addEventListener('DOMContentLoaded', initMap);
    </script>
</x-app-layout>
