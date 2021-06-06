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
     * Display the specified resource.
     *
     * @param int $id
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
