<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\TypeMilitant;

class TypeMilitantController extends Controller
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
 
        $TypeMilitants = TypeMilitant::all();
        return response()->json(["success" => true, "message" => "Liste des TypeMilitants", "data" => $TypeMilitants]);

        
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
        $TypeMilitant = TypeMilitant::create($input);
        return response()->json(["success" => true, "message" => "Type Militant créé avec succès.", "data" => $TypeMilitant]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $TypeMilitant = TypeMilitant::find($id);
        if (is_null($TypeMilitant))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Type Militant introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Type Militant retrouvée avec succès.", "data" => $TypeMilitant]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeMilitant $TypeMilitant)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            return response() ->json($validator->errors());
        }
        //$TypeMilitant->name = $input['name'];
        $TypeMilitant->libelle= $input['libelle'];
        $TypeMilitant->save();

        return response()
            ->json(["success" => true, "message" => "Type Militant modifié avec succès.", "data" => $TypeMilitant]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeMilitant $TypeMilitant)
    {
        $TypeMilitant->delete();
        return response()
            ->json(["success" => true, "message" => "Type Militant supprimé avec succès.", "data" => $TypeMilitant]);
    }
}
