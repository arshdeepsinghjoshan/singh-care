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
use PDF;

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
            ini_set('memory_limit', '1G');
            ini_set('max_execution_time', 300); // 5 minute
            $model = Product::findActive()->with(['mfg', 'agency'])->orderBy('id', 'desc')->take(1650)->get();
            if (!$model) {
                return redirect()->route('product')->with('error', 'No products found.');
            }
            $pdf = PDF::loadView('pdf.products', compact('model'))->setPaper('a4', 'landscape');
            return $pdf->stream('invoice.pdf');
        } catch (\Exception $e) {
            return redirect('product')->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
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
