<?php

namespace App\Modules\Product\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;

class ProductController extends Controller {

    public function addProduct() {
        return view('Product::add_product');
    }
    
    public function addProductProcess(\App\Http\Requests\Productrequest $request){
        $getProduct = new Product();
        
        $getProduct->code = $request->input('pcode');
        $getProduct->product_name = $request->input('pname');
        $getProduct->product_description = $request->input('pdescription');
        $getProduct->unit_price = $request->input('uprice');
        $getProduct->vat = $request->input('vat');
        $getProduct->discount_pc = $request->input('dprice');
        $getProduct->discount_type = $request->input('discount_type');
        $getProduct->item_type_id = $request->input('item_type');
        $getProduct->product_group_id = $request->input('pg_id');
        $getProduct->product_sub_group_id = $request->input('psg_id');
        $getProduct->product_unit_id = $request->input('pu_id');
        $getProduct->chart_of_account_id = $request->input('coa_id');
        
        $getProduct->save();
        
        return redirect('add_product');
    }

}
