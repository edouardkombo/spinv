<?php namespace App\Http\Controllers;

use App\Http\Requests\ProductFormRequest;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;

class ProductsController extends Controller {

    private $product;

    public function __construct(Product $product){
        $this->middleware('auth');
        $this->product = $product;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$products = $this->product->all();
        return view('products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('products.create');
	}

    /**
     * Store a newly created resource in storage.
     * @param ProductFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(ProductFormRequest $request)
	{
		if($this->product->create($request->all())){
            flash()->success('Product has been added');
            return response()->json(array('success'=>true, 'msg' => 'expense updated'), 201);
        }
        return response()->json(array('success'=>false, 'msg' => 'product not added'), 422);
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $product = $this->product->getById($id);
		return view('products.edit', compact('product'));
	}

    /**
     * Update the specified resource in storage.
     * @param ProductFormRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(ProductFormRequest $request, $id)
	{
		if($this->product->updateById($id,$request->all())){
            flash()->success('Product has been updated');
            return response()->json(array('success'=>true, 'msg' => 'product updated'), 201);
        }
        return response()->json(array('success'=>false, 'msg' => 'product not updated'), 422);
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($this->product->deleteById($id))
            flash()->success('Product deleted');
        else
            flash()->error('Product delete failed');

        return redirect('products');
	}

}
