<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\TypeAnnee;
use App\Models\Annee;
use App\Models\Investissement;

class AnneeController extends Controller
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
 
        $annees = Annee::with('type_annees')->get();
        return response()->json(["success" => true, "message" => "Liste des années", "data" => $annees]);

        
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
        $annee = Annee::create($input);

        $array_type_annees = $request->type_annees;

        if(!empty($array_type_annees)){
            foreach($array_type_annees as $type_annee){
                $type_anneeObj = TypeAnnee::where('id',$type_annee)->first();
                $annee->type_annees()->attach($type_anneeObj);
            }
        }

        return response()->json(["success" => true, "message" => "Année créée avec succès.", "data" => $annee]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $annee = Annee::with('type_annees')->find($id);
        if (is_null($annee))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Année introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Année retrouvée avec succès.", "data" => $annee]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Annee $annee)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $annee->libelle = $input['libelle'];

        $annee->save();

        $array_type_annees = $request->type_annees;
        $old_type_annees = $annee->type_annees();

        if(!empty($array_type_annees)){
            foreach($old_type_annees as $type_annee){
                $type_anneeObj = TypeAnnee::where('id',$type_annee)->first();
                $annee->type_annees()->detach($type_anneeObj);
            }
            foreach($array_type_annees as $type_annee){
                $type_anneeObj = TypeAnnee::where('id',$type_annee)->first();
                $annee->type_annees()->attach($type_anneeObj);
            }
        }
        return response()
            ->json(["success" => true, "message" => "Année modifiée avec succès.", "data" => $annee]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Annee $annee)
    {
        $annee->delete();
        return response()
            ->json(["success" => true, "message" => "Année supprimée avec succès.", "data" => $annee]);
    }
}
