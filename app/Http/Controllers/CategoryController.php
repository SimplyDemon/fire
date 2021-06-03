<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Destroy;
use App\Http\Requests\Category\Store;
use App\Http\Requests\Category\Update;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    protected $folderPath = 'categories.';
    const QUERY_EXCEPTION_READABLE_MESSAGE = 2;
    protected $all;


    function __construct() {
        $this->all = Category::orderBy( 'id', 'desc' )->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return response()->view( $this->folderPath . 'index', [ 'all' => $this->all ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        return response()->view( $this->folderPath . 'create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return RedirectResponse
     */
    public function store( Store $request ) {
        $slug = Str::slug( $request->title, '-' );
        $request->merge( [ 'slug' => $slug ] );
        try {
            $category = Category::create( $request->all() );
            $message  = 'Добавление выполнено успешно!';
            $redirect = redirect( route( $this->folderPath . 'show', [ 'category' => $category->id ] ) );
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $redirect = redirect()->back()->withInput();
        }
        $request->session()->flash( 'message', $message );

        return $redirect;
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return Response
     */
    public function show( Category $category ) {
        return response()->view( $this->folderPath . 'show', [ 'single' => $category ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     *
     * @return Response
     */
    public function edit( Category $category ) {
        return response()->view( $this->folderPath . 'edit', [ 'single' => $category ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param int $id
     *
     * @return Redirector
     */
    public function update( Update $request, $id ) {
        $single   = Category::findOrFail( $id );
        $method   = $request->input( 'method' );
        $redirect = redirect( route( $this->folderPath . 'index' ) );
        try {
            $single->update( $request->except( 'method' ) );
            $message = 'Обновление выполнено успешно!';
        } catch ( QueryException $exception ) {
            $message  = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
            $redirect = redirect()->back()->withInput();
        }

        $request->session()->flash( 'message', $message );

        if ( $method == 'Применить' ) {
            $redirect = redirect()->back()->withInput();
        }

        return $redirect;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Destroy $request
     *
     * @return Redirector
     */
    public function destroy( Destroy $request ) {
        $single = Category::findOrFail( $request->id );
        try {
            $single->delete();
            $message = 'Удаление выполнено успешно!';
        } catch ( QueryException $exception ) {
            $message = $exception->errorInfo[ self::QUERY_EXCEPTION_READABLE_MESSAGE ];
        }

        $request->session()->flash( 'message', $message );

        return redirect( route( $this->folderPath . 'index' ) );
    }
}
