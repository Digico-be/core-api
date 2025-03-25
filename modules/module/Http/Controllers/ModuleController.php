<?php

namespace Diji\Module\Http\Controllers;

use App\Http\Controllers\Controller;
use Diji\Module\Models\Module;

class ModuleController extends Controller
{
    public function index()
    {
        return Module::all();
    }
}
