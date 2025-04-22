<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 12px;">

    <!-- Header Section -->
    <div id="sales-invoice" style="width: 96%; margin: 0 auto; padding: 10px; border: 1px solid #000;">
        <h4 style="text-align: center; margin: 0;">Products</h4>

        <table style="width: 100%; margin: 10px auto 0 auto; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <!-- Left Info -->
                <td style="vertical-align: top; padding: 10px; border: 1px solid #000; border-right: none;">
                    <h2 style="color: #D04A02; margin: 3px 0; font-size: 10px;">Guru Nanak Medical Hall.</h2>
                    <p style="margin: 3px 0; font-size: 8px;">
                        JAKHAL ROAD, near GURUDWARA<br>
                        SHRI GRUR TEG BAHADUR SAHIB,<br>
                        Moonak, Punjab 148033
                    </p>
                </td>

                <!-- Right Info -->
                <td style="vertical-align: top; text-align: right; padding: 10px; border: 1px solid #000; border-left: none;">
                    <p style="margin: 3px 0; font-size: 8px;">GSTIN: 332DS654DS4CDA</p>
                    <p style="margin: 3px 0; font-size: 8px;">CIN NO: 32147PB2003PTC026200</p>
                    <p style="margin: 3px 0; font-size: 8px;">Customer Care: 72787 08000</p>
                    <p style="margin: 3px 0; font-size: 8px;">
                        Date: {{ now() ? date('Y-m-d h:i:s A', strtotime(now())) : 'N/A' }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- Product Table -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
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
                @foreach ($model as $product)
                    <tr>
                        <td style="border: 1px solid black; padding: 2px 3px; font-size:8px">{{ $loop->iteration }}</td>
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

       
    </div>
</body>

</html>
