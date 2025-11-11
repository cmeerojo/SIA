<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Delivery Map</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $delivery->customer->name }} • {{ $delivery->item->brand }} ({{ $delivery->item->size }})</p>
            </div>
            <a href="{{ route('deliveries.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Back to Deliveries</a>
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
                    <!-- Status Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Delivery Status</h3>
                        
                        @php
                            $statusClasses = [
                                'completed' => 'bg-green-100 text-green-800',
                                'ongoing' => 'bg-blue-100 text-blue-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $badgeClass = $statusClasses[$delivery->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp

                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                            @if($delivery->status !== 'completed' && $delivery->status !== 'cancelled')
                                <button onclick="startDelivery({{ $delivery->id }})" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                    Start Delivery
                                </button>
                            @endif
                        </div>

                        <form action="{{ route('deliveries.status.update', $delivery) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="text-sm font-medium text-gray-700 block mb-2">Update Status</label>
                                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                                    <option value="pending" {{ $delivery->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ongoing" {{ $delivery->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ $delivery->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $delivery->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Customer Details</h3>
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
                                <p class="font-medium break-words">{{ $delivery->dropoff_location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Details -->
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

                    <!-- Item Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">Item Details</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Brand:</span>
                                <p class="font-medium">{{ $delivery->item->brand }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Size:</span>
                                <p class="font-medium">{{ $delivery->item->size }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Quantity:</span>
                                <p class="font-medium">{{ $delivery->quantity }}</p>
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

            // Track driver location in real-time if ongoing
            @if($delivery->status === 'ongoing')
                trackDriverLocation();
            @endif
        }

        // Try to geocode and add customer marker
        function addCustomerMarker() {
            const location = "{{ e($delivery->dropoff_location) }}";
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
                        }).addTo(map).bindPopup(`<strong>Dropoff:</strong><br>${location}`);

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

        // Track driver location
        function trackDriverLocation() {
            const interval = setInterval(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(pos => {
                        const lat = pos.coords.latitude;
                        const lon = pos.coords.longitude;
                        
                        // Update location on server
                        fetch(`{{ route('deliveries.location.update', $delivery) }}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ latitude: lat, longitude: lon })
                        }).then(() => {
                            addDriverMarker(lat, lon);
                        });
                    });
                }
            }, 10000); // Update every 10 seconds

            // Stop tracking on page leave
            window.addEventListener('beforeunload', () => clearInterval(interval));
        }

        // Start delivery and request location permission
        function startDelivery(id) {
            if (navigator.geolocation) {
                navigator.geolocation.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        // Update status to ongoing
                        document.querySelector('select[name="status"]').value = 'ongoing';
                        document.querySelector('form').submit();
                    }
                }).catch(() => {
                    // Fallback for browsers that don't support requestPermission
                    alert('Please enable location services to start the delivery');
                });
            } else {
                alert('Geolocation is not supported by your browser');
            }
        }

        // Initialize map on page load
        document.addEventListener('DOMContentLoaded', initMap);
    </script>
</x-app-layout>
