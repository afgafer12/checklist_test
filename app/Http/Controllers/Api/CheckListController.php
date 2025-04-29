<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\Request;
use App\Utils\UtilityApi;
use Exception;
use Illuminate\Support\Facades\Validator;

class CheckListController extends Controller
{
    private $utilApi;

    function __construct(UtilityApi $utilApi){
        $this->utilApi = $utilApi;
    }

    public function getAll(){
        $cl = Checklist::get();
        return $this->utilApi->resp($cl, 200);
    }

    public function create(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required",
            ]
        );
        if($validator->fails()){
            return $this->utilApi->resp($validator->errors(), 422, 422,);
        }
        try{
            $cl = new Checklist();
            $cl->name = $request->name;
            $cl->save();
            return $this->utilApi->resp($cl, 200);
        }catch(Exception $e ){
            return $this->utilApi->resp(null, 500, 500, 'Internal server eroor');
        }
    }
    public function delete($clId, Request $request){
        $cl = Checklist::find($clId);
        if(!$cl){
            return $this->utilApi->resp(null, 404, 404);
        }
        $cl->delete();
        return $this->utilApi->resp(null, 200);
    }
}
