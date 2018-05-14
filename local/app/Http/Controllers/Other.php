<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Other extends Controller
{
    public function id_to_code(Request $request, $context)
    {
        require base_path()."/storage/admin-panel/id-to-code/".$context.".php";
        return \Response::json([
            'items' => $GLOBALS["ID_TO_CODE_".strtoupper($context)],
        ], 200);
    }

    public function code_to_id(Request $request, $context)
    {
        require base_path()."/storage/admin-panel/code-to-id/".$context.".php";
        return \Response::json([
            'items' => $GLOBALS["CODE_TO_ID_".strtoupper($context)],
        ], 200);
    }
}
