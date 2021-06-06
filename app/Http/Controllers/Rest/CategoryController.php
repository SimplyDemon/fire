<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\Destroy;
use App\Http\Requests\Category\Store;
use App\Http\Requests\Category\Update;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller {
    const QUERY_EXCEPTION_READABLE_MESSAGE = 2;

    /**
     * @return JsonResponse
     * @api {get} /api/v1/categories
     * @apiName Index
     * @apiGroup Categories
     *
     * Display a listing of the resource.
     *
     */
    public function index() {
        $success    = true;
        $categories = Category::orderBy( 'id', 'asc' )->get();
        foreach ( $categories as $key => &$category ) {
            $category['priceMin'] = $category->products()->min( 'price' );
            $category['priceMax'] = $category->products()->max( 'price' );
            $category['count']    = $category->products()->count();
        }

        return response()->json( [
            'success'    => $success,
            'categories' => $categories,
        ] )->setStatusCode( Response::HTTP_OK );
    }

    /**
     * @api {post} /api/v1/categories
     * @apiName Store
     * @apiGroup Categories
     *
     * @apiParam {string} [title] Category name
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
            $category = Category::create( $request->all() );
            $message  = 'Добавление выполнено успешно!';
            $success  = true;
            $response = Response::HTTP_CREATED;
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $success  = false;
            $response = Response::HTTP_OK;
        }

        return response()->json( [
            'success'  => $success,
            'category' => $category ?? null,
            'message'  => $message,
        ] )->setStatusCode( $response );
    }

    /**
     * @api {get} /api/v1/categories/{category_id}
     * @apiName Show
     * @apiGroup Categories
     *
     * @apiParam {int} [category_id] Category Id
     *
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function show( Category $category ) {
        $success              = true;
        $category['priceMin'] = $category->products()->min( 'price' );
        $category['priceMax'] = $category->products()->max( 'price' );

        return response()->json( [
            'success'  => $success,
            'category' => $category,
        ] )->setStatusCode( Response::HTTP_OK );
    }

    /**
     * @api {put} /api/v1/categories/{category_id}
     * @apiName Update
     * @apiGroup Categories
     *
     * @apiParam {int} [category_id] Category Id
     * @apiParam {int} [id] Category Id
     * @apiParam {string} [title] Category name
     *
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update( Update $request, $id ) {
        $category = Category::findOrFail( $id );
        $slug     = Str::slug( $request->title, '-' );
        $request->merge( [ 'slug' => $slug ] );
        try {
            $category->update( $request->all() );
            $message  = 'Обновление выполнено успешно!';
            $success  = true;
            $response = Response::HTTP_ACCEPTED;
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $success  = false;
            $response = Response::HTTP_OK;
        }

        return response()->json( [
            'success'  => $success,
            'category' => $category,
            'message'  => $message,
        ] )->setStatusCode( $response );

    }

    /**
     * @api {delete} /api/v1/categories/{category_id}
     * @apiName Destroy
     * @apiGroup Categories
     *
     * @apiParam {int} [category_id] Category Id
     * @apiParam {int} [id] Category Id
     *
     *
     * Remove the specified resource from storage.
     *
     * @param Destroy $request
     *
     * @return JsonResponse
     */
    public function destroy( Destroy $request ) {
        $category = Category::findOrFail( $request->id );
        $response = Response::HTTP_OK;
        if ( ! $category->products->isEmpty() ) {
            $message = 'Нельзя удалить категорию, в которой есть товары.';
            $success = false;
        } else {
            try {
                $category->delete();
                $message = 'Удаление выполнено успешно!';
                $success = true;
            } catch ( QueryException $exception ) {
                $message = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
                $success = false;
            }
        }

        return response()->json( [
            'success' => $success,
            'message' => $message,
        ] )->setStatusCode( $response );
    }
}
