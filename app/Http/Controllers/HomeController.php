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

    public function resProductCat(Request $request=null, $action=null)
    {
        switch ($action) {
            case 'add':
                $this->validate($request, [
                    'name' => 'required|unique:product_cats'
                ]);
                
                $productcat = $this->saveProductCat($request, new ProductCat());
                $resp = (object) [
                            'task' => 'create product category',
                            'message' => 'success',
                            'data' => $productcat
                        ];
                
                return response()->json($resp);
                break;

            case 'edit':
                $this->validate($request, [
                    'id' => 'required',
                    'name' => ['required',
                                Rule::unique('product_cats')->ignore($request->input('id')),
                                ]
                ]);
                
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
                $this->validate($request, [
                    'id' => 'required'
                ]);
                
                $productcat = ProductCat::find($request->input('id'));
                if(! isset($productcat->id)) {
                    return $this->notFoundExcep();
                }

                $productcat->delete();
                $resp = (object) [
                            'task' => 'delete product category',
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

    //
}
