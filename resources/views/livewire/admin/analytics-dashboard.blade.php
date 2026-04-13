<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Analytics Dashboard</h3>
            <div>
                <select wire:model.live="dateRange" class="form-control form-select" style="width: auto; display: inline-block;">
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                    <option value="year">Last Year</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $this->totalViews }}</h3>
                            <p>Total Page Views</p>
                        </div>
                        <div class="icon"><i class="fas fa-eye"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $this->uniqueVisitors }}</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $this->contactSubmissions }}</h3>
                            <p>Contact Submissions</p>
                        </div>
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $this->unreadContacts }}</h3>
                            <p>Unread Contacts</p>
                        </div>
                        <div class="icon"><i class="fas fa-envelope-open"></i></div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $this->testimonials }}</h3>
                            <p>Testimonials</p>
                        </div>
                        <div class="icon"><i class="fas fa-quote-left"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $this->newsletterSubscribers }}</h3>
                            <p>Total Subscribers</p>
                        </div>
                        <div class="icon"><i class="fas fa-newspaper"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{{ $this->newSubscribers }}</h3>
                            <p>New Subscribers</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-plus"></i></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Top Pages</h4></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead><tr><th>Page</th><th>Views</th></tr></thead>
                                <tbody>
                                    @forelse($this->topPages as $page)
                                        <tr>
                                            <td>{{ $page->page_title ?: $page->page_slug }}</td>
                                            <td>{{ $page->views }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">No data available</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Page Views Over Time</h4></div>
                        <div class="card-body">
                            @if(count($this->pageViewsChart['data']) > 0)
                                <canvas id="pageViewsChart"></canvas>
                            @else
                                <p class="text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($this->pageViewsChart);
            if (chartData.data && chartData.data.length > 0) {
                const ctx = document.getElementById('pageViewsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{ label: 'Page Views', data: chartData.data, borderColor: 'rgba(75, 192, 192, 1)', backgroundColor: 'rgba(75, 192, 192, 0.2)', fill: true, tension: 0.3 }]
                    },
                    options: { responsive: true, scales: { y: { beginAtZero: true } } }
                });
            }
        });
    </script>
    <style>
    .small-box { border-radius: .25rem; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); padding: 20px; color: #fff; position: relative; overflow: hidden; }
    .small-box .inner h3 { font-size: 2rem; font-weight: bold; margin: 0 0 10px 0; }
    .small-box .inner p { margin: 0; font-size: 0.9rem; }
    .small-box .icon { position: absolute; top: 15px; right: 15px; font-size: 4rem; opacity: 0.5; }
    .bg-info { background: #17a2b8 !important; }
    .bg-success { background: #28a745 !important; }
    .bg-warning { background: #ffc107 !important; color: #1f2d3d !important; }
    .bg-danger { background: #dc3545 !important; }
    .bg-primary { background: #007bff !important; }
    .bg-secondary { background: #6c757d !important; }
    .bg-teal { background: #20c997 !important; }
    </style>
</div>