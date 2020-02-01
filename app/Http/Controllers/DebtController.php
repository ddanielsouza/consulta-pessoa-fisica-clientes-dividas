<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Debt;

/**
 * Criei a class ControllerModel que contem algumas functions basica para REST API
 */
class DebtController extends ControllerModel
{
    protected $modelName = Debt::class;
    protected $basicValidate = [
        'client_id'=>'required|numeric',
        'description' => 'required|string|max:200',
        'startDate' => 'required|date',
        'datePayment' => 'date',
        'initialAmount' => 'required|numeric',
        'paymentAmount' => 'numeric',
        'isActive' => 'boolean',
    ];

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
