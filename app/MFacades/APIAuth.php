<?php
namespace App\MFacades;
use Illuminate\Support\Facades\Facade;

class APIAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Helpers\APIAuth'; 
    }
}
