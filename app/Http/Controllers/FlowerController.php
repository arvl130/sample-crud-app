<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flowers = Flower::orderBy('id', 'desc')->get();
        return Inertia::render("Flowers/Index", [
            "flowers" => $flowers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "name" => "required|string|max:255",
            "color" => "required|string|max:255",
        ]);

        Flower::create([
            "name" => $fields["name"],
            "color" => $fields["color"],
        ]);

        to_route('flowers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            "name" => "required|string|max:255",
            "color" => "required|string|max:255",
        ]);

        $flower = Flower::find($id);
        $flower->name = $fields["name"];
        $flower->color = $fields["color"];
        $flower->save();

        to_route('flowers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flower = Flower::find($id);
        $flower->delete();

        to_route('flowers.index');
    }
}
