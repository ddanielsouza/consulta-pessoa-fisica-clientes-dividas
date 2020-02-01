<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends ControllerModel
{
    protected $modelName = Client::class;
    protected $basicValidate = [
        'dctRegistry'=>'required|regex:/\\d{11}/',
        'name' => 'required|string',
    ];
    protected $columnsEncrypted = ['dctRegistry' => 'hash_registry'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }
}
