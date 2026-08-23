<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\ElementaryStudent;

class BillsController extends Controller
{
    public function printPdf(ElementaryStudent $student){
        $student = ElementaryStudent::find($student->id);
        $bills = $student->bills;
        return $student;
        return view('test', compact('student', 'bills'));
    }
}
