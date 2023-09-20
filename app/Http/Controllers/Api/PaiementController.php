<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Models\Paiement;


class PaiementController extends Controller
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
 
        $Paiements = Paiement::all();
        return response()->json(["success" => true, "message" => "Liste des Paiements", "data" => $Paiements]);

        
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
        $validator = Validator::make($input, ['libelle' => 'required','slug' => 'required']);
        if ($validator->fails())
        {
            
            return response()
            ->json($validator->errors());
        }
        $Paiement = Paiement::create($input);
        return response()->json(["success" => true, "message" => "Opérateur créé avec succès.", "data" => $Paiement]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Paiement = Paiement::find($id);
        if (is_null($Paiement))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Paiement introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Paiement retrouvé avec succès.", "data" => $Paiement]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paiement $Paiement)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            return response() ->json($validator->errors());
        }
        //$Paiement->name = $input['name'];
        $Paiement->libelle= $input['libelle'];
        $Paiement->save();

        return response()
            ->json(["success" => true, "message" => "Opérateur modifié avec succès.", "data" => $Paiement]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paiement $Paiement)
    {
        $Paiement->delete();
        return response()
            ->json(["success" => true, "message" => "Opérateur supprimé avec succès.", "data" => $Paiement]);
    }
}
