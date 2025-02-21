<?php

namespace App\Http\Controllers;

use App\Models\StudentLearningRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function basic()
    {
        return view('activity.basic'); // Return Blade view
    }

    public function chooseLevel()
    {
        return view('activity.chooseLevel'); // Make sure the view exists in resources/views/activity
    }

}
