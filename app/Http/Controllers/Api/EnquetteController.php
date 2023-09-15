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
use App\Models\Enquette;
use App\Models\User;

class EnquetteController extends Controller
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
    public function index(Request $request)
    {
 
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $analyses = Enquette::paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $analyses = Enquette::where('libelle_secteur', 'like', '%'.$secteur.'%')->paginate(10);
        } 
        
        $total = $analyses->total();

        return response()->json(["success" => true, "message" => "Liste des enquettes", "data" => $analyses,"total"=> $total]);       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enquetteMultipleSearch($term)
    {
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $analyses = Enquette::where('id', 'like', '%'.$term.'%')->orWhere('reference_projet', 'like', '%'.$term.'%')->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')->orWhere('region', 'like', '%'.$term.'%')->orWhere('departement', 'like', '%'.$term.'%')->orWhere('commune', 'like', '%'.$term.'%')->paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $analyses = Enquette::where('libelle_secteur', 'like', '%'.$secteur.'%')->where('id', 'like', '%'.$term.'%')->orWhere('reference_projet', 'like', '%'.$term.'%')->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')->orWhere('region', 'like', '%'.$term.'%')->orWhere('departement', 'like', '%'.$term.'%')->orWhere('commune', 'like', '%'.$term.'%')->paginate(10);
        }      
        
        $total = $analyses->total();
        return response()->json(["success" => true, "message" => "Liste des enquettes", "data" => $analyses,"total"=> $total]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeenquette($id)
    {
        $analyse = Enquette::find($id);

        $message = '';

        if($analyse->status=='actif'){
            $message = 'Enquete desactivé';
            $analyse->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'Enquete activé';
            $analyse->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $analyse]);   
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
        $validator = Validator::make($input, 
        ['titre_projet' => 'required',
        'prenom_beneficiaire' => 'required', 
        'nom_beneficiaire' => 'required'
        ]);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $analyse = Enquette::create($input);       

        
        return response()->json(["success" => true, "message" => "Enquette créé avec succès.", "data" => $input]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $enquette = Enquette::with('beneficiaire')->with('projet')->with('commune')->with('departement')->with('region')->get()->find($id);
        if (is_null($enquette))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "enquette introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "enquette  retrouvé avec succès.", "data" => $projet]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enquette $enquette)
    {
        $input = $request->all();
        $validator = Validator::make($input, 
        ['titre_projet' => 'required',
        'prenom_beneficiaire' => 'required', 
        'nom_beneficiaire' => 'required'
        ]);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        if(isset($input['reference_projet']))
        $analyse->reference_projet= $input['reference_projet'];

        if(isset($input['date_enquette']))
        $analyse->date_enquette= $input['date_enquette'];

        if(isset($input['titre_projet']))
        $analyse->titre_projet= $input['titre_projet'];

        if(isset($input['prenom_beneficiaire']))
        $analyse->prenom_beneficiaire= $input['prenom_beneficiaire'];

        if(isset($input['nom_beneficiaire']))
        $analyse->nom_beneficiaire= $input['nom_beneficiaire'];

        if(isset($input['telephone_beneficiaire']))
        $analyse->telephone_beneficiaire= $input['telephone_beneficiaire'];

        if(isset($input['cni_beneficiaire']))
        $analyse->cni_beneficiaire= $input['cni_beneficiaire'];

        if(isset($input['adresse_beneficiaire']))
        $analyse->adresse_beneficiaire= $input['adresse_beneficiaire'];

        if(isset($input['region']))
        $analyse->region= $input['region'];

        if(isset($input['departement']))
        $analyse->departement= $input['departement'];

        if(isset($input['commune']))
        $analyse->commune= $input['commune'];

        if(isset($input['section']))
        $analyse->commune= $input['section'];

        if(isset($input['activites']))
        $analyse->commune= $input['activites'];

        if(isset($input['contraintes']))
        $analyse->commune= $input['contraintes'];

        if(isset($input['geolocalisation']))
        $analyse->commune= $input['geolocalisation'];

        if(isset($input['libelle_secteur']))
        $analyse->libelle_secteur= $input['libelle_secteur'];

        if(isset($input['id_secteur']))
        $analyse->id_secteur= $input['id_secteur'];

        

        if(isset($input['questionnaire']))
        $analyse->questionnaire= $input['questionnaire'];

        if(isset($input['evaluation_expert']))
        $analyse->evaluation_expert= $input['evaluation_expert'];

        $analyse->save();


        return response()
            ->json(["success" => true, "message" => "Enquette modifiée avec succès.", "data" => $analyse]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enquette $enquette)
    {
        $enquette->delete();
        return response()
            ->json(["success" => true, "message" => "Enquette supprimée avec succès.", "data" => $enquette]);
    }
}
