<table style="width: 100%; border-collapse: collapse; margin-top: 15px; page-break-after: always;">
    <thead>
        <tr style="background-color: #f0f0f0; border: 1px solid black;">
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">Sr.</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">HSN</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">BATCH NO.</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">QTY</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">MFG</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">PKG</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">Product Name</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">MRP</th>
            <th style="background-color:yellow; border: 1px solid black; padding: 2px 3px; font-size:8px">Rate</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">AGENCY NAME</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">ADDRESS</th>
            <th style="border: 1px solid black; padding: 2px 3px; font-size:8px">BILL DATE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->id }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->hsn_code ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->batch_no ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->quantity ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->mfg?->name ?? $product->mfg_name ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->pkg ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->name ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ number_format($product->mrp_price, 2) ?? 'N/A' }}</td>
                <td style="background-color:yellow; border: 1px solid black; padding: 2px 3px; font-size:8px">{{ number_format($product->price, 2) ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->agency?->name ?? $product->agency_name ?? 'N/A' }}</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">TOHANA</td>
                <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $product->bill_date ? date('M-y', strtotime($product->bill_date)) : 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
