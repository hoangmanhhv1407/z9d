<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $showContact = Contact::paginate(10);
        return view('admin.contact.index',compact('showContact'));
    }

    public function dislay(Request $request)
    {
        $id = $request->id;
        $contact = Contact::where('id',$id)->first();
        return response()->json($contact);
    }
}
