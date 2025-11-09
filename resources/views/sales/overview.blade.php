<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl">Sales Overview</h2>
            <div>
                <a href="{{ route('sales.manage') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    Manage Sales
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Amount Today -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-6 rounded-lg shadow md:row-span-2">
                    <div class="flex flex-col h-full">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex items-center justify-center w-14 h-14 bg-orange-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-orange-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-base text-gray-600">Total Amount Today</p>
                                <p class="text-3xl font-bold text-gray-900">₱{{ number_format($totalAmountToday, 2) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Completed Sales</span>
                                    <span class="text-lg font-semibold text-green-600">₱{{ number_format($completedAmountToday, 2) }}</span>
                                </div>
                            </div>
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Pending Sales</span>
                                    <span class="text-lg font-semibold text-yellow-600">₱{{ number_format($pendingAmountToday, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Orders</p>
                            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-yellow-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pending Orders</p>
                            <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Completed Orders</p>
                            <p class="text-2xl font-bold">{{ $completedOrders }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Sales Amount Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">Last 7 Days Sales</h3>
                    <canvas id="salesChart" class="w-full" height="300"></canvas>
                </div>

                <!-- Orders Count Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">Orders by Day</h3>
                    <canvas id="ordersChart" class="w-full" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales data for the last 7 days
        const salesData = @json($last7Days);
        
        // Sales Amount Chart
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: salesData.map(d => d.date),
                datasets: [
                    {
                        label: 'Completed Sales',
                        data: salesData.map(d => d.completed),
                        backgroundColor: '#16a34a',
                    },
                    {
                        label: 'Pending Sales',
                        data: salesData.map(d => d.pending),
                        backgroundColor: '#ca8a04',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { stacked: true },
                    y: { 
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Orders Count Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: salesData.map(d => d.date),
                datasets: [{
                    label: 'Number of Orders',
                    data: salesData.map(d => d.count),
                    backgroundColor: '#0284c7',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    @include('sales._add_sale_modal')
</x-app-layout>
