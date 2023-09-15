<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Commune;

class CommuneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
        $communes = Commune::with('departement')->get();
        return response()->json(["success" => true, "message" => "Liste des communes", "data" => $communes]);

        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['nom_commune' => 'required', 'slug' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $commune = Commune::create($input);

        return response()->json(["success" => true, "message" => "Commune créé avec succès.", "data" => $commune]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commune = Commune::with('departement')->with('beneficiaires')->get()->find($id);
        if (is_null($commune))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Commune introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Commune  retrouvé avec succès.", "data" => $commune]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commune $commune)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['nom_commune' => 'required', 'slug' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $commune->nom_commune = $input['nom_commune'];
        $commune->slug = $input['slug'];
        $commune->latitude = $input['latitude'];
        $commune->longitude = $input['longitude'];
        $commune->svg = $input['svg'];
        $commune->save();
        return response()
            ->json(["success" => true, "message" => "Commune modifié avec succès.", "data" => $commune]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commune $commune)
    {
        $commune->delete();
        return response()
            ->json(["success" => true, "message" => "Commune supprimée avec succès.", "data" => $commune]);
    }
}
