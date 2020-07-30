<?php
/**
 * Created by PhpStorm.
 * User: ageorge
 * Date: 7/30/2020
 * Time: 1:08 AM
 */

namespace App\Http\Controllers;

use App\PHP\Yelp;
use Illuminate\Http\Request;

class YelpAPIController extends Controller
{
    // General route for businesses
    public function businesses(Request $request, $route = null){
        $searchFor = "/businesses/".$route;
        $parameters = $request->all();

        $yelp = new Yelp(); // Yelp class initialization
        $response = $yelp->getRequest($searchFor, $parameters); // Call the requested route
        return response()->json($response);
    }
}
