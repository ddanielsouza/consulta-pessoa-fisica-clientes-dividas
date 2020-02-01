<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

abstract class ControllerModel  extends Controller
{
    protected $modelName;
    protected $basicValidate = [];
    protected $columnsEncrypted = [];

    public function getById($id)
    {
        try {
            $result = $this->modelName::find($id);
            return response()->json(['success' => true, 'data' => $result], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), $this->basicValidate);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = new $this->modelName();
            foreach ($request->all() as $key => $value) {
                $model->$key = $value;
            }
            $model->save();
            return response()->json(['success' => true, 'data' => $model], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), $this->basicValidate);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = $this->modelName::find($id);

            if (!empty($model)) {
                foreach ($request->all() as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
                return response()->json(['success' => true, 'data' => $model], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'registro não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $rules = [];
            foreach ($this->basicValidate as $key => $value) {
                $rules[$key] = preg_replace('/required\\||\\|required/im', '', $value);
            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = $this->modelName::find($id);

            if (!empty($model)) {
                foreach ($request->all() as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
                return response()->json(['success' => true, 'data' => $model], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'registro não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function get(Request $request)
    {
        try{
            $model = $this->modelName::whereNotNull('id');

            $columnsSelect = json_decode($request->input('columnsSelect'));
            if(json_last_error() == JSON_ERROR_NONE && is_array($columnsSelect)){
                $model->select($columnsSelect);
            }

            foreach($request->all() as $key => $value){
                if(in_array($key, array_keys($this->columnsEncrypted))){

                    $columnName = $this->columnsEncrypted[$key];
                    $model->where($columnName, hash('md5', $value));

                }else if(in_array($key, array_keys($this->getRules()))){

                    if(is_array($value) && count($value) === 2){
                        $model->whereBetween($key, $value);
                    }
                    else{
                        $param = json_decode($value);
                        if(json_last_error() == JSON_ERROR_NONE && is_object($param)){
                            $filterColumns = is_array($param) ? $param : [$param];

                            foreach($filterColumns as $filterColumn){
                                $value = $filterColumn->value;
                                $operator = empty($filterColumn->operator)  ? '=' : $filterColumn->operator;

                                $model->where($key, $operator, $value);
                            }
                        }
                        else{
                            $model->where($key, $value);
                        }
                    }
                }
            }

            return response()->json(['success' => true, 'data' => $model->get()], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function getRules(){
        return array_merge($this->basicValidate, [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
    }
}
