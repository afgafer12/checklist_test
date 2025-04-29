<?php

namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class UtilityApi
{
    protected $msg = [
        '200'=> 'Success',
        '422'=> 'Unprocessable Entity',
        '404'=> 'Not found',
        '500'=> 'Internal server error',
    ];

    public function resp($data, int $code = 200 , int $httpStatus = 200, $msg = ''){
        if($code == 500 || $code == 422){
            return response()->json([
                'code' => $code,
                'message' => $this->msg[$code],
                'errors' => $data,
            ], $code);
        }

        $resp = [
            'code' => $code,
            'message' => $msg == '' ? $this->msg[$code] : $msg,
            'data' => $data,
        ];
        // if($data instanceof LengthAwarePaginator || $data instanceof Paginator){
        //     $resp['data'] = $data->items();
        //     $resp['meta'] = [
        //         "current_page"=>$data->currentPage(),
        //         "total"=>$data->total(),
        //         "per_page"=>$data->perPage(),
        //         "first_item"=>$data->firstItem(),
        //         "last_page"=>$data->lastPage(),
        //     ];
        // }else{
        //     $resp['data'] = $data;
        // }
        return response()->json( $resp, $httpStatus);
  }
}
