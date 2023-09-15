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
use App\Models\Beneficiaire;
use App\Models\Projet;

class ProjetController extends Controller
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
 
        $projets = Projet::with('beneficiaire')->with('commune')->with('departement')->with('region')->paginate(10);       
        $total = $projets->total();

        return response()->json(["success" => true, "message" => "Liste des projets", "data" => $projets,"total"=> $total]);       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projetMultipleSearch($term)
    {
        $projets = projet::where('id', 'like', '%'.$term.'%')
            ->orWhere('reference_projet', 'like', '%'.$term.'%')
            ->orWhere('titre_projet', 'like', '%'.$term.'%')
            ->with('beneficiaire')->paginate(10);
        $total = $projets->total();
        return response()->json(["success" => true, "message" => "Liste des projets", "data" => $projets,"total"=> $total]);   
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
        $validator = Validator::make($input, ['reference_projet'=> 'required',
        'titre_projet'=> 'required'
        ]);

        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $projet = Projet::create($input);

        $array_beneficiaires = $request->beneficiaire;
        $array_communes = $request->commune;
        $array_departements = $request->departement;
        $array_regions = $request->region;

        if(!empty($array_beneficiaires)){
            foreach($array_beneficiaires as $beneficiaire){
                $beneficiaireObj = Beneficiaire::where('id',$beneficiaire)->first();
                $projet-> beneficiaire()->attach( $beneficiaireObj);
            }
        }

        if(!empty($array_communes)){
            foreach($array_communes as $commune){
                $communeObj = Commune::where('id',$commune)->first();
                $projet->commune()->attach( $communeObj);
            }
        }

        if(!empty($array_departements)){
            foreach($array_departements as $departement){
                $departementObj = Departement::where('id',$departement)->first();
                $projet->departement()->attach( $departementObj);
            }
        }

        if(!empty($array_regions)){
            foreach($array_regions as $region){
                $regionObj = Region::where('id',$region)->first();
                $projet->region()->attach( $regionObj);
            }
        }


        return response()->json(["success" => true, "message" => "projet créé avec succès.", "data" => $input]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $projet = Projet::with('beneficiaire')->with('commune')->with('departement')->with('region')->get()->find($id);
        if (is_null($projet))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "projet introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "projet  retrouvé avec succès.", "data" => $projet]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Projet $projet)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['reference_projet'=> 'required',
        'titre_projet'=> 'required'
        ]);

        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $projet->reference_projet = $input['reference_projet'];
        $projet->titre_projet = $input['titre_projet'];
        $projet->save();

        $array_beneficiaires = $request->beneficiaire;
        $old_beneficiaires = $projet->beneficiaire();

        $array_communes = $request->commune;
        $old_communes = $projet->commune();

        $array_departements = $request->departement;
        $old_departements = $projet->departement();

        $array_regions = $request->region;
        $old_regions = $projet->region();
        
        foreach($old_beneficiaires as $beneficiaire){
            $beneficiaireObj = Beneficiaire::where('id',$beneficiaire)->first();
            $projet->beneficiaire()->detach($beneficiaireObj);
        }
        if(!empty($array_beneficiaires)){
            foreach($array_beneficiaires as $beneficiaire){
                $beneficiaireObj = Beneficiaire::where('id',$beneficiaire)->first();
                $projet->beneficiaire()->attach($beneficiaireObj);
            }
        }

        foreach($old_communes as $commune){
            $communeObj = Commune::where('id',$commune)->first();
            $projet->commune()->detach($communeObj);
        }
        if(!empty($array_communes)){
            foreach($array_communes as $commune){
                $communeObj = Commune::where('id',$commune)->first();
                $projet->commune()->attach($communeObj);
            }
        }

        foreach($old_departements as $departement){
            $departementObj = Departement::where('id',$departement)->first();
            $projet->departement()->detach($departementObj);
        }
        if(!empty($array_departements)){
            foreach($array_departements as $departement){
                $departementObj = Departement::where('id',$departement)->first();
                $projet->departement()->attach($departementObj);
            }
        }

        foreach($old_regions as $region){
            $regionObj = Region::where('id',$region)->first();
            $projet->region()->detach($regionObj);
        }
        if(!empty($array_regions)){
            foreach($array_regions as $region){
                $regionObj = Region::where('id',$region)->first();
                $projet->region()->attach($regionObj);
            }
        }

        return response()
            ->json(["success" => true, "message" => "projet modifié avec succès.", "data" => $projet]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Projet $projet)
    {
        $projet->delete();
        return response()
            ->json(["success" => true, "message" => "Projet supprimé avec succès.", "data" => $projet]);
    }
}
