<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function get_autocomplete_cust(){
        
        if (isset($_GET['term'])) {
            // $result = $this->model->search($_GET['term']);
            $result = Customer::where([['status', '=',1], ['kode','like','%'.$_GET["term"].'%']])->orderBy('name')->get();

            // dd($result);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array( 
                        'label'         => $row['kode'].' | '. $row['name'],
                        'kode'          => $row['kode'],
                        'name'          => $row['name'],
                        'telp'         => $row['telp']
                    );
                    echo json_encode($arr_result);
                
            }
        }
    }
}
