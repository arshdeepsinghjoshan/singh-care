<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    public $setFilteredRecords = 0;

    public function index()
    {
        try {
            $model = new Product();
            return view('product.index', compact('model'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                // Validate the file input
                $request->validate([
                    'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Ensure it's an Excel or CSV file
                ]);

                // Handle file upload
                $file = $request->file('file');
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();
                // dd($data); 
                // Validate headers
                $requiredColumns = ['name'];
                $headers = $data[0]; // First row as headers
                $trimmedArray = array_map('trim', $headers);
                $missingColumns = array_diff($requiredColumns, $trimmedArray);

                if (!empty($missingColumns)) {
                    return redirect()->back()->with('error', 'Missing columns: ' . implode(', ', $missingColumns));
                }

                // Process rows
                $rows = array_slice($data, 1); // Skip header row
                $productsToInsert = [];

                foreach ($rows as $row) {
                    $productData = array_combine($trimmedArray, $row); // Map headers to row values

                    // Validate required fields in rows
                    if (
                        empty($productData['name'])
                    ) {
                        continue; // Skip this row if any required field is empty
                    }
                    $now = now();
                    $productsToInsert[] = [
                        'name' => $productData['name'],
                        'hsn_code' => $productData['hsn_code'],
                        'salt' => $productData['salt'] ?? '', // Default empty string
                        'quantity' => $productData['quantity'] ?? '', // Default empty string
                        'price' => (float) $productData['price'],
                        'mrp_price' => (float) $productData['mrp_price'],
                        'created_by_id' => Auth::id() ?? null,
                        'expiry_date' => $productData['expiry_date'] ?? null,
                        'bill_date' => $productData['bill_date'] ?? null,
                        'description' => $productData['description'] ?? null,
                        'mfg_name' => $productData['mfg_id'] ?? null,
                        'agency_name' => $productData['agency_id'] ?? null,
                        'batch_no' => $productData['batch_no'] ?? null,
                        'pkg' => $productData['pkg'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert into database
                Product::insert($productsToInsert);

                return redirect()->back()->with('success', 'File imported successfully! Products added: ' . count($productsToInsert));
            }

            // For GET request
            $model = new Product();
            return view('product.import', compact('model'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function edit(Request $request)
    {
        try {
            $id = $request->id;
            $model  = Product::find($id);
            if ($model) {

                return view('product.update', compact('model'));
            } else {
                return redirect()->back()->with('error', 'Product not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    public function create(Request $request)
    {
        try {

            $model  = new Product();
            if ($model) {

                return view('product.add', compact('model'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
  
    public function generatePDF(Request $request)
    {
        try {
            $chunkSize = 1000; // Use larger chunks if Snappy handles well
            $productsQuery = Product::findActive();
            $pages = [];
    
            // Load each chunk and render a partial view
            $productsQuery->chunk($chunkSize, function ($products) use (&$pages) {
                $pages[] = View::make('pdf.product_chunk', ['products' => $products])->render();
            });
    
            // Combine all pages into one full HTML view
            $html = View::make('pdf.full_template', ['pages' => $pages])->render();
    
            return PDF::loadHTML($html)->download('products.pdf');
    
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF.');
        }
    }
    



    //     public function generatePaginatedPDF(Request $request)
    // {
    //     // Define records per page (for large datasets, e.g. 500 records per page)
    //     $perPage = 500;
    //     $totalRecords = Product::count();
    //     $totalPages = ceil($totalRecords / $perPage);

    //     // Start building the PDF content
    //     $htmlContent = '<h1 style="text-align: center;">Product List</h1>';
    //     $htmlContent .= '<div style="margin: 20px 0; font-size: 14px; text-align: center;">';

    //     // Add links for navigation (e.g. page 1, page 2, ...)
    //     for ($page = 1; $page <= $totalPages; $page++) {
    //         $htmlContent .= '<a href="#page' . $page . '" style="text-decoration: none; padding: 5px 10px; border: 1px solid #000; margin: 0 5px;">Page ' . $page . '</a>';
    //     }

    //     $htmlContent .= '</div>';

    //     // Loop through pages and fetch the data
    //     for ($page = 1; $page <= $totalPages; $page++) {
    //         $products = Product::skip(($page - 1) * $perPage)->take($perPage)->get();

    //         // Add section for each page
    //         $htmlContent .= '<div id="page' . $page . '" style="page-break-before: always; margin-top: 30px;">';
    //         $htmlContent .= '<h2 style="text-align: center;">Page ' . $page . ' - Products</h2>';
    //         $htmlContent .= $this->generateProductTable($products);
    //         $htmlContent .= '</div>';
    //     }

    //     // Load PDF view with generated HTML content
    //     $pdf = PDF::loadHTML($htmlContent)->setPaper('a4', 'landscape');

    //     // Generate and stream the PDF
    //     return $pdf->stream('paginated_products.pdf');
    // }


    public function generatePaginatedPDF(Request $request)
    {
        // Pagination settings
        $perPage = 500;
        $totalRecords = Product::count();
        $totalPages = ceil($totalRecords / $perPage);

        $pdfContent = '';
        $tableOfContents = '<h3>Table of Contents</h3><ul>';

        // Loop through pages and create links in the table of contents
        for ($page = 1; $page <= $totalPages; $page++) {
            $products = Product::skip(($page - 1) * $perPage)->take($perPage)->get();

            // Add link to table of contents

            // Generate the product table for the current page
            $pdfContent .= '<div id="page-' . $page . '">
            <h4 style="text-align: center;">Page ' . $page . '</h4>
            <table class="product-table" style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Sr.</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">HSN</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Batch No.</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Qty</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">MFG</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">PKG</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Product Name</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">MRP</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Rate</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Agency Name</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Address</th>
                        <th style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">Bill Date</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($products as $index => $product) {
                $pdfContent .= '<tr>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($index + 1 + (($page - 1) * $perPage)) . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->hsn_code ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->batch_no ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->quantity ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->mfg->name ?? $product->mfg_name ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->pkg ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->name ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . number_format($product->mrp_price ?? 0, 2) . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left; background-color:yellow;">' . number_format($product->price ?? 0, 2) . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->agency->name ?? $product->agency_name ?? 'N/A') . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . 'TOHANA' . '</td>
                <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . (isset($product->bill_date) ? date('M-y', strtotime($product->bill_date)) : 'N/A') . '</td>
            </tr>';
            }

            $pdfContent .= '</tbody></table></div>';
        }


        // Create the PDF
        $content = $pdfContent;
        $pdf = PDF::loadHTML($content);

        return $pdf->stream('paginated_invoice.pdf');
    }
    // Helper function to generate product table for each page
    private function generateProductTable($products)
    {
        $tableHtml = '<table style="width: 100%; border-collapse: collapse;">';
        $tableHtml .= '<thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="padding: 8px; border: 1px solid #000;">Sr.</th>
                        <th style="padding: 8px; border: 1px solid #000;">HSN</th>
                        <th style="padding: 8px; border: 1px solid #000;">Batch No.</th>
                        <th style="padding: 8px; border: 1px solid #000;">Quantity</th>
                        <th style="padding: 8px; border: 1px solid #000;">MFG</th>
                        <th style="padding: 8px; border: 1px solid #000;">Package</th>
                        <th style="padding: 8px; border: 1px solid #000;">Product Name</th>
                        <th style="padding: 8px; border: 1px solid #000;">MRP</th>
                        <th style="padding: 8px; border: 1px solid #000;">Rate</th>
                        <th style="padding: 8px; border: 1px solid #000;">Agency Name</th>
                    </tr>
                </thead><tbody>';

        foreach ($products as $product) {
            $tableHtml .= '<tr>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . $product->id . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->hsn_code ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->batch_no ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->quantity ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->mfg_name ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->pkg ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->name ?? 'N/A') . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . number_format($product->mrp_price, 2) . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . number_format($product->price, 2) . '</td>
                        <td style="border: 1px solid #000;padding: 4px;font-size: 8px;text-align: left;">' . ($product->agency_name ?? 'N/A') . '</td>
                    </tr>';
        }

        $tableHtml .= '</tbody></table>';
        return $tableHtml;
    }

    public function view(Request $request)
    {
        try {
            $id = $request->id;
            $model  = Product::find($id);
            if ($model) {

                return view('product.view', compact('model'));
            } else {
                return redirect('/product')->with('error', 'Product not found');
            }
        } catch (\Exception $e) {
            return redirect('/product')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function addMfg(Request $request)
    {
        try {
            $request->validate([
                'product_type' => 'required|string|max:255',
            ]);

            // Store new MFG
            ProductCategory::create([
                'name' => $request->product_type,
                'created_by_id' => Auth::user()->id,
                'type_id' => $request->type_id, // Assuming type_id is 1 for MFG
            ]);

            // Assuming you want to return updated categories
            $categories = ProductCategory::where('type_id', $request->type_id)->get();

            return response()->json([
                'success' => true,
                'categories' => $categories,
                'type_id' => $request->type_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ]);
        }
    }
    public function update(Request $request)
    {
        if ($this->validator($request->all())->fails()) {
            $message = $this->validator($request->all())->messages()->first();
            return redirect()->back()->withInput()->with('error', $message);
        }
        try {
            $model = Product::find($request->id);
            if (!$model) {
                return redirect()->back()->with('error', 'Product not found');
            }
            $all_images = [];
            $ticket_images = $request->file('images');
            // Check if images were uploaded
            if ($request->hasFile('images')) {
                foreach ($ticket_images as $image) {
                    if ($image->isValid()) {
                        $imageName = rand(1, 100000) . time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('products'), $imageName);
                        $all_images[] = $imageName;
                    }
                }
            }
            $model->fill($request->all());
            if ($request->hasFile('images')) {

                $model->images = !empty($all_images) ? json_encode($all_images) : null;  // Ensure it's a JSON string
            }
            if ($model->save()) {
                return redirect('product/view/' . $model->id)->with('success', 'Product updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Product not updated');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    public $s_no = 1;





    public function getList(Request $request, $id = null)
    {
        if (User::isUser()) {
            $query = Product::my()->orderBy('id', 'desc');
        } else {
            $query = Product::orderBy('id', 'desc');
        }

        if (!empty($id))
            $query->where('id', $id);

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('select', function ($data) {
                $checked = $data->cart ? 'checked' : ''; // If the relationship exists, mark as checked
                return '<input class="form-check-input select-product" data-product_id="' . $data->id . '" type="checkbox" ' . $checked . ' value="' . $data->id . '" >';
            })
            ->addColumn('created_by', function ($data) {
                return !empty($data->createdBy && $data->createdBy->name) ? $data->createdBy->name : 'N/A';
            })
            ->addColumn('name', function ($data) {
                return !empty($data->name) ? (strlen($data->name) > 60 ? substr(ucfirst($data->name), 0, 60) . '...' : ucfirst($data->name)) : 'N/A';
            })
            ->addColumn('price', function ($data) {
                return number_format($data->price, 2);
            })

            ->addColumn('mrp_price', function ($data) {
                return number_format($data->mrp_price, 2);
            })
            ->addColumn('status', function ($data) {
                return '<span class="' . $data->getStateBadgeOption() . '">' . $data->getState() . '</span>';
            })
            ->rawColumns(['created_by'])

            ->addColumn('created_at', function ($data) {
                return (empty($data->created_at)) ? 'N/A' : date('Y-m-d', strtotime($data->created_at));
            })
            ->addColumn('expiry_date', function ($data) {
                return (empty($data->expiry_date)) ? 'N/A' : date('M-y', strtotime($data->expiry_date));
            })
            ->addColumn('bill_date', function ($data) {
                return (empty($data->bill_date)) ? 'N/A' : date('M-y', strtotime($data->bill_date));
            })
            ->addColumn('priority_id', function ($data) {
                return $data->getPriority();
            })
            ->addColumn('agency_name', function ($data) {
                return $data->agency ?  $data->agency->name : $data->agency_name ?? 'N/A';
            })
            ->addColumn('mfg', function ($data) {
                return $data->mfg ?  $data->mfg->name : $data->mfg_name ?? 'N/A';
            })
            ->addColumn('department_id', function ($data) {
                return $data->getDepartment ?  $data->getDepartment->title : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="table-actions text-center">';
                $html .= ' <a class="btn btn-icon btn-primary mt-1" href="' . url('product/edit/' . $data->id) . '" ><i class="fa fa-edit"></i></a>';
                $html .=    '  <a class="btn btn-icon btn-primary mt-1" href="' . url('product/view/' . $data->id) . '"  ><i class="fa fa-eye
                    "data-toggle="tooltip"  title="View"></i></a>';
                $html .=  '</div>';
                return $html;
            })->addColumn('customerClickAble', function ($data) {
                $html = 0;

                return $html;
            })
            ->rawColumns([
                'action',
                'created_at',
                'status',
                'customerClickAble',
                'select'
            ])

            ->filter(function ($query) {
                if (!empty(request('search')['value'])) {
                    $searchValue = request('search')['value'];
                    $searchTerms = explode(' ', $searchValue);
                    $query->where(function ($q) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $q->where('id', 'like', "%$term%")
                                ->orWhere('name', 'like', "%$term%")
                                ->orWhere('price', 'like', "%$term%")
                                ->orWhere('hsn_code', 'like', "%$term%")
                                ->orWhere('batch_no', 'like', "%$term%")
                                ->orWhere('bill_date', 'like', "%$term%")
                                ->orWhere('expiry_date', 'like', "%$term%")
                                ->orWhere('created_at', 'like', "%$term%")
                                // ->orWhereHas('getDepartment', function ($query) use ($term) {
                                //     $query->where('title', 'like', "%$term%");
                                // })
                                ->orWhere(function ($query) use ($term) {
                                    $query->searchState($term);
                                })
                                ->orWhere(function ($query) use ($term) {
                                    $query->searchPriority($term);
                                })
                                ->orWhereHas('createdBy', function ($query) use ($term) {
                                    $query->where('name', 'like', "%$term%");
                                })->orWhereHas('agency', function ($query) use ($term) {
                                    $query->where('name', 'like', "%$term%");
                                });
                        }
                    });
                }
            })
            ->make(true);
    }








    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (!empty($ids)) {
                Product::whereIn('id', $ids)->delete();
                return response()->json(['message' => 'Selected products deleted successfully.']);
            } else {
                return response()->json(['message' => 'No products selected.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }



    protected static function validator(array $data, $id = null)
    {
        return Validator::make(
            $data,
            [
                'name' => 'required|string|max:255',
                'price' => 'required',
            ],
            [
                'title.required' => 'The subject field is required.',
                'title.max' => 'The subject field must not exceed 255 characters.',
                'department_id.required' => 'The department field is required.',
                'priority_id.required' => 'The priority field is required.',
                'message.required' => 'The message field is required.',
                'message.max' => 'The message field must not exceed 255 characters.'
            ]
        );
    }

    public function add(Request $request)
    {
        try {
            // Validation
            if ($this->validator($request->all())->fails()) {
                $message = $this->validator($request->all())->messages()->first();
                return redirect()->back()->withInput()->with('error', $message);
            }

            // Initialize the images array
            $all_images = [];
            $ticket_images = $request->file('images');

            // Check if images were uploaded
            if ($request->hasFile('images')) {
                foreach ($ticket_images as $image) {
                    if ($image->isValid()) {
                        $imageName = rand(1, 100000) . time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('products'), $imageName);
                        $all_images[] = $imageName;
                    }
                }
            }

            // Create a new product model
            $model = new Product();
            $model->fill($request->all());
            $model->state_id = Product::STATE_ACTIVE;
            $model->images = !empty($all_images) ? json_encode($all_images) : null;  // Ensure it's a JSON string
            $model->created_by_id = Auth::user()->id;

            // Save the model
            if ($model->save()) {
                return redirect('product/view/' . $model->id)->with('success', 'Product created successfully!');
            } else {
                return redirect('/product/create')->with('error', 'Unable to save the Product!');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->withInput()->with('error', $bug);
        }
    }



    public function stateChange($id, $state)
    {
        try {
            $model = Product::find($id);
            if ($model) {
                $update = $model->update([
                    'state_id' => $state,
                ]);
                return redirect()->back()->with('success', 'Product has been ' . (($model->getState() != "New") ? $model->getState() . 'd!' : $model->getState()));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function finalDelete($id)
    {
        try {
            $model = Product::find($id);
            if ($model) {
                $model->delete();
                return redirect('support')->with('success', 'Product has been deleted successfully!');
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
