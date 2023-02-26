<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Merk;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($column_name = null, $column_value = null, $send_data = false)
    {
        $data = array(
            [
                'tab_name' => 'All',
                'products' => null,
            ]
        );

        $products = Product::all()->toArray();

        $data[0]['products'] = $products;

        $merk = Merk::all();
        foreach ($merk as $element) {
            $products = Product::where('merk_id', $element->id)->get()->toArray();
            $tab = [
                'tab_name' => $element->merk,
                'products' => $products,
            ];

            $data[] = $tab;
        }

        $category = Category::all();
        foreach ($category as $element) {
            $products = Product::where('category_id', $element->id)->get()->toArray();
            $tab = [
                'tab_name' => $element->category,
                'products' => $products,
            ];

            $data[] = $tab;
        }

        // dd($data);

        return view('main')->with(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
