<div x-data="salesDashboardData()" x-init="initCharts()">

    <!-- Sales Trends Chart -->
    <div class="card">
        <div class="card-header">Monthly Sales Trends</div>
        <div class="card-body">
            <canvas id="salesTrendsChart" x-show="Object.keys(salesTrendsData).length > 0"></canvas>
        </div>
    </div>

    <!-- Category Sales Chart -->
    <div class="card mt-3">
        <div class="card-header">Category-wise Sales</div>
        <div class="card-body">
            <canvas id="categorySalesChart" x-show="Object.keys(categorySalesData).length > 0"></canvas>
        </div>
    </div>

    <!-- Payment Methods Chart -->
    <div class="card mt-3">
        <div class="card-header">Payment Methods</div>
        <div class="card-body">
            <canvas id="paymentMethodsChart" x-show="Object.keys(paymentMethodsData).length > 0"></canvas>
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('vendors/chart.js/Chart.bundle.min.js') }}"></script>

    <script>
        function salesDashboardData() {
            return {
                salesTrendsData: @json($salesTrends),
                categorySalesData: @json($categorySales),
                paymentMethodsData: @json($paymentMethods),

                initCharts() {
                    console.log('Initializing charts...');

                    if (Object.keys(this.salesTrendsData).length) {
                        this.initializeSalesTrendsChart();
                    } else {
                        console.log('Sales Trends Data is empty');
                    }

                    if (Object.keys(this.categorySalesData).length) {
                        this.initializeCategorySalesChart();
                    } else {
                        console.log('Category Sales Data is empty');
                    }

                    if (Object.keys(this.paymentMethodsData).length) {
                        this.initializePaymentMethodsChart();
                    } else {
                        console.log('Payment Methods Data is empty');
                    }
                },

                initializeSalesTrendsChart() {
                    const ctx = document.getElementById('salesTrendsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(this.salesTrendsData),
                            datasets: [{
                                label: 'Total Sales',
                                data: Object.values(this.salesTrendsData),
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                fill: true
                            }]
                        }
                    });
                    console.log('Sales Trends Chart initialized');
                },

                initializeCategorySalesChart() {
                    const ctx = document.getElementById('categorySalesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(this.categorySalesData),
                            datasets: [{
                                label: 'Sales by Category',
                                data: Object.values(this.categorySalesData),
                                backgroundColor: '#28a745'
                            }]
                        }
                    });
                    console.log('Category Sales Chart initialized');
                },

                initializePaymentMethodsChart() {
                    const ctx = document.getElementById('paymentMethodsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(this.paymentMethodsData),
                            datasets: [{
                                data: Object.values(this.paymentMethodsData),
                                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745']
                            }]
                        }
                    });
                    console.log('Payment Methods Chart initialized');
                }
            }
        }
    </script>
@endpush
