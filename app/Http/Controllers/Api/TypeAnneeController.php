<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\TypeAnnee;

class TypeAnneeController extends Controller
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
 
        $TypeAnnees = TypeAnnee::all();
        return response()->json(["success" => true, "message" => "Liste des TypeAnnees", "data" => $TypeAnnees]);

        
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
        $TypeAnnee = TypeAnnee::create($input);
        return response()->json(["success" => true, "message" => "TypeAnnee créée avec succès.", "data" => $TypeAnnee]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $TypeAnnee = TypeAnnee::find($id);
        if (is_null($TypeAnnee))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "TypeAnnee introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "TypeAnnee retrouvée avec succès.", "data" => $TypeAnnee]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeAnnee $TypeAnnee)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            return response() ->json($validator->errors());
        }
        //$TypeAnnee->name = $input['name'];
        $TypeAnnee->libelle= $input['libelle'];
        $TypeAnnee->save();

        return response()
            ->json(["success" => true, "message" => "TypeAnnee modifiée avec succès.", "data" => $TypeAnnee]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeAnnee $TypeAnnee)
    {
        $TypeAnnee->delete();
        return response()
            ->json(["success" => true, "message" => "TypeAnnee supprimée avec succès.", "data" => $TypeAnnee]);
    }
}
