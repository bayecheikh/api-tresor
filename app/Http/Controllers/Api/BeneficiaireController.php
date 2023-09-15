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

class BeneficiaireController extends Controller
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
 
        $beneficiaires = Beneficiaire::with('region')->with('departement')->with('commune')->with('projets')->paginate(10);       
        $total = $beneficiaires->total();

        return response()->json(["success" => true, "message" => "Liste des beneficiaires", "data" => $beneficiaires,"total"=> $total]);       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function beneficiaireMultipleSearch($term)
    {
        $beneficiaires = Beneficiaire::where('id', 'like', '%'.$term.'%')
            ->orWhere('numero_cin', 'like', '%'.$term.'%')
            ->orWhere('telephone_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('adresse_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')
            ->with('region')->with('departement')->with('commune')->with('projets')->paginate(10);
        $total = $beneficiaires->total();
        return response()->json(["success" => true, "message" => "Liste des beneficiaires", "data" => $beneficiaires,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function beneficiaireByTerm($term)
    {
        $beneficiaires = Beneficiaire::where('id', 'like', '%'.$term.'%')
            ->orWhere('numero_cin', 'like', '%'.$term.'%')
            ->orWhere('telephone_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('adresse_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')
            ->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')->get();
        return response()->json(["success" => true, "message" => "Liste des beneficiaires", "data" => $beneficiaires]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function beneficiaireByCommune($term)
    {

        $beneficiaires = Commune::with('region')->with('departement')->with('commune')->with('projets')
        ->whereHas('commune', function($q) use ($term){
            $q->where('id', $term);
        })->orderBy('nom_beneficiaire', 'ASC');
        return response()->json(["success" => true, "message" => "Liste des beneficiaires", "data" => $beneficiaires]);   
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
        $validator = Validator::make($input, ['nom_beneficiaire'=> 'required',
        'prenom_beneficiaire'=> 'required',
        'numero_cin'=> 'required',
        'adresse_beneficiaire'=> 'required',
        'telephone_beneficiaire'=> 'required']);

        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $beneficiaire = Beneficiaire::create($input);

        $array_regions = $request->region;
        $array_departements = $request->departement;
        $array_communes = $request->commune;

        if(!empty($array_regions)){
            foreach($array_regions as $region){
                $regionObj = Region::where('id',$region)->first();
                $beneficiaire->region()->attach($regionObj);
            }
        }
        if(!empty($array_departements)){
            foreach($array_departements as $departement){
                $departementObj = Departement::where('id',$departement)->first();
                $beneficiaire->departement()->attach($departementObj);
            }
        }
        if(!empty($array_communes)){
            foreach($array_communes as $commune){
                $communeObj = Commune::where('id',$commune)->first();
                $beneficiaire->commune()->attach($communeObj);
            }
        }

        return response()->json(["success" => true, "message" => "Beneficiaire créé avec succès.", "data" => $input]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $beneficiaire = Beneficiaire::with('region')->with('departement')->with('commune')->with('projets')->get()->find($id);
        if (is_null($beneficiaire))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Beneficiaire introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Beneficiaire  retrouvé avec succès.", "data" => $beneficiaire]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, beneficiaire $beneficiaire)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['nom_beneficiaire'=> 'required',
        'prenom_beneficiaire'=> 'required',
        'numero_cin'=> 'required',
        'adresse_beneficiaire'=> 'required',
        'telephone_beneficiaire'=> 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $beneficiaire->nom_beneficiaire = $input['nom_beneficiaire'];
        $beneficiaire->prenom_beneficiaire = $input['prenom_beneficiaire'];
        $beneficiaire->numero_cin = $input['numero_cin'];
        $beneficiaire->adresse_beneficiaire = $input['adresse_beneficiaire'];
        $beneficiaire->telephone_beneficiaire = $input['telephone_beneficiaire'];
        $beneficiaire->save();

        $array_regions = $request->region;
        $old_regions = $beneficiaire->region();

        $array_departements = $request->departement;
        $old_departements = $beneficiaire->departement();

        $array_communes = $request->commune;
        $old_communes = $beneficiaire->commune();
        
        foreach($old_regions as $region){
            $regionObj = Region::where('id',$region)->first();
            $beneficiaire->region()->detach($regionObj);
        }
        if(!empty($array_regions)){
            foreach($array_regions as $region){
                $regionObj = Region::where('id',$region)->first();
                $beneficiaire->region()->attach($regionObj);
            }
        }

        foreach($old_departements as $departement){
            $departementObj = Departement::where('id',$departement)->first();
            $beneficiaire->departement()->detach($departementObj);
        }
        if(!empty($array_departements)){
            foreach($array_departements as $departement){
                $departementObj = Departement::where('id',$departement)->first();
                $beneficiaire->departement()->attach($departementObj);
            }
        }

        foreach($old_communes as $commune){
            $communeObj = Commune::where('id',$commune)->first();
            $beneficiaire->commune()->detach($communeObj);
        }
        if(!empty($array_communes)){
            foreach($array_communes as $commune){
                $communeObj = Commune::where('id',$commune)->first();
                $beneficiaire->commune()->attach($communeObj);
            }
        }

        return response()
            ->json(["success" => true, "message" => "Beneficiaire modifié avec succès.", "data" => $beneficiaire]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beneficiaire $beneficiaire)
    {
        $beneficiaire->delete();
        return response()
            ->json(["success" => true, "message" => "Bénéficiaire supprimé avec succès.", "data" => $beneficiaire]);
    }
}
