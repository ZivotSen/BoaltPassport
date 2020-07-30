<?php
/**
 * Created by PhpStorm.
 * User: ageorge
 * Date: 7/28/2020
 * Time: 12:15 AM
 */

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Show the client view.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('passport/client');
    }

    /**
     * Show the clients registered by the logged user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(){
        $clients = Client::where('user_id', Auth::user()->id)->get();
        return view('passport/show')->with([
            'clients' => $clients
        ]);
    }

    public function current(Request $request){
        $user = Auth::user();
        if($user){
            return response()->json([
                $user->toArray()
            ]);
        }

        return response()->json([
            'error' => 'The requested token is not associated anymore with a logged user.'
        ]);
    }
}
