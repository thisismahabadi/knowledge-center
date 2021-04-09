<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const SUCCESS = 'Success';
    const ERROR = 'Error';

    /**
     * Return a json response.
     *
     * @param string $status
     * @param $data
     * @param int $code
     *
     * @return object
     */
    public function setResponse(string $status, $data, int $code): object
    {
    	return response()->json([
		    'status' => $status,
		    'data' => $data,
		    'code' => $code,
		], $code);
    }
}
