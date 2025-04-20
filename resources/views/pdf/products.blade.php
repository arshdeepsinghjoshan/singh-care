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
        <h4 style="text-align: center; margin: 0;">
            Products</h4>
        <table style="width: 100%; margin: 10px auto 0 auto; border-collapse: collapse; table-layout: fixed;">
            <tr style="">

                <td
                    style="vertical-align: top; padding: 10px; border-top: 1px solid #000; border-right: 0px; border-left: 1px solid #000;border-bottom: 1px solid #000;">
                    <!-- <img src="https://node.greenfinworld.com/invoice/Maxgrow-logo-1.jpg" alt="logo" -->
                    <!-- style="width: 70px; height: auto;"> -->
                    <h2 style="color: #D04A02; margin: 3px 0; font-size: 10px;">Guru Nanak Medical Hall.</h2>

                    <p style="margin: 3px 0; font-size: 8px;">
                        JAKHAL ROAD, near GURUDWARA
                        <br>
                        SHRI GRUR TEG BAHADUR SAHIB,
                        <br>Moonak, Punjab 148033
                    </p>
                    {{-- <h2 style="color: #D04A02; margin: 3px 0; font-size: 10px;">HDFC BANK LTD.</h2>
                    <p style="margin: 3px 0; font-size: 8px;">A/C No: 6323652365</p>
                    <p style="margin: 3px 0; font-size: 8px;">IFSC Code: HDFC0001238</p>
                    <p style="margin: 3px 0; font-size: 8px;">Branch: Patiala</p> --}}

                </td>

                <td
                    style="vertical-align: top; text-align: right; padding: 10px; border-top: 1px solid #000; border-right: 1px solid #000; border-left: 0px solid #000; border-bottom: 1px solid #000;">
                    <!-- <img src="https://node.greenfinworld.com/invoice/logo.png" alt="logo"
                        style="width: 70px; height: auto;"> -->
                    <p style="margin: 3px 0; font-size: 8px;">GSTIN: 332DS654DS4CDA</p>
                    <p style="margin: 3px 0; font-size: 8px;">CIN NO: 32147PB2003PTC026200</p>
                    {{-- <p style="margin: 3px 0; font-size: 8px;">Web Address: https://joshanchakki.web.app/</p> --}}
                    {{-- <p style="margin: 3px 0; font-size: 8px;">joshanchakki@gmail.com</p> --}}
                    <p style="margin: 3px 0; font-size: 8px;">Customer Care: 72787 08000</p>
                    <p style="margin: 3px 0; font-size: 8px;">Date:
                        {{ empty(Now()) ? 'N/A' : date('Y-m-d h:i:s A', strtotime(Now())) }}</p>


                </td>
            </tr>
        </table>

        <!-- Invoice and Transport Info -->

        <!-- Item Details Section -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead style="width: 100%; border-collapse: collapse; margin-top: 10px; white-space: wrap;">
                <tr style="background-color: #f0f0f0; border: 1px solid black;">
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                        Sr.</th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">HSN</th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">BATCH NO.
                    </th>

                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">QTY</th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">MFG
                    </th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">PKG
                    </th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">Product
                        Name</th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">MRP</th>

                    <th
                        style="background-color:yellow; border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                        Rate</th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">AGENCY NAME
                    </th>
                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">ADDRESS</th>

                    <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">BILL DATE
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($model as $product)
                    <tr style="border: 1px solid black;">
                        <!-- Sr. No. -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $loop->iteration }}
                        </td>

                        <!-- hsn code -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->hsn_code ?? 'N/A' }}
                        </td>
                        <!-- batch_no -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->batch_no ?? 'N/A' }}
                        </td>
                        <!-- Quantity -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->quantity ?? 'N/A' }}
                        </td>
                        <!-- Manufacturer Name -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->mfg->name ?? 'N/A' }}
                        </td>


                        <!-- HSN Code -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->pkg ?? 'N/A' }}
                        </td>

                        <!-- Product Name -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->name ?? 'N/A' }}
                        </td>

                        <!-- Agency Name -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ number_format($product->mrp_price, 2) ?? 'N/A' }}

                        </td>

                        <td
                            style="background-color:yellow; border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ number_format($product->price, 2) ?? 'N/A' }}

                        </td>
                        <!-- Agency Name -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ $product->agency->name ?? 'N/A' }}
                        </td>

                        <!-- Address Name -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ 'TOHANA' }}
                        </td>
                        <!-- Bill Date -->
                        <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                            {{ empty($product->bill_date) ? 'N/A' : date('Y-m-d', strtotime($product->bill_date)) }}

                        </td>


                    </tr>
                @endforeach

            </tbody>
        </table>
        {{-- 
        <table style="width: 100%; margin: 0 auto; border-collapse: collapse; ">
            <!-- First Row with 2 Columns -->
            <!-- <thead> -->
            <th colspan="2" style="text-align: right; font-size: 8px; padding: 4px 5px;">
                Order Installments:</th>

            <!-- Subsequent Rows with 4 Columns -->
            <tr style="background-color: #f0f0f0; border: 1px solid black;">

                <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px"> Amount
                </th>
                <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">Pay Date</th>
                <th style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">Last Update</th>

            </tr>
            <!-- </thead> -->
            <!-- <tbody> -->
            @foreach ($model->installments as $installment)
                <tr style="border: 1px solid black;">

                    <!-- Replace with dynamic code if available -->
                    <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                        {{ number_format($installment->amount, 2) ?? 'N/A' }}
                    </td>

                    <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                        {{ empty($installment->created_at) ? 'N/A' : date('Y-m-d', strtotime($installment->created_at)) }}
                    </td>

                    <td style="border: 1px solid black; padding: 2px 3px; white-space: wrap; font-size:8px">
                        {{ empty($installment->updated_at) ? 'N/A' : date('Y-m-d', strtotime($installment->updated_at)) }}
                    </td>


                </tr>
            @endforeach
            <!-- </tbody> -->
        </table> --}}
        <table style="width: 100%; margin: 0 auto; border-collapse: collapse; ">
            <!-- First Row with 2 Columns -->

            {{-- <th colspan="3"
                style="border-left: 1px solid #000; border-right: 1px solid #000;border-bottom: 1px solid #000; text-align: right; font-size: 8px; padding: 4px 5px;">
                Bill Summary:</th> --}}
            <!-- Subsequent Rows with 4 Columns -->



        </table>
        {{-- <table style="width: 100%; margin: 0 auto; border-collapse: collapse; ">
            <!-- First Row with 2 Columns -->

            <th colspan="3"
                style="border-left: 1px solid #000; border-right: 1px solid #000; text-align: right; font-size: 8px; padding: 4px 5px;">
                Total Amount: {{ number_format($model->total_amount, 2) ?? 'N/A' }}</th>

        </table>
        <table style="width: 100%; margin: 0 auto; border-collapse: collapse; ">

            <th colspan="3"
                style="border-left: 1px solid #000; border-right: 1px solid #000; text-align: right; font-size: 8px; padding: 4px 5px;">
                Pending Amount:
                21

                <!-- Subsequent Rows with 4 Columns -->



        </table>

        <table style="width: 100%; margin: 0 auto; border-collapse: collapse; ">

            <th colspan="3"
                style="border-left: 1px solid #000; border-right: 1px solid #000; text-align: right; font-size: 8px; padding: 4px 5px;">
                Paid Amount:
                32
            </th>

            <!-- Subsequent Rows with 4 Columns -->



        </table> --}}

        <table style="width: 100%; margin: 0 auto; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;">
            <tr>
                <td
                    style="border-left: 1px solid #000; border-right: 1px solid #000;  border-bottom: 1px solid #000;padding: 2px;">

                    <table style="width: 97%; margin-top: 100px; border-collapse: collapse;">
                        <tr style="padding: 0;">
                            <td style="padding: 4px 5px;">Authorised Signatory</td>
                </td>
            </tr>

            </tr>

        </table>

        </td>

        </tr>

        </table>


        <!-- Notes Section -->
        <!-- <p style="margin: 5px 0; font-weight: bold;">Note:</p>
    <p style="margin: 5px 0;">
      1. Subject to Ludhiana jurisdiction.<br>
      2. No cancellation once the order is placed.
    </p> -->
    </div>
</body>

</html>
