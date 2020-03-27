<?php

namespace App\Http\Controllers;

use App\Services\FourSquareApiServices;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FourSquareController extends Controller
{
	private $fourSquare;

	public function __construct(FourSquareApiServices $fourSquare)
	{
		$this->fourSquare = $fourSquare;
	}
    public function index()
    {
    	//Get Foursquare Categories
        $categories = $this->fourSquare->getCategories(config('enum.FOURSQUARE_PARAMETER.CATEGORY'));
        return view('foursquare.list', compact('categories'));
    }

    public function search($search)
    {
		//1.Parameter City=Valletta, 2.Parameter=Category Search
        $parameter['near'] = 'Valletta';
        $parameter['query'] = $search;

        $categories = $this->fourSquare->getCategories(config('enum.FOURSQUARE_PARAMETER.CATEGORY'));
        $data = $this->fourSquare->getLocations(config('enum.FOURSQUARE_PARAMETER.SEARCH'), $parameter);

        return view('foursquare.list', compact('categories', 'data'));
    }
}
