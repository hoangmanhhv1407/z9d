<?php namespace App\Http\Controllers\Frontend;use Illuminate\Http\Request;use App\Http\Controllers\Controller;class AboutUsController extends Controller{public function productDetail(){return view('frontend.product_detail');}public function postList(){return view('frontend.post_list');}public function productList(){return view('frontend.product_list');}public function postDetail(){return view('frontend.post_detail');}}