<x-app-layout>
    <div class="mb-3">
        <h4 class="mb-1">UI Asset Diagnostics</h4>
        <p class="mb-0 text-muted">
            Checked at {{ $checkedAt->format('Y-m-d H:i:s') }} |
            APP_URL: <code>{{ $appUrl }}</code> |
            Request Host: <code>{{ $requestBaseUrl }}</code>
        </p>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Asset</th>
                            <th>Generated URL</th>
                            <th>Scheme</th>
                            <th>Local File</th>
                            <th>Size</th>
                            <th>Probe</th>
                            <th>HTTP Status</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $row)
                            @php
                                $status = $row['http_status'];
                                $httpOk = is_int($status) && $status >= 200 && $status < 400;
                                $schemeOk = $row['scheme'] === 'https';
                                $localOk = $row['local_exists'] === true;
                                $rowWarning = !($httpOk && $schemeOk && $localOk);
                            @endphp
                            <tr @class(['table-warning' => $rowWarning])>
                                <td><code>{{ $row['path'] }}</code></td>
                                <td class="text-break"><code>{{ $row['url'] }}</code></td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-success' => $schemeOk,
                                        'bg-danger' => !$schemeOk,
                                    ])>{{ strtoupper($row['scheme']) }}</span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-success' => $localOk,
                                        'bg-danger' => !$localOk,
                                    ])>{{ $localOk ? 'YES' : 'NO' }}</span>
                                </td>
                                <td>{{ $row['local_size'] ? number_format($row['local_size']) . ' bytes' : '-' }}</td>
                                <td>{{ $row['request_method'] ?? '-' }}</td>
                                <td>
                                    @if (is_null($status))
                                        <span class="badge bg-secondary">N/A</span>
                                    @else
                                        <span @class([
                                            'badge',
                                            'bg-success' => $httpOk,
                                            'bg-danger' => !$httpOk,
                                        ])>{{ $status }}</span>
                                    @endif
                                </td>
                                <td class="text-danger small text-break">{{ $row['error'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
