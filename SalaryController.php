<?php

namespace App\Http\Controllers\Fronted;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Managesalaries;
use App\Models\Payments;
use App\Models\Attendance;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use PDF;
use File;
use Illuminate\Support\Facades\Storage;

class SalaryController extends Controller
{
    public $data;
    public $db;
    public $other;
    public $id;
    public function __construct()
    {
        $this->middleware('lbauth');
    }
    
   
    public function exportpdf($month){
            $employee = Helper::lbauth();
            $payments = Payments::where([
                ['emp_id', '=', $employee->id],
                ['payment_month', '=', $month]
            ])->first();
            $salaries = Managesalaries::where('emp_id', '=', $employee->id)->first();
       
            $present = Attendance::where([
                ['emp_id', '=', $employee->id],
                ['attendance', '=', 'present'],
                ['date', 'like', '%' . $month . '%'],
            ])->count();
            $absence = Attendance::where([
                ['emp_id', '=', $employee->id],
                ['attendance', '=', 'absent'],
                ['date', 'like', '%' . $month . '%'],
            ])->count();
        $path = Storage::path('public/logo.png');
        $file = file_get_contents($path);
        $logo = 'data:image/png;base64,' . base64_encode($file);
        $pdf = PDF::loadView('fronted.pdf.salarySlip', compact('employee', 'payments','present', 'absence', 'salaries','logo'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('salary_slip.pdf');
        // return view('fronted.pdf.salarySlip', compact('employee', 'payments', 'present', 'absence', 'salaries'));
     
    }

    public function __destruct()
    {
        unset($this->data);
        unset($this->db);
        unset($this->other);
        unset($this->id);
    }
}
