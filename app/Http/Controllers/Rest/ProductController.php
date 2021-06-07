<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Destroy;
use App\Http\Requests\Product\Store;
use App\Http\Requests\Product\Update;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller {
    const QUERY_EXCEPTION_READABLE_MESSAGE = 2;

    /**
     * @api {get} /api/v1/products
     * @apiName Index
     * @apiGroup Products
     *
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() {
        $success  = true;
        $products = Product::with( 'categories' )
                           ->orderBy( 'id', 'asc' )
                           ->get();

        return response()->json( [
            'success'  => $success,
            'products' => $products,
        ] )->setStatusCode( Response::HTTP_OK );
    }

    /**
     * @api {post} /api/v1/products
     * @apiName Store
     * @apiGroup Products
     *
     * @apiParam {string} [title] Product name
     * @apiParam {string} [description] optional product description
     * @apiParam {float} [price] Product price
     * @apiParam {array} [categories] Array of ids of product categories
     *
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return JsonResponse
     */
    public function store( Store $request ) {
        $slug = Str::slug( $request->title, '-' );
        $request->merge( [ 'slug' => $slug ] );
        try {
            $product = Product::create( $request->except( 'categories' ) );
            $product->categories()->attach( $request->categories );
            $product->categories;
            $message  = 'Добавление выполнено успешно!';
            $success  = true;
            $response = Response::HTTP_CREATED;
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $success  = false;
            $response = Response::HTTP_OK;
        }

        return response()->json( [
            'success' => $success,
            'product' => $product ?? null,
            'message' => $message,
        ] )->setStatusCode( $response );
    }

    /**
     * @api {get} /api/v1/products/{product_id}
     * @apiName Show
     * @apiGroup Products
     *
     * @apiParam {integer} [product_id] Product id
     *
     * Display the specified resource.
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function show( Product $product ) {
        $product->categories;
        $success = true;

        return response()->json( [
            'success' => $success,
            'product' => $product,
        ] )->setStatusCode( Response::HTTP_OK );
    }

    /**
     * @api {put} /api/v1/products/{product_id}
     * @apiName Update
     * @apiGroup Products
     *
     * @apiParam {string} [title] Product name
     * @apiParam {string} [description] optional product description
     * @apiParam {float} [price] Product price
     * @apiParam {array} [categories] Array of ids of product categories
     * @apiParam {integer} [id] Product id
     * @apiParam {integer} [product_id] Product id
     *
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update( Update $request, $id ) {
        $product = Product::findOrFail( $id );
        $slug    = Str::slug( $request->title, '-' );
        $request->merge( [ 'slug' => $slug ] );
        try {
            $product->update( $request->except( [ 'categories' ] ) );
            $product->categories()->sync( $request->categories );
            $message  = 'Обновление выполнено успешно!';
            $success  = true;
            $response = Response::HTTP_ACCEPTED;
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $success  = false;
            $response = Response::HTTP_OK;
        }

        return response()->json( [
            'success' => $success,
            'product' => $product,
            'message' => $message,
        ] )->setStatusCode( $response );

    }

    /**
     * @api {delete} /api/v1/products/{product_id}
     * @apiName Destroy
     * @apiGroup Products
     *
     * @apiParam {integer} [product_id] Product id
     * @apiParam {integer} [id] Product id
     *
     * Remove the specified resource from storage.
     *
     * @param Destroy $request
     *
     * @return JsonResponse
     */
    public function destroy( Destroy $request ) {
        $product  = Product::findOrFail( $request->id );
        $response = Response::HTTP_OK;
        try {
            $product->delete();
            $message = 'Удаление выполнено успешно!';
            $success = true;
        } catch ( QueryException $exception ) {
            $message = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $success = false;
        }


        return response()->json( [
            'success' => $success,
            'message' => $message,
        ] )->setStatusCode( $response );
    }
}
