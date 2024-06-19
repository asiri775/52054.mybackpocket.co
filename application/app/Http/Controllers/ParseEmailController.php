<?php

namespace App\Http\Controllers;

use App\Helpers\ParserHelper;

class ParseEmailController extends Controller
{

    public function index()
    {
        ParserHelper::run();
    }
}
