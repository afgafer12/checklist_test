<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use App\Utils\UtilityApi;
use Exception;
use Illuminate\Support\Facades\Validator;

class CheckListItemController extends Controller
{
    private $utilApi;

    function __construct(UtilityApi $utilApi){
        $this->utilApi = $utilApi;
    }

    public function getAll($clId){
        $items = ChecklistItem::where('checklist_id', $clId)
        ->get();
        return $this->utilApi->resp($items, 200);
    }

    public function getDetil($clId, $itemId){
        $item = ChecklistItem::where('checklist_id', $clId)
        ->where('id', $itemId)
        ->first();
        return $this->utilApi->resp($item, 200);
    }

    public function create($clId, Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "itemName" => "required",
            ]
        );
        if($validator->fails()){
            return $this->utilApi->resp($validator->errors(), 422, 422,);
        }
        try{
            $cl = new ChecklistItem();
            $cl->checklist_id =  $clId;
            $cl->item_name = $request->itemName;
            $cl->save();
            return $this->utilApi->resp($cl, 200);
        }catch(Exception $e ){
            return $this->utilApi->resp($e, 500, 500, 'Internal server eroor');
        }
    }
    public function updateStatus($clId, $itemId, Request $request){
        $item = ChecklistItem::where('checklist_id', $clId)
        ->where('id', $itemId);
        $item->update([
            'status'=> 'done'
        ]);
        $resItem = $item->first();
        return $this->utilApi->resp($resItem, 200);
    }
    public function updateRename($clId, $itemId, Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "itemName" => "required",
            ]
        );
        if($validator->fails()){
            return $this->utilApi->resp($validator->errors(), 422, 422,);
        }

        $item = ChecklistItem::where('checklist_id', $clId)
        ->where('id', $itemId);
        $resItem = $item->first();
        if(!$resItem){
            return $this->utilApi->resp(null, 404, 404);
        }
        $item->update([
            'item_name'=> $request->itemName
        ]);
        $resItem = $item->first();
        return $this->utilApi->resp($resItem, 200);
    }
    public function delete($clId, $itemId, Request $request){
        $item = ChecklistItem::find($itemId);
        $item = ChecklistItem::where('checklist_id', $clId)
        ->where('id', $itemId);
        $resItem = $item->first();
        if(!$resItem){
            return $this->utilApi->resp(null, 404, 404);
        }
        $item->delete();
        return $this->utilApi->resp(null, 200);
    }
}
