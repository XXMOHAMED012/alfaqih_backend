<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function get() {
        $cars = Car::all();
        return response()->json(CarResource::collection($cars));
    }

    public function show(Car $car) {
        return response()->json(new CarResource($car));
    }
}
