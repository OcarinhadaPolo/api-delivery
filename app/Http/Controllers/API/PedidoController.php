<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Pedido;
use App\Http\Resources\Pedido as PedidoResource;
   
class PedidoController extends BaseController
{
    public function index()
    {
        $pedidos = Pedido::all();
        return $this->sendResponse(PedidoResource::collection($pedidos), 'Pedidos fetched.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'produto' => 'required',
            'valor' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $pedido = Pedido::create($input);
        return $this->sendResponse(new PedidoResource($pedido), 'Pedido created.');
    }
   
    public function show($id)
    {
        $pedido = Pedido::find($id);
        if (is_null($pedido)) {
            return $this->sendError('Pedido does not exist.');
        }
        return $this->sendResponse(new PedidoResource($pedido), 'Pedido fetched.');
    }
    
    public function update(Request $request, Pedido $pedido)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'produto' => 'required',
            'valor' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $pedido->produto = $input['produto'];
        $pedido->valor = $input['valor'];
        $pedido->save();
        
        return $this->sendResponse(new PedidoResource($pedido), 'Pedido updated.');
    }
   
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return $this->sendResponse([], 'Pedido deleted.');
    }
}