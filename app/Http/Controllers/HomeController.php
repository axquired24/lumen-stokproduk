<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $resp;

    public function __construct()
    {
        $this->resp = (object) [
            'task' => '',
            'message' => '',
            'data' => null
        ];
    }

    public function resProduct(Request $request=null, $action=null)
    {
        $product_cat_ids = $this->getProductCatIds();
        switch ($action) {
            case 'add':
                $validator = \Validator::make($request->all(), [
                    'product_cat_id' => ['required',
                                         Rule::in($product_cat_ids)
                                        ],
                    'name' => 'required|unique:products',
                    'stock' => 'integer',
                    'price' => 'integer'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                $product = $this->saveProduct($request, new Product());
                $resp = (object) [
                            'task' => 'create product',
                            'message' => 'success',
                            'data' => Product::with('productCat')->find($product->id)
                        ];
                
                return response()->json($resp);
                break;

            case 'edit':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required',
                    'product_cat_id' => ['required',
                                         Rule::in($product_cat_ids),
                                        ],
                    'name' => ['required',
                                Rule::unique('products')->ignore($request->input('id'))],
                    'stock' => 'integer',
                    'price' => 'integer'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                $product = Product::find($request->input('id'));
                if(! isset($product->id)) {
                    return $this->notFoundExcep();
                }

                $product = $this->saveProduct($request, $product);
                $resp = (object) [
                            'task' => 'update product',
                            'message' => 'success',
                            'data' => Product::with('productCat')->find($product->id)
                        ];
                
                return response()->json($resp);
                break;

            case 'delete':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required'
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                $product = Product::find($request->input('id'));
                if(! isset($product->id)) {
                    return $this->notFoundExcep();
                }

                $productdata = Product::with('productCat')->find($product->id);
                $product->delete();
                $resp = (object) [
                            'task' => 'delete product',
                            'message' => 'success',
                            'data' => $productdata
                        ];
                
                return response()->json($resp);
                break;
            
            case 'detail':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required'
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                $product = Product::find($request->input('id'));
                if(! isset($product->id)) {
                    return $this->notFoundExcep();
                }

                $resp = (object) [
                            'task' => 'detail product',
                            'message' => 'success',
                            'data' => Product::with('productCat')->find($product->id)
                        ];
                
                return response()->json($resp);
                break;

            default:
                # code...
                break;
        }
    }

    public function resProductCat(Request $request=null, $action=null)
    {
        switch ($action) {
            case 'add':
                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:product_cats'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                $productcat = $this->saveProductCat($request, new ProductCat());
                $resp = (object) [
                            'task' => 'create product category',
                            'message' => 'success',
                            'data' => $productcat
                        ];
                
                return response()->json($resp);
                break;

            case 'edit':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required',
                    'name' => ['required',
                                Rule::unique('product_cats')->ignore($request->input('id')),
                                ]
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                $productcat = ProductCat::find($request->input('id'));
                if(! isset($productcat->id)) {
                    return $this->notFoundExcep();
                }

                $productcat = $this->saveProductCat($request, $productcat);
                $resp = (object) [
                            'task' => 'update product category',
                            'message' => 'success',
                            'data' => $productcat
                        ];
                
                return response()->json($resp);
                break;

            case 'delete':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required'
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                $productcat = ProductCat::find($request->input('id'));
                if(! isset($productcat->id)) {
                    return $this->notFoundExcep();
                }

                try {
                    $productcat->delete();                    
                } catch(\Exception $e) {
                    return response()->json([
                        'errors' => [
                            'sql' => $e->errorInfo[2]
                        ],
                        'errordetails' => $e
                    ], 422);
                }

                $resp = (object) [
                            'task' => 'delete product category',
                            'message' => 'success',
                            'data' => $productcat
                        ];
                
                return response()->json($resp);
                break;

            case 'detail':
                $validator = \Validator::make($request->all(), [
                    'id' => 'required'
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                $productcat = ProductCat::find($request->input('id'));
                if(! isset($productcat->id)) {
                    return $this->notFoundExcep();
                }

                $resp = (object) [
                            'task' => 'detail product category',
                            'message' => 'success',
                            'data' => $productcat
                        ];
                
                return response()->json($resp);
                break;
            
            default:
                # list
                $productcats = ProductCat::paginate(10);
                return response()->json($productcats);

                break;
        }
    }

    function saveProduct(Request $request, Product $product) {
        $product->name = $request->input('name');
        $product->product_cat_id = $request->input('product_cat_id');
        $product->description = $this->setIfNull($request, 'description');
        $product->stock = $this->setIfNull($request, 'stock', 0);
        $product->price = $this->setIfNull($request, 'price', 0);
        $product->save();

        return $product;
    }

    function getProductCatIds() {
        $productcats = ProductCat::select('id')->get();
        $productcats = collect($productcats)->map(function($item) {
            return $item->id;
        });

        return $productcats->toArray();
    }

    function saveProductCat(Request $request, ProductCat $productcat) {
        $productcat->name = $request->input('name');
        $productcat->save();

        return $productcat;
    }

    function notFoundExcep() {
        $resp = (object) [
            'message' => 'not found',
            'errors' => [
                'id' => 'data related to given ID was not found'
            ]
        ];

        return response()->json($resp, 404);
    }

    function setIfNull(Request $request, $param, $defaultval='') {
        $ret = $request->has($param) ? $request->input($param) : $defaultval;
        return $ret;
    }

    //
}
