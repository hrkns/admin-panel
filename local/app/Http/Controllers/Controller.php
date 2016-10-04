<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $keywords_search;
    private $token;
    private $tokens_list;
    private $items_to_return;

    public function index_items($request, $base_items, $attrs, $op_code, $config = array("status"=>true), $extra_func = null)
    {
        $this->code_status = 200;
        $this->token = $request->input("data.token");
        $this->tokens_list = $request->session()->get(PROGRESSIVE_REQUEST_TOKENS);
        $this->items_to_return = array();
        $page = $request->input("data.page");

        if (gettype($this->tokens_list) == "array" && array_key_exists($this->token, $this->tokens_list)) {
            $type = $request->input("data.type_request");
            $see_all = isTrue($request->input("data.see_all"));

            if (!array_key_exists($type, $this->tokens_list[$this->token]) || $request->has("data.reset")) {
                $this->tokens_list[$this->token][$type] = array( "items"=>$base_items,
                                            "index"=>0);
                $this->tokens_list[$this->token][$type]["length"] = count($this->tokens_list[$this->token][$type]["items"]);
            }

            if ($page != null) {
                $page = intval($page) - 1;
                $start = $page * AMOUNT_ITEMS_PER_REQUEST;
            } else {
                $start = $this->tokens_list[$this->token][$type]["index"];
            }

            for ($i = $start; ($see_all && $i < $this->tokens_list[$this->token][$type]["length"]) || ($i < AMOUNT_ITEMS_PER_REQUEST + $start && $i < $this->tokens_list[$this->token][$type]["length"]); $i++) {
                $model = $this->tokens_list[$this->token][$type]["items"][$i];

                $item = array();

                if (isset($config["status"]) && $config["status"]) {
                    $status = $model->read_Status;
                    $statuses=array();

                    foreach ($status as $key => $value) {
                        array_push($statuses, $value->id_status);
                    }

                    $item["status"] = $statuses;
                }

                foreach ($attrs as $attr => $config_attr) {
                    if (isset($config_attr["translate"]) && $config_attr["translate"]) {
                        $item[$attr] = translate($model->$attr);
                    } else {
                        $item[$attr] = $model->$attr;
                    }
                }

                if ($extra_func != null) {
                    $extra_func(["model"=>$model, "request"=>$request, "see_all"=>$see_all], $item, $i);
                }

                $item["id"] = $model->id;
                array_push($this->items_to_return, $item);

                if ($page === null) {
                    $this->tokens_list[$this->token][$type]["index"]+=1;
                }
            }

            $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, $this->tokens_list);
        } else {
            $this->code_status=400;
            $this->items_to_return = $this->token;
        }

        operation($op_code);

        return \Response::json([
            'items' => $this->items_to_return,
            "total" => isset($this->tokens_list[$this->token])?$this->tokens_list[$this->token][$type]["length"]:0,
        ], $this->code_status);
    }

    public function search_items($request, $base_items, $attrs, $op_code, $config = array("status"=>true), $extra_func = null)
    {
        $this->keywords_search = $request->input("data.keywords_search");
        $this->code_status = 200;
        $this->token = $request->input("data.token");
        $this->tokens_list = $request->session()->get(PROGRESSIVE_REQUEST_TOKENS);
        $this->items_to_return = array();
        $see_all = false;
        $page = $request->input("data.page");

        if (gettype($this->tokens_list) == "array" && array_key_exists($this->token, $this->tokens_list)) {
            $this->items_to_return = array();
            $type = $request->input("data.type_request");
            $see_all = isTrue($request->input("data.see_all"));


            if (!array_key_exists($type, $this->tokens_list[$this->token]) || $this->tokens_list[$this->token][$type]["txt"] != $this->keywords_search || $request->has("data.reset")) {
                $this->tokens_list[$this->token][$type] = array( "items"=>$base_items,
                                            "index"=>0,
                                            "txt"  =>$this->keywords_search);
                $this->tokens_list[$this->token][$type]["length"] = count($this->tokens_list[$this->token][$type]["items"]);
            }

            if ($page != null) {
                $page = intval($page) - 1;
                $start = $page * AMOUNT_ITEMS_PER_REQUEST;
            } else {
                $start = $this->tokens_list[$this->token][$type]["index"];
            }

            for ($i = $start; ($see_all && $i < $this->tokens_list[$this->token][$type]["length"]) || ($i < AMOUNT_ITEMS_PER_REQUEST + $start && $i < $this->tokens_list[$this->token][$type]["length"]); $i++) {
                $model = $this->tokens_list[$this->token][$type]["items"][$i];

                $item = array();

                if (isset($config["status"]) && $config["status"]) {
                    $status = $model->read_Status;
                    $statuses=array();

                    foreach ($status as $key => $value) {
                        array_push($statuses, $value->id_status);
                    }

                    $item["status"] = $statuses;
                }

                foreach ($attrs as $attr => $config_attr) {
                    if (isset($config_attr["translate"]) && $config_attr["translate"]) {
                        $item[$attr] = translate($model->$attr);
                    } else {
                        $item[$attr] = $model->$attr;
                    }
                }

                if ($extra_func != null) {
                    $extra_func(["model"=>$model, "request"=>$request], $item, $i);
                }

                $item["id"] = $model->id;
                array_push($this->items_to_return, $item);

                if ($page === null) {
                    $this->tokens_list[$this->token][$type]["index"]+=1;
                }
            }

            $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, $this->tokens_list);
        } else {
            $this->keywords_search=400;
        }

        operation($op_code);

        return \Response::json([
            'items' => $this->items_to_return,
            "total" => $this->tokens_list[$this->token][$type]["length"]
        ], $this->code_status);
    }

    public function writeConstants($class, $file)
    {
        $class = "App\\Models\\".$class;
        $items = $class::all();
        $f1 = fopen(base_path()."/storage/admin-panel/code-to-id/".$file.".php", "w");
        fwrite($f1, "<?php\n\t\$GLOBALS[\"CODE_TO_ID_".strtoupper($file)."\"] = [\n");
        foreach ($items as $key => $value) {
            fwrite($f1, "\t\t'".$value->code."' => ".$value->id.",\n");
        }
        fwrite($f1, "\t]\n?>");
        fclose($f1);

        $f2 = fopen(base_path()."/storage/admin-panel/id-to-code/".$file.".php", "w");
        fwrite($f2, "<?php\n\t\$GLOBALS[\"ID_TO_CODE_".strtoupper($file)."\"] = [\n");
        foreach ($items as $key => $value) {
            fwrite($f2, "\t\t'".$value->id."' => '".$value->code."',\n");
        }
        fwrite($f2, "\t]\n?>");
        fclose($f2);
    }
}
