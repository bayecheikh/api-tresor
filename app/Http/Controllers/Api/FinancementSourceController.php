<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TypeZoneIntervention;
use App\Models\TypeSource;
use App\Models\SourceFinancement;
use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\FinancementSource;
use App\Models\Activite;
use App\Models\Bailleur;
use App\Models\Secteur;
use App\Models\SousSecteur;
use App\Models\User;
use App\Models\Fichier;

use App\Models\Annee;
use App\Models\Monnaie;
use App\Models\LigneFinancement;
use App\Models\ModeFinancement;
use App\Models\LigneModeFinancementSource;
use App\Models\Dimension;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Pilier;
use App\Models\Axe;

class FinancementSourceController extends Controller
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
        $FinancementSources = FinancementSource::with('sources')-> with('secteurs')      
        ->orderBy('created_at', 'DESC')->get();
        return response()->json(["success" => true, "message" => "Fiancement source list", "data" =>$FinancementSources]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function financementByType($term)
    {
        $FinancementSources = FinancementSource::where('type', 'like', '%'.$term.'%')->with('sources')-> with('secteurs')       
        ->orderBy('created_at', 'DESC')->get();
        return response()->json(["success" => true, "message" => "Financement source list", "data" =>$FinancementSources]);
        
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

        $validator = Validator::make($input, ['montant' => 'required']);
        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
            

            $montant = $input['montant'];
            $libelle_source = $input['libelle_source'];
            $libelle_secteur = $input['libelle_secteur'];
            $id_source = $input['id_source'];
            $id_secteur = $input['id_secteur'];
            $annee = $input['annee'];
            $trimestre = $input['trimestre'];
            $type = $input['type'];

            $FinancementSource = FinancementSource::create(
                ['montant' => $montant,
                'libelle_source' => $libelle_source,
                'libelle_secteur' => $libelle_secteur,
                'annee' => $annee,
                'trimestre' => $trimestre,
                'type' => $type
                ]
            );

            if($id_source!=null){               
                $sourceObj = Bailleur::where('id',$id_source)->first();
                $FinancementSource->sources()->attach($sourceObj);
            }

            if($id_secteur!=null){               
                $secteurObj = Secteur::where('id',$id_secteur)->first();
                $FinancementSource->secteurs()->attach($secteurObj);
            }

            return response()->json(["success" => true, "message" => "FinancementSource enregistré avec succès.", "data" => ""]);
            //return response()->json(["success" => true, "message" => "FinancementSource created successfully.", "data" => $input]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $FinancementSource = FinancementSource::with('sources')-> with('secteurs')
        ->get()
        ->find($id);
        if (is_null($FinancementSource))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "FinancementSource not found."]);
        }
        return response()
            ->json(["success" => true, "message" => "FinancementSource retrieved successfully.", "data" => $FinancementSource]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinancementSource $FinancementSource)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['montant' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        else{
        $FinancementSource->montant = $input['montant'];
        $FinancementSource->libelle_source = $input['libelle_source'];
        $FinancementSource->libelle_secteur = $input['libelle_secteur'];
        $FinancementSource->annee = $input['annee'];
        $FinancementSource->trimestre = $input['trimestre'];
        $FinancementSource->type = $input['type'];
        $FinancementSource->save();

        $id_source = $input['id_source'];
        $old_sources = $FinancementSource->sources();

        $id_secteur = $input['id_secteur'];
        $old_secteurs = $FinancementSource->secteurs();

        if($id_source!=null){               
            foreach($old_sources as $source){
                $sourceObj = Bailleur::where('id',$source)->first();
                $FinancementSource->sources()->detach($sourceObj);
            }
            $sourceObj = Bailleur::where('id',$id_source)->first();
            $FinancementSource->sources()->attach($sourceObj);
        }

        if($id_secteur!=null){               
            foreach($old_secteurs as $secteur){
                $secteurObj = Secteur::where('id',$secteur)->first();
                $FinancementSource->secteurs()->detach($secteurObj);
            }
            $secteurObj = Secteur::where('id',$id_secteur)->first();
            $FinancementSource->secteurs()->attach($secteurObj);
        }

        return response()
            ->json(["success" => true, "message" => "FinancementSource updated successfully.", "data" => $FinancementSource]);
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancementSource $FinancementSource)
    {
        $FinancementSource->delete();
        return response()
            ->json(["success" => true, "message" => "FinancementSource supprimé.", "data" => $FinancementSource]);
    }


    /////////////////////////////////////////   WORKFLOW / ///////////////////////////
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation_FinancementSource(Request $request)
    {
        $input = $request->all();
        

        $FinancementSource = FinancementSource::where('id',$input['id'])->first();

        if ($request->user()->hasRole('point_focal')){
            $FinancementSource->state = 'VALIDATION_ADMIN_FinancementSource';
            $FinancementSource->status = 'a_valider';
        }
        if ($request->user()->hasRole('admin_FinancementSource')){

            if($FinancementSource->source[0]->libelle_source=='EPS'){
                $FinancementSource->state = 'VALIDATION_DIRECTEUR_EPS';
                $FinancementSource->status = 'a_valider';
            }
            else{
                $FinancementSource->state = 'FIN_PROCESS';
                $FinancementSource->status = 'publie';
            }
        }
        if ($request->user()->hasRole('directeur_eps')){
            $FinancementSource->state = 'FIN_PROCESS';
            $FinancementSource->status = 'publie';
        }
        $FinancementSource->save();

        return response()->json(["success" => true, "message" => "FinancementSource validé", "data" =>$FinancementSource]);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rejet_FinancementSource(Request $request)
    {
        $input = $request->all();
        $motif_rejet = $input['motif_rejet'];
        

        $FinancementSource = FinancementSource::where('id',$input['id'])->first();

        if ($request->user()->hasRole('admin_FinancementSource')){          
            $FinancementSource->state = 'INITIER_FinancementSource';
            $FinancementSource->status = 'rejete';          
            $FinancementSource->motif_rejet = $motif_rejet;          
        }
        if ($request->user()->hasRole('directeur_eps')){
            $FinancementSource->state = 'VALIDATION_ADMIN_FinancementSource';
            $FinancementSource->status = 'rejete';
            $FinancementSource->motif_rejet = $motif_rejet; 
        }
        if ($request->user()->hasRole('admin_dprs')){
            $FinancementSource->state = 'VALIDATION_ADMIN_FinancementSource';
            $FinancementSource->status = 'rejete';
            $FinancementSource->motif_rejet = $motif_rejet; 
        }
        $FinancementSource->save();

        return response()->json(["success" => true, "message" => "FinancementSource rejeté avec succés", "data" =>$FinancementSource]);  
    }
}
