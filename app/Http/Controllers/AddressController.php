<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Address;

/**
 * Criei a class ControllerModel que contem algumas functions basica para REST API
 */
class AddressController extends ControllerModel
{
    protected $modelName = Address::class;
    protected $basicValidate = [
        'client_id'=>'required|numeric',
        'cod_ibge' => 'required|numeric',
        'dctZipCode' => 'required|regex:/\\d{8}/im',
        'dctStreetAddress' => 'required|string',
        'dctComplement' => 'required|string',
        'dctNeighborhood' => 'required|string',
    ];
    protected $columnsEncrypted = ['dctZipCode' => 'hash_zip_code'];
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
