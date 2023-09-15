<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Activite;
use App\Models\Investissement;

class ActiviteController extends Controller
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
 
        $Activites = Activite::get();
        return response()->json(["success" => true, "message" => "Liste des Activites", "data" => $Activites]);

        
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
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $Activite = Activite::create($input);

        return response()->json(["success" => true, "message" => "Activite créée avec succès.", "data" => $Activite]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Activite = Activite::with('investissements')->find($id);
        if (is_null($Activite))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Activite introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Activite retrouvée avec succès.", "data" => $Activite]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activite $Activite)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $Activite->libelle = $input['libelle'];

        $Activite->save();
        return response()
            ->json(["success" => true, "message" => "Activite modifiée avec succès.", "data" => $Activite]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activite $Activite)
    {
        $Activite->delete();
        return response()
            ->json(["success" => true, "message" => "Activite supprimée avec succès.", "data" => $Activite]);
    }
}
