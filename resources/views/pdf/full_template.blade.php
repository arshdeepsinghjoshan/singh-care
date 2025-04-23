<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products PDF</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 12px;">

    <div style="text-align: center; margin-bottom: 10px;">
        <h3 style="margin: 0;">Guru Nanak Medical Hall</h3>
        <p style="font-size: 10px; margin: 0;">
            JAKHAL ROAD, near GURUDWARA SHRI GRUR TEG BAHADUR SAHIB, Moonak, Punjab 148033
        </p>
        <p style="font-size: 10px; margin: 0;">
            GSTIN: 332DS654DS4CDA | CIN NO: 32147PB2003PTC026200 | Customer Care: 72787 08000
        </p>
        <p style="font-size: 10px;">Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d h:i A') }}</p>
    </div>

    @foreach ($pages as $page)
        {!! $page !!}
    @endforeach

</body>
</html>
