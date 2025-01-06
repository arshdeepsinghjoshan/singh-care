<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public $setFilteredRecords = 0;

    public function index()
    {
        try {
            $model = new Cart();
            return view('product.index', compact('model'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    protected static function changeQuantityValidator(array $data, $id = null)
    {
        return Validator::make(
            $data,
            [
                'product_id' => 'required|exists:products,id',
                'type_id' => 'required',
            ]
        );
    }
    public function changeQuantity(Request $request)
    {
        try {
            // Validate the request
            $validator = $this->changeQuantityValidator($request->all());
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validator->messages()->first(),
                ]);
            }

            // Retrieve the cart item
            $cartItem = Cart::where('product_id', $request->product_id)->first();
            if (!$cartItem) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Product not found in the cart.',
                ]);
            }

            // Retrieve the product details
            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Product not found.',
                ]);
            }

            // Determine the new quantity
            $quantity = $cartItem->quantity;
            if ($request->type_id == "1") {
                if ($quantity >= 20) {
                    return response()->json([
                        'status' => 422,
                        'message' => "We're sorry! Only 20 unit(s) allowed in each order.",
                    ]);
                }
                $quantity++;
            } else {
                if ($quantity <= 1) {
                    return response()->json([
                        'status' => 422,
                        'message' => "Quantity cannot be less than 1.",
                    ]);
                }
                $quantity--;
            }

            // Calculate the total price
            $totalPrice = $product->price * $quantity;

            // Update the cart item
            $cartItem->update([
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Quantity updated successfully!',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Resource not found: ' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An internal error occurred: ' . $e->getMessage(),
            ]);
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

                // Validate headers
                $requiredColumns = ['name', 'product_code', 'hsn_code', 'price'];
                $headers = $data[0]; // First row as headers
                $missingColumns = array_diff($requiredColumns, $headers);

                if (!empty($missingColumns)) {
                    return redirect()->back()->with('error', 'Missing columns: ' . implode(', ', $missingColumns));
                }

                // Process rows
                $rows = array_slice($data, 1); // Skip header row
                $productsToInsert = [];

                foreach ($rows as $row) {
                    $productData = array_combine($headers, $row); // Map headers to row values

                    // Validate required fields in rows
                    if (
                        empty($productData['name']) || empty($productData['product_code']) ||
                        empty($productData['hsn_code']) || empty($productData['price'])
                    ) {
                        return redirect()->back()->with('error', 'Missing required fields in one or more rows.');
                    }
                    $now = now();
                    $productsToInsert[] = [
                        'name' => $productData['name'],
                        'product_code' => $productData['product_code'] ?? '',
                        'hsn_code' => $productData['hsn_code'],
                        'description' => $productData['description'] ?? '', // Default empty string
                        'salt' => $productData['salt'] ?? '', // Default empty string
                        'tax_id' => $productData['tax_id'] ?? '', // Default empty string
                        'batch_no' => $productData['batch_no'] ?? '', // Default empty string
                        'agency_name' => $productData['agency_name'] ?? '', // Default empty string
                        'price' => (float) $productData['price'],
                        'category_id' => $productData['category_id'] ?? null,
                        'created_by_id' => Auth::id() ?? null,
                        'expiry_date' => $productData['expiry_date'] ?? null,
                        'bill_date' => $productData['bill_date'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert into database
                Cart::insert($productsToInsert);

                return redirect()->back()->with('success', 'File imported successfully! Carts added: ' . count($productsToInsert));
            }

            // For GET request
            $model = new Cart();
            return view('product.import', compact('model'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function edit(Request $request)
    {
        try {
            $id = $request->id;
            $model  = Cart::find($id);
            if ($model) {

                return view('product.update', compact('model'));
            } else {
                return redirect()->back()->with('error', 'Cart not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    public function create(Request $request)
    {
        try {

            $model  = new Cart();
            if ($model) {

                return view('product.add', compact('model'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function view(Request $request)
    {
        try {
            $id = $request->id;
            $model  = Cart::find($id);
            if ($model) {

                return view('product.view', compact('model'));
            } else {
                return redirect('/product')->with('error', 'Cart not found');
            }
        } catch (\Exception $e) {
            return redirect('/product')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function update(Request $request)
    {
        if ($this->validator($request->all())->fails()) {
            $message = $this->validator($request->all())->messages()->first();
            return redirect()->back()->withInput()->with('error', $message);
        }
        try {
            $model = Cart::find($request->id);
            if (!$model) {
                return redirect()->back()->with('error', 'Cart not found');
            }
            $all_images = null;

            if ($request->hasFile('image')) {
                $ticket_images = $request->file('image');
                foreach ($ticket_images as $image) {
                    if ($image->isValid()) {
                        $imageName = rand(1, 100000) . time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('support_module/ticket_images'), $imageName);
                        $all_images[] =  $imageName;
                    }
                }
                $all_images = json_encode($all_images);
            } else {
                $all_images = $model->image;
            }
            $model->fill($request->all());
            $model->image = $all_images;
            if ($model->save()) {
                return redirect()->back()->with('success', 'Cart updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Cart not updated');
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
            $query = Cart::my()->with('product')->orderBy('id', 'desc');
        } else {
            $query = Cart::with('product')->orderBy('id', 'desc');
        }

        if (!empty($id)){
            $model =$query->first();
            if($model)
            $query->where('product_id', $model->product_id);
        }


        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('select', function ($data) {
                return '
                <!-- Quantity -->
                <div class="d-flex " style="max-width: 300px">
                  <button  data-type="0" data-product=\'' . htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . '\' 
                    class="btn btn-link px-2 changeQuantity">
                    <i class="fas fa-minus"></i>
                  </button>
            
                  <div data-mdb-input-init class="form-outline">
                    <input id="form1" min="0" name="quantity" value="' . $data->quantity . '" type="number" disabled class="form-control" />
                  </div>
            
                  <button data-mdb-button-init data-mdb-ripple-init data-type="1" data-product=\'' . htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . '\' 
                    class="btn btn-link px-2 changeQuantity" 
                    >
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
            ';
            })
            ->addColumn('created_by', function ($data) {
                return !empty($data->createdBy && $data->createdBy->name) ? $data->createdBy->name : 'N/A';
            })
            ->addColumn('product_name', function ($data) {
                return !empty($data->product) ? (strlen($data->product->name) > 60 ? substr(ucfirst($data->product->name), 0, 60) . '...' : ucfirst($data->product->name)) : 'N/A';
            })
            ->addColumn('price', function ($data) {
                return number_format($data->price, 2);
            })
            ->addColumn('total_price', function ($data) {
                return number_format($data->total_price, 2);
            })
            ->addColumn('unit_price', function ($data) {
                return number_format($data->unit_price, 2);
            })
            
            ->addColumn('total_checkout_amount', function ($data) {
                return number_format($data->getTotalPriceSum(), 2);
            })
            ->rawColumns(['created_by'])

            ->addColumn('created_at', function ($data) {
                return (empty($data->created_at)) ? 'N/A' : date('Y-m-d', strtotime($data->created_at));
            })
            ->addColumn('expiry_date', function ($data) {
                return (empty($data->expiry_date)) ? 'N/A' : date('Y-m-d', strtotime($data->expiry_date));
            })
            ->addColumn('bill_date', function ($data) {
                return (empty($data->bill_date)) ? 'N/A' : date('Y-m-d', strtotime($data->bill_date));
            })

            ->addColumn('department_id', function ($data) {
                return $data->getDepartment ?  $data->getDepartment->title : 'N/A';
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="table-actions text-center">';
                // $html .= ' <a class="btn btn-icon btn-primary mt-1" href="' . url('support/edit/' . $data->id) . '" ><i class="fa fa-edit"></i></a>';
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
                                ->orWhere('total_price', 'like', "%$term%")
                                ->orWhere('unit_price', 'like', "%$term%")
                                ->orWhere('quantity', 'like', "%$term%")
                                // ->orWhereHas('getDepartment', function ($query) use ($term) {
                                //     $query->where('title', 'like', "%$term%");
                                // })
                                ->orWhere(function ($query) use ($term) {
                                    $query->searchState($term);
                                })
                                ->orWhereHas('createdBy', function ($query) use ($term) {
                                    $query->where('name', 'like', "%$term%");
                                })
                                ->orWhereHas('product', function ($query) use ($term) {
                                    $query->where('name', 'like', "%$term%");
                                })
                                ;
                        }
                    });
                }
            })
            ->make(true);
    }










    protected static function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'product_id' => 'required|exists:products,id', // Ensure the product exists
            ],
            [
                'product_id.exists' => 'The selected product does not exist.',
            ]
        );
    }

    public function add(Request $request)
    {
        try {
            // Validate the request data
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validator->messages()->first(),
                ]);
            }
    
            $typeId = $request->type_id; // Get type_id from the request
    
            // Handle Add to Cart
            if ($typeId == 1) {
                // Check if the product is already in the cart
                $existingCart = Cart::where('product_id', $request->product_id)->first();
                if ($existingCart) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Product is already in the cart. Update the quantity if needed.',
                    ]);
                }
    
                // Retrieve the product details
                $product = Product::find($request->product_id);
                if (!$product) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Product not found.',
                    ]);
                }
    
                $quantity = 1;
                $cart = new Cart();
                $cart->product_id = $request->product_id;
                $cart->quantity = $quantity;
                $cart->total_price = $product->price * $quantity;
                $cart->unit_price = $product->price;
                $cart->created_by_id = Auth::id();
    
                if ($cart->save()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product added to cart successfully!',
                        'cart' => $cart,
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to add product to the cart.',
                    ]);
                }
            }
    
            // Handle Remove from Cart
            if ($typeId == 0) {
                $cart = Cart::where('product_id', $request->product_id)->first();
                if (!$cart) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Product not found in the cart.',
                    ]);
                }
    
                if ($cart->delete()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product removed from cart successfully!',
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to remove product from the cart.',
                    ]);
                }
            }
    
            // If type_id is invalid
            return response()->json([
                'status' => 400,
                'message' => 'Invalid type_id. Use 1 to add or 0 to remove.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ]);
        }
    }
    



    public function stateChange($id, $state)
    {
        try {
            $model = Cart::find($id);
            if ($model) {
                $update = $model->update([
                    'state_id' => $state,
                ]);
                return redirect()->back()->with('success', 'Cart has been ' . (($model->getState() != "New") ? $model->getState() . 'd!' : $model->getState()));
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
            $model = Cart::find($id);
            if ($model) {
                $model->delete();
                return redirect('support')->with('success', 'Cart has been deleted successfully!');
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
