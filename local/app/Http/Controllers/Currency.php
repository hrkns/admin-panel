<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterCurrency;
use App\Models\MasterCurrencyExchange;
use App\Models\MasterCurrencyStatus;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Currency extends Controller
{
    public function create(Request $request)
    {
        $code = trim($request->input("data.code"));
        $name = sanitize(trim($request->input("data.name")));
        $description = sanitize(trim($request->input("data.description")));
        $new_currency = new MasterCurrency;
        $new_currency->name = generateMultilingual($name);
        $new_currency->description = generateMultilingual($description);
        $new_currency->code = $code;
        $new_currency->__create__();
        $lstatus = $request->input("data.status");

        if (gettype($lstatus) != "array") {
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();
            if (count($st)>0) {
                $new_currency->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_currency->available_for_use = $available_for_use?'1':'0';
        $new_currency->save();
        $this->writeConstants("MasterCurrency", "currencies");
        operation("CREATE_CURRENCY");
        return \Response::json([
            'item' => array(
                "id"            =>  $new_currency->id,
                "name"          =>  translate($new_currency->name),
                "description"   =>  translate($new_currency->description),
                "code" =>  $new_currency->code,
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, MasterCurrency::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_CURRENCIES");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterCurrency:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_CURRENCIES");
    }

    public function read(Request $request, $id)
    {
        $item = MasterCurrency::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        operation("READ_CURRENCY");

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "code" =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $code = $request->input("data.code");
        $item = MasterCurrency::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $code;

        if (gettype($status) != "array") {
            $status = array();
        }

        $item->delete_Status();
        $available_for_use = false;

        foreach ($status as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if (count($st)>0) {
                $item->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }
        $item->available_for_use = $available_for_use?'1':'0';
        $item->__update__();
        $this->writeConstants("MasterCurrency", "currencies");
        operation("UPDATE_CURRENCY");

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = MasterCurrency::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants("MasterCurrency", "currencies");
        operation("DELETE_CURRENCY");
        return \Response::json(array(), 200);
    }

    public function createExchange(Request $request)
    {
        $currency_1 = $request->input("data.currency_1");
        $currency_2 = $request->input("data.currency_2");
        $value = $request->input("data.value");

        $existe = MasterCurrencyExchange::where(function ($query) use ($currency_1, $currency_2) {
            $query->where("id_currency", "=", $currency_1);
            $query->where("id_currency_2", "=", $currency_2);
        })->orWhere(function ($query) use ($currency_1, $currency_2) {
            $query->where("id_currency", "=", $currency_2);
            $query->where("id_currency_2", "=", $currency_1);
        })->get();

        if (count($existe) > 0) {
            //conflict
            return \Response::json(array(), 400);
        } else {
            //crear
            $exchange = new MasterCurrencyExchange;
            $exchange->id_currency = $currency_1;
            $exchange->id_currency_2 = $currency_2;
            $exchange->value = $value;
            $exchange->__create__();
            operation("CREATE_CURRENCY_EXCHANGE");
            return \Response::json(array(
                "item"=>array(
                    "currency_1"=>$currency_1,
                    "currency_2"=>$currency_2,
                    "value"     =>$value,
                    "id"        =>$exchange->id
                )
            ), 200);
        }
    }

    public function updateExchange(Request $request, $id)
    {
        $exchange = MasterCurrencyExchange::where("id", "=", $id)->get()[0];
        $exchange->value = $request->input("data.value");
        $exchange->__update__();
        operation("UPDATE_CURRENCY_EXCHANGE");
        return \Response::json(array(
        ), 200);
    }

    public function deleteExchange(Request $request, $id)
    {
        $item = MasterCurrencyExchange::where("id", "=", $id)->get()[0];
        $item->__delete__();
        operation("DELETE_CURRENCY_EXCHANGE");
        return \Response::json(array(), 200);
    }

    public function readExchanges(Request $request)
    {
        $exchanges = MasterCurrencyExchange::all();
        $ret = array();

        foreach ($exchanges as $key => $value) {
            array_push($ret, array(
                "currency_1" => $value->id_master_currency,
                "currency_2" => $value->id_currency_2,
                "value" => $value->value,
                "id"    => $value->id
            ));
        }

        operation("READ_CURRENCY_EXCHANGES");
        return \Response::json(array("items"=>$ret), 200);
    }
}
