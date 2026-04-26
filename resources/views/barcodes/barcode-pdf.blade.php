<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Barcodes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90mm, 1fr));
            gap: 10mm;
            padding: 10mm;
        }

        .barcode-item {
            border: 1px solid #ddd;
            padding: 8mm;
            text-align: center;
            background: white;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .barcode-label {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-word;
            max-height: 30px;
            overflow: hidden;
        }

        .barcode-image {
            margin: 8px 0 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 42px;
        }

        .barcode-image img {
            max-width: 100%;
            max-height: 48px;
            height: auto;
            display: block;
        }

        .barcode-text {
            font-size: 9px;
            font-weight: bold;
            margin-top: 5px;
            word-break: break-all;
            font-family: monospace;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .barcode-grid {
                padding: 5mm;
                gap: 5mm;
            }

            .barcode-item {
                page-break-inside: avoid;
            }
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="barcode-grid">
        @foreach ($barcodes as $index => $barcode)
            @if ($index > 0 && $index % $itemsPerPage == 0)
                </div>
                <div class="page-break"></div>
                <div class="barcode-grid">
            @endif

            <div class="barcode-item">
                <div class="barcode-label">
                    {{ $barcode['product_name'] }}
                </div>

                <div class="barcode-image">
                    @if (!empty($barcode['barcode_data']))
                        <img src="data:image/png;base64,{{ $barcode['barcode_data'] }}" alt="Barcode {{ $barcode['barcode'] }}">
                    @else
                        <span style="font-size: 9px; color: #888;">Barcode image unavailable</span>
                    @endif
                </div>

                <div class="barcode-text">
                    {{ $barcode['barcode'] }}
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 20mm; text-align: center; font-size: 10px; color: #999;">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }} | Total: {{ count($barcodes) }} barcodes</p>
    </div>
</body>
</html>
