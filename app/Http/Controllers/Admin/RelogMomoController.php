<?php namespace App\Http\Controllers\Admin;use Illuminate\Http\Request;use App\Http\Controllers\Controller;use GuzzleHttp\Client;class RelogMomoController extends Controller{public function index(){$body=['username'=>env('MOMO_PHONE'),'password'=>env('MOMO_PASSWORD'),'accountNumber'=>env('MOMO_PHONE')];$client=new Client(['headers'=>['Content-Type'=>'application/json'],]);$response=$client->request('POST','https://apibank.otpsystem.com/api/momo/balance',['body'=>json_encode($body)]);$response=$response->getBody()->getContents();$response=json_decode($response,true);$balance='';if($response['success']){$balance=$response['data']['balance'];return view('admin.momoLogin.index',['balance'=>$balance]);}return view('admin.momoLogin.index',['balance'=>$balance]);}public function login(){$body=['username'=>env('MOMO_PHONE'),'password'=>env('MOMO_PASSWORD'),'accountNumber'=>env('MOMO_PHONE')];$client=new Client(['headers'=>['Content-Type'=>'application/json'],]);$response=$client->request('POST','https://apibank.otpsystem.com/api/momo/init-login',['body'=>json_encode($body)]);$response=$response->getBody()->getContents();$response=json_decode($response,true);if($response['success']){return 'success';}return 'failed';}public function otp(Request $request){$body=['username'=>env('MOMO_PHONE'),'password'=>env('MOMO_PASSWORD'),'accountNumber'=>env('MOMO_PHONE'),'otp'=>$request['otp'],];$client=new Client(['headers'=>['Content-Type'=>'application/json'],]);$response=$client->request('POST','https://apibank.otpsystem.com/api/momo/confirm-login',['body'=>json_encode($body)]);$response=$response->getBody()->getContents();$response=json_decode($response,true);if($response['success']){return 'success';}return 'failed';}public function check(){$body=['username'=>env('MOMO_PHONE'),'password'=>env('MOMO_PASSWORD'),'accountNumber'=>env('MOMO_PHONE')];$client=new Client(['headers'=>['Content-Type'=>'application/json'],]);$response=$client->request('POST','https://apibank.otpsystem.com/api/momo/balance',['body'=>json_encode($body)]);$response=$response->getBody()->getContents();$response=json_decode($response,true);if($response['success']){return 'success';}return 'failed';}}