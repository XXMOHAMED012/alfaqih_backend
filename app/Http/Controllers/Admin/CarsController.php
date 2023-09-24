<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarsController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return response()->json(CarResource::collection($cars));
    }

    public function show($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }

        return response()->json($car);
    }

    public function store(Request $request)
    {
        $car = new Car();
        $car->name = $request->input('name');
        $car->price = $request->input('price');
        $car->status = $request->input('status');
        $car->type = $request->input('type');

        // Handling single image
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('car_images', 'public');
            $car->img = $imgPath;
        }

        // Handling multiple images
        if ($request->hasFile('imgs')) {
            $imgPaths = [];
            foreach ($request->file('imgs') as $img) {
                $imgPath = $img->store('car_images', 'public');
                $imgPaths[] = $imgPath;
            }
            $car->imgs = implode("|", $imgPaths);
        }

        $car->specs = $request->input('specs');
        $car->des = $request->input('des');
        $car->save();

        return response()->json(new CarResource($car), 201);
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }
        
        // Update only if the field is present in the request
        if ($request->has('name')) {
            $car->name = $request->input('name');
        }

        if ($request->has('price')) {
            $car->price = $request->input('price');
        }

        if ($request->has('status')) {
            $car->status = $request->input('status');
        }

        if ($request->has('type')) {
            $car->type = $request->input('type');
        }

        // Handling single image update
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('car_images', 'public');
            $car->img = $imgPath;
        }

        // Handling multiple images update
        if ($request->hasFile('imgs')) {
            $imgPaths = [];
            foreach ($request->file('imgs') as $img) {
                $imgPath = $img->store('car_images', 'public');
                $imgPaths[] = $imgPath;
            }
            $car->imgs = implode("|", $imgPaths);
        }

        if ($request->has('specs')) {
            $car->specs = $request->input('specs');
        }

        if ($request->has('des')) {
            $car->des = $request->input('des');
        }

        $car->save();

        return response()->json(new CarResource($car));
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }

        $car->delete();

        return response()->json(['message' => 'Car deleted'], 204);
    }
}
