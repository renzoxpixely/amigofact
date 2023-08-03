<?php

namespace Modules\Contract\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contract\Http\Resources\ContractTypeCollection;
use Modules\Contract\Http\Requests\ContractTypeRequest;
use Modules\Contract\Http\Resources\ContractTypeResource;
use Modules\Contract\Models\ContractType;

class ContractTypeController extends Controller
{

    public function index()
    {
        return view('contract::contract_types.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = ContractType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ContractTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = new ContractTypeResource(ContractType::findOrFail($id));
		
        return $record;
    }

    public function tables() {
        $types = [
            ['type' => ContractType::TYPE_SALE, 'description' => 'Venta'],
            ['type' => ContractType::TYPE_MONTHLY, 'description' => 'Mensual']
        ];
        return compact('types');
    }

    public function store(ContractTypeRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $contract_type = null;
        if (!empty($name)) {
            $contract_type = ContractType::where('name', $name);
            if (empty($id)) {
                $contract_type = $contract_type->first();
                if (!empty($contract_type)) {
                    $error = 'El nombre de Tipo de Contrato ya existe';
                }
            } else {
                $contract_type = $contract_type->where('id', '!=', $id)->first();
                if (!empty($contract_type)) {
                    $error = 'El nombre de Tipo de Contrato ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $contract_type
        ];
        if (empty($error)) {
            $contract_type = ContractType::firstOrNew(['id' => $id]);
            $contract_type->fill($request->all());
            $contract_type->save();
            $data = [
                'success' => true,
                'message' => ($id) ? 'Tipo de Contrato editado con éxito' : 'Tipo de Contrato registrado con éxito',
                'data' => $contract_type
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $brand = ContractType::findOrFail($id);
            $brand->delete();

            return [
                'success' => true,
                'message' => 'Tipo de Contrato eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El tipo de contrato esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la Marca"];

        }

    }




}
