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

use App\Models\FinancementActivite;
use App\Models\Activite;
use App\Models\User;
use App\Models\Fichier;

use App\Models\Annee;
use App\Models\Monnaie;
use App\Models\LigneFinancement;
use App\Models\ModeFinancement;
use App\Models\LigneModeFinancementActivite;
use App\Models\Dimension;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Pilier;
use App\Models\Axe;

class FinancementActiviteController extends Controller
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
        $FinancementActivites = FinancementActivite::with('activites')       
        ->orderBy('created_at', 'DESC')->get();
        return response()->json(["success" => true, "message" => "Fiancement activités list", "data" =>$FinancementActivites]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function financementByType($term)
    {
        $FinancementActivites = FinancementActivite::where('type', 'like', '%'.$term.'%')->with('activites')       
        ->orderBy('created_at', 'DESC')->get();
        return response()->json(["success" => true, "message" => "Fiancement activités list", "data" =>$FinancementActivites]);
        
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
            $libelle_activite = $input['libelle_activite'];
            $annee = $input['annee'];
            $trimestre = $input['trimestre'];
            $type = $input['type'];
            $id_activite = $input['id_activite'];

            $FinancementActivite = FinancementActivite::create(
                ['montant' => $montant,
                'libelle_activite' => $libelle_activite,
                'annee' => $annee,
                'trimestre' => $trimestre,
                'type' => $type
                
                ]
            );

            if($id_activite!=null){               
                $activiteObj = Activite::where('id',$id_activite)->first();
                $FinancementActivite->activites()->attach($activiteObj);
            }

            return response()->json(["success" => true, "message" => "FinancementActivite enregistré avec succès.", "data" => ""]);
            //return response()->json(["success" => true, "message" => "FinancementActivite created successfully.", "data" => $input]);
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
        $FinancementActivite = FinancementActivite::with('activites')
        ->get()
        ->find($id);
        if (is_null($FinancementActivite))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "FinancementActivite not found."]);
        }
        return response()
            ->json(["success" => true, "message" => "FinancementActivite retrieved successfully.", "data" => $FinancementActivite]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinancementActivite $FinancementActivite)
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
        $FinancementActivite->montant = $input['montant'];
        $FinancementActivite->libelle_activite = $input['libelle_activite'];
        $FinancementActivite->annee = $input['annee'];
        $FinancementActivite->trimestre = $input['trimestre'];
        $FinancementActivite->type = $input['type'];
        $FinancementActivite->save();

        $id_activite = $input['id_activite'];
        $old_activites = $FinancementActivite->activites();

        if($id_activite!=null){               
            foreach($old_activites as $activite){
                $activiteObj = Activite::where('id',$activite)->first();
                $FinancementActivite->activites()->detach($activiteObj);
            }
            $activiteObj = Activite::where('id',$id_activite)->first();
            $FinancementActivite->activites()->attach($activiteObj);
        }

        return response()
            ->json(["success" => true, "message" => "FinancementActivite updated successfully.", "data" => $FinancementActivite]);
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancementActivite $FinancementActivite)
    {
        $FinancementActivite->delete();
        return response()
            ->json(["success" => true, "message" => "FinancementActivite supprimé.", "data" => $FinancementActivite]);
    }


    /////////////////////////////////////////   WORKFLOW / ///////////////////////////
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation_FinancementActivite(Request $request)
    {
        $input = $request->all();
        

        $FinancementActivite = FinancementActivite::where('id',$input['id'])->first();

        if ($request->user()->hasRole('point_focal')){
            $FinancementActivite->state = 'VALIDATION_ADMIN_FinancementActivite';
            $FinancementActivite->status = 'a_valider';
        }
        if ($request->user()->hasRole('admin_FinancementActivite')){

            if($FinancementActivite->source[0]->libelle_source=='EPS'){
                $FinancementActivite->state = 'VALIDATION_DIRECTEUR_EPS';
                $FinancementActivite->status = 'a_valider';
            }
            else{
                $FinancementActivite->state = 'FIN_PROCESS';
                $FinancementActivite->status = 'publie';
            }
        }
        if ($request->user()->hasRole('directeur_eps')){
            $FinancementActivite->state = 'FIN_PROCESS';
            $FinancementActivite->status = 'publie';
        }
        $FinancementActivite->save();

        return response()->json(["success" => true, "message" => "FinancementActivite validé", "data" =>$FinancementActivite]);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rejet_FinancementActivite(Request $request)
    {
        $input = $request->all();
        $motif_rejet = $input['motif_rejet'];
        

        $FinancementActivite = FinancementActivite::where('id',$input['id'])->first();

        if ($request->user()->hasRole('admin_FinancementActivite')){          
            $FinancementActivite->state = 'INITIER_FinancementActivite';
            $FinancementActivite->status = 'rejete';          
            $FinancementActivite->motif_rejet = $motif_rejet;          
        }
        if ($request->user()->hasRole('directeur_eps')){
            $FinancementActivite->state = 'VALIDATION_ADMIN_FinancementActivite';
            $FinancementActivite->status = 'rejete';
            $FinancementActivite->motif_rejet = $motif_rejet; 
        }
        if ($request->user()->hasRole('admin_dprs')){
            $FinancementActivite->state = 'VALIDATION_ADMIN_FinancementActivite';
            $FinancementActivite->status = 'rejete';
            $FinancementActivite->motif_rejet = $motif_rejet; 
        }
        $FinancementActivite->save();

        return response()->json(["success" => true, "message" => "FinancementActivite rejeté avec succés", "data" =>$FinancementActivite]);  
    }
}
