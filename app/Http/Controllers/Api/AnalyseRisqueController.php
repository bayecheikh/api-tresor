<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AnalyseRisque;
use App\Models\AnalyseGenre;
use App\Models\User;

class AnalyseRisqueController extends Controller
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
            $analyses = AnalyseRisque::paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $analyses = AnalyseRisque::where('libelle_secteur', 'like', '%'.$secteur.'%')->paginate(10);
        }

        
        
        $total = $analyses->total();

        return response()->json(["success" => true, "message" => "Liste des analyses risque", "data" =>$analyses,"total"=> $total]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function analyseMultipleSearch($term)
    {
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $analyses = AnalyseRisque::where('id', 'like', '%'.$term.'%')->orWhere('reference_projet', 'like', '%'.$term.'%')->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')->orWhere('region', 'like', '%'.$term.'%')->orWhere('departement', 'like', '%'.$term.'%')->orWhere('commune', 'like', '%'.$term.'%')->paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $analyses = AnalyseRisque::where('libelle_secteur', 'like', '%'.$secteur.'%')->where('id', 'like', '%'.$term.'%')->orWhere('reference_projet', 'like', '%'.$term.'%')->orWhere('nom_beneficiaire', 'like', '%'.$term.'%')->orWhere('prenom_beneficiaire', 'like', '%'.$term.'%')->orWhere('region', 'like', '%'.$term.'%')->orWhere('departement', 'like', '%'.$term.'%')->orWhere('commune', 'like', '%'.$term.'%')->paginate(10);
        }      
        
        $total = $analyses->total();
        return response()->json(["success" => true, "message" => "Liste des analyse risque", "data" => $analyses,"total"=> $total]); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeanalyse($id)
    {
        $analyse = AnalyseRisque::find($id);

        $message = '';

        if($analyse->status=='actif'){
            $message = 'Analyse desactivé';
            $analyse->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'Analyse activé';
            $analyse->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $analyse]);   
    }
    /**
     * Store a newly created resource in storagrolee.
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

        $analyse = AnalyseRisque::create($input);       

        return response()->json(["success" => true, "message" => "Analyse crée avec succès.", "data" => $analyse]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $analyse = AnalyseRisque::find($id);
        if (is_null($analyse))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Analyse introuvable "]);
        }
        return response()
            ->json(["success" => true, "message" => "Analyse trouvée avec succès.", "data" => $analyse]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, analyse $analyse)
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
            ->json(["success" => true, "message" => "Analyse modifiée avec succès.", "data" => $analyse]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AnalyseRisque $analyse)
    {
        $analyse->delete();
        return response()
            ->json(["success" => true, "message" => "ANalyse supprimée avec succès.", "data" => $analyse]);
    }
}
