<?php

namespace App\Http\Controllers\AgencyController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AgencyController extends Controller
{
    public function __construct() {
        $this->middleware('check-role');
    }

    public function index(Request $request)
    {
        echo "hi";
    }

}
