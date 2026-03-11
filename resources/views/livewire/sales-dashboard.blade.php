<div x-data="salesDashboardData()" x-init="initCharts()">
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-sm-5">
                    <label class="form-label mb-1" for="sales-dashboard-from-date">From</label>
                    <input id="sales-dashboard-from-date" type="date" class="form-control"
                        wire:model.defer="fromDate">
                </div>
                <div class="col-sm-5">
                    <label class="form-label mb-1" for="sales-dashboard-to-date">To</label>
                    <input id="sales-dashboard-to-date" type="date" class="form-control" wire:model.defer="toDate">
                </div>
                <div class="col-sm-2 d-grid">
                    <button class="btn btn-primary d-flex align-items-center justify-content-center"
                        type="button" wire:click="applyDateFilter" wire:loading.attr="disabled"
                        wire:target="applyDateFilter">
                        <span wire:loading wire:target="applyDateFilter"
                            class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        <i class="fas fa-filter me-2" wire:loading.remove wire:target="applyDateFilter"></i>
                        <span wire:loading.remove wire:target="applyDateFilter">Apply</span>
                        <span wire:loading wire:target="applyDateFilter">Applying...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Trends Charty -->
    <div class="card">
        <div class="card-header">Sales Trends</div>
        <div class="card-body">
            <div class="text-muted small" x-show="!hasSalesTrends">
                No data for the selected date range.
            </div>
            <canvas wire:ignore id="salesTrendsChart" x-show="hasSalesTrends"></canvas>
        </div>
    </div>

    <!-- Category Sales Chart -->
    <div class="card mt-3">
        <div class="card-header">Category-wise Sales</div>
        <div class="card-body">
            <div class="text-muted small" x-show="!hasCategorySales">
                No data for the selected date range.
            </div>
            <canvas wire:ignore id="categorySalesChart" x-show="hasCategorySales"></canvas>
        </div>
    </div>

    <!-- Payment Methods Chart -->
    <div class="card mt-3">
        <div class="card-header">Payment Methods</div>
        <div class="card-body">
            <div class="text-muted small" x-show="!hasPaymentMethods">
                No data for the selected date range.
            </div>
            <canvas wire:ignore id="paymentMethodsChart" x-show="hasPaymentMethods"></canvas>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('vendors/chart.js/Chart.bundle.min.js') }}"></script>

    <script>
        function salesDashboardData() {
            return {
                salesTrendsData: @entangle('salesTrends'),
                categorySalesData: @entangle('categorySales'),
                paymentMethodsData: @entangle('paymentMethods'),
                salesTrendsChart: null,
                categorySalesChart: null,
                paymentMethodsChart: null,
                get hasSalesTrends() {
                    return Object.keys(this.salesTrendsData || {}).length > 0;
                },
                get hasCategorySales() {
                    return Object.keys(this.categorySalesData || {}).length > 0;
                },
                get hasPaymentMethods() {
                    return Object.keys(this.paymentMethodsData || {}).length > 0;
                },
                livewireHooked: false,

                initCharts() {
                    this.refreshCharts();

                    this.$watch('salesTrendsData', () => this.refreshCharts());
                    this.$watch('categorySalesData', () => this.refreshCharts());
                    this.$watch('paymentMethodsData', () => this.refreshCharts());

                    if (!this.livewireHooked && window.Livewire) {
                        const componentEl = this.$el.closest('[wire\\:id]');
                        const componentId = componentEl ? componentEl.getAttribute('wire:id') : null;
                        window.Livewire.hook('message.processed', (message, component) => {
                            if (componentId && component.id === componentId) {
                                this.$nextTick(() => this.refreshCharts());
                            }
                        });
                        this.livewireHooked = true;
                    }
                },

                refreshCharts() {
                    if (typeof Chart === 'undefined') {
                        console.warn('Chart.js not loaded');
                        return;
                    }

                    this.$nextTick(() => {
                        this.updateSalesTrendsChart();
                        this.updateCategorySalesChart();
                        this.updatePaymentMethodsChart();
                    });
                },

                destroyCharts() {
                    if (this.salesTrendsChart) {
                        this.salesTrendsChart.destroy();
                        this.salesTrendsChart = null;
                    }
                    if (this.categorySalesChart) {
                        this.categorySalesChart.destroy();
                        this.categorySalesChart = null;
                    }
                    if (this.paymentMethodsChart) {
                        this.paymentMethodsChart.destroy();
                        this.paymentMethodsChart = null;
                    }
                },

                updateSalesTrendsChart() {
                    if (!this.hasSalesTrends) {
                        if (this.salesTrendsChart) {
                            this.salesTrendsChart.destroy();
                            this.salesTrendsChart = null;
                        }
                        return;
                    }

                    const ctx = document.getElementById('salesTrendsChart').getContext('2d');
                    const labels = Object.keys(this.salesTrendsData);
                    const data = Object.values(this.salesTrendsData);

                    if (this.salesTrendsChart) {
                        this.salesTrendsChart.data.labels = labels;
                        this.salesTrendsChart.data.datasets[0].data = data;
                        this.salesTrendsChart.update();
                        return;
                    }

                    this.salesTrendsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Total Sales',
                                data,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                fill: true
                            }]
                        }
                    });
                },

                updateCategorySalesChart() {
                    if (!this.hasCategorySales) {
                        if (this.categorySalesChart) {
                            this.categorySalesChart.destroy();
                            this.categorySalesChart = null;
                        }
                        return;
                    }

                    const ctx = document.getElementById('categorySalesChart').getContext('2d');
                    const labels = Object.keys(this.categorySalesData);
                    const data = Object.values(this.categorySalesData);

                    if (this.categorySalesChart) {
                        this.categorySalesChart.data.labels = labels;
                        this.categorySalesChart.data.datasets[0].data = data;
                        this.categorySalesChart.update();
                        return;
                    }

                    this.categorySalesChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Sales by Category',
                                data,
                                backgroundColor: '#28a745'
                            }]
                        }
                    });
                },

                updatePaymentMethodsChart() {
                    if (!this.hasPaymentMethods) {
                        if (this.paymentMethodsChart) {
                            this.paymentMethodsChart.destroy();
                            this.paymentMethodsChart = null;
                        }
                        return;
                    }

                    const ctx = document.getElementById('paymentMethodsChart').getContext('2d');
                    const labels = Object.keys(this.paymentMethodsData);
                    const data = Object.values(this.paymentMethodsData);

                    if (this.paymentMethodsChart) {
                        this.paymentMethodsChart.data.labels = labels;
                        this.paymentMethodsChart.data.datasets[0].data = data;
                        this.paymentMethodsChart.update();
                        return;
                    }

                    this.paymentMethodsChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels,
                            datasets: [{
                                data,
                                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745']
                            }]
                        }
                    });
                }
            }
        }
    </script>
@endpush
