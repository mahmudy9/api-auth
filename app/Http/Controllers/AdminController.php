<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Illuminate\Support\Facades\Hash;
use Validator;

class AdminController extends Controller
{

    public function __construct()
    {
        return $this->middleware(['auth']);
    }

    public function index()
    {
        $clients = Client::paginate(20);
        return view('admin.index' , compact('clients'));
    }

    public function new_client()
    {
        return view('admin.newclient');
    }

    public function create_client(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => 'required|numeric|unique:clients,phone',
            'name' => 'required|string|min:3|max:190',
            'password' => 'required|string|min:6|max:190'
        ]);
        if($validator->fails())
        {
            return redirect('/admin/new-client')->withErrors($validator)->withInput();
        }

        $client = new Client;
        $client->phone = $request->input('phone');
        $client->name  = $request->input('name');
        $client->password = Hash::make($request->input('password'));
        $client->verified_at = date('Y-m-d H:i:s');
        $client->save();
        return redirect('/admin');
    }

    public function update_client($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.updateclient' , compact('client'));
    }

    public function update_client_data(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'clientid' => 'required|integer',
            'phone' => 'required|numeric',
            'name' => 'required|string|min:3|max:190',
            'password' => 'confirmed',
            'verified' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('admin/updateclient/'.$request->input('clientid'))->withErrors($validator);
        }
        $client = Client::findORFail($request->input('clientid'));
        if($client->phone != $request->input('phone'))
        {
            $client->phone = $request->input('phone');
        }
        $client->name = $request->input('name');
        if($request->has('password'))
        {
            $client->password = Hash::make($request->input('password'));
        }
        if($request->input('verified') == 1)
        {
            $client->verified_at = date('Y-m-d H:i:s');
        }else{
            $client->verified_at = null;
        }
        $client->save();
        $request->session()->flash('status' , 'user updated successfully');
        return redirect('/admin');
    }

    public function delete_client($id)
    {
        $client = Client::find($id);
        return view('admin.deleteclient' , compact('client'));
    }

    public function destroy_client(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'clientid' => 'required|integer'
        ]);
        if($validator->fails())
        {
            return redirect('/admin');
        }
        $client = Client::findOrFail($request->input('clientid'));
        $client->delete();
        $request->session()->flash('status' , 'client removed successfully');
        return redirect('/admin');
    }

}
