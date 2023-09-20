<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operateur;


class OperateurController extends Controller
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
 
        $Operateurs = Operateur::all();
        return response()->json(["success" => true, "message" => "Liste des Operateurs", "data" => $Operateurs]);

        
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
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $Operateur = Operateur::create($input);
        return response()->json(["success" => true, "message" => "Opérateur créé avec succès.", "data" => $Operateur]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Operateur = Operateur::find($id);
        if (is_null($Operateur))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Operateur introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Operateur retrouvé avec succès.", "data" => $Operateur]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operateur $Operateur)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            return response() ->json($validator->errors());
        }
        //$Operateur->name = $input['name'];
        $Operateur->libelle= $input['libelle'];
        $Operateur->save();

        return response()
            ->json(["success" => true, "message" => "Opérateur modifié avec succès.", "data" => $Operateur]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operateur $Operateur)
    {
        $Operateur->delete();
        return response()
            ->json(["success" => true, "message" => "Opérateur supprimé avec succès.", "data" => $Operateur]);
    }
}
