<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerController extends Controller
{
    protected $customer;
    
    public function __construct()
    {
        $this->customer = new Customer();
    }

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid     = false;
        $countries  = $this->customer->getCountry();
        $codes     = $this->customer->getCodes();
        $data      = $this->customer->select('id', 'phone');

        if(!empty($request->country)) {
            $data->whereRaw("substr(phone, 2, 3) = '{$codes[$request->country]}'");
        }

        $customers = $this->attributes($data->get(), $countries);

        if(!empty($request->valid)) {
            $customers = $this->validFilter($customers, $request->valid);
        }
        $customers = $this->paginate($customers, 10);
        
        return view('index', compact('customers', 'countries', 'request'));
    }

    /**
     * Normalize the customers attributes.
     * 
     * @param object $customers
     * @param array $country
     *
     * @return object $customers
     */
    private function attributes($customers, $country)
    {
       
        foreach($customers as $k => $c) {
            $c->code    = substr($c->phone, 1, 3);
            $c->number  = substr($c->phone, 6);
            $c->country = $country[$c->code][0];
            $c->state   = $this->validNumber($country, $c);
        }
        return $customers;
    }

    /**
     * Valid Phone Number.
     * 
     * @param object $country
     * @param object $c
     *
     * @return boolean
     */
    private function validNumber($country, $c)
    {
        if(preg_match($country[$c->code][1], $c->phone)) {
            return true;
        }
        return false;
    }

    /**
     * Filter valid or invalid number.
     * 
     * @param object $customers
     * @param string $value
     *
     * @return object $customers
     */
    private function validFilter($customers, $value)
    {
        $value = $value == 'OK' ? true : false;
        foreach($customers as $k => $c) {
            if($c->state != $value){
                $customers->forget($k);
            }
        }
        return $customers;
    }

    /**
     * Generates the pagination of items in an array or collection.
    *
    * @param array|Collection  $items
    * @param int   $perPage
    * @param int  $page
    * @param array $options
    *
    * @return LengthAwarePaginator
    */
    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
