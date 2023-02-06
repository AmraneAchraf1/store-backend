<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            "user_cart_id" => "Required|exists:carts",
        ]);

        // $tokenable = PersonalAccessToken::findToken($request->bearerToken())->tokenable_id ?? 0;


        $products = Cart::where("user_cart_id", $request->user_cart_id)
            ->join("products", "products.id", "carts.product_id")->get()
            ->map(function($product) {
                return [
                    "id" => $product->id,
                    "user_cart_id" => $product->user_cart_id,
                    "name" => $product->name,
                    "description" => $product->description,
                    "price" => $product->price,
                    "total" => $product->price * $product->quantity,
                    'quantity' => $product->quantity,
                    "options" => $product->options,
                    "image" => $product->image,
                    "type"=> $product->type,
                    "image_url" =>  asset("storage/uploads/".$product->image)
                ];
            });

        if($products){
            return response()->json($products);
        }

        return response()->json(['msg' => 'product not found']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "Required",
            "user_cart_id" => "sometimes",
        ]);

        $product = Product::find($request->product_id);
        if(!$product) return response()->json(['msg' =>'product not found']);



        $personalleAccessToken = PersonalAccessToken::findToken($request->bearerToken());
        // Can Access just by User Token
        if($personalleAccessToken){
            if($personalleAccessToken->tokenable_type !== "App\\Models\\User"){
                return  response()->json(['msg' => 'Access denied'], 400);
            }
        }


        $updateCart = Cart::where('user_cart_id', $request->user_cart_id )->where('product_id', $request->product_id);

        if($updateCart->count() > 0){
            $updateCart->increment("quantity");
            return response()->json($updateCart->first());
        }

        // If User Cart id is not found then Create new User Cart id
        // This Cart id is Stocked in Local Storage
        $cartId = $request->user_cart_id ? $request->user_cart_id : Str::uuid();


        $cart = Cart::create([
            'user_cart_id' => $cartId,
            'user_id' =>  $personalleAccessToken->tokenable_id ?? null,
            'product_id' => $request->product_id,
        ]);

        if($cart){

            return response()->json($cart, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            "user_cart_id" => "Required|exists:carts",
        ]);

        if(! Product::find($id)) return response()->json(["msg" => "Product not found"]);

        $product = Cart::where('user_cart_id', $request->user_cart_id)
            ->where('product_id', $id)
            ->delete() ;

        if($product){
            return  response()->json(["product deleted successfully"]);
        }
        return  response()->json(["Cant delete product"]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function total(Request $request)
    {
        $request->validate([
            "user_cart_id" => "Required|exists:carts",
        ]);

        return Cart::where("user_cart_id", $request->user_cart_id)
            ->join('products', 'products.id', 'carts.product_id')
            ->selectRaw("SUM(products.price * carts.quantity) as total")->value("total");
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function empty(Request $request)
    {
        $request->validate([
            "user_cart_id" => "Required|exists:carts",
        ]);

         $empty = Cart::where("user_cart_id", $request->user_cart_id)
                        ->delete();

         if($empty){
             return response()->json(['msg' => "Cart is empty"]);
         }

         return response()->json(['msg' =>'cant empty cart']);
    }

}
