<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Sectoriel;
use App\Models\User;

class SectorielController extends Controller
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
            $sectoriels = Sectoriel::paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $sectoriels = Sectoriel::where('secteur', 'like', '%'.$secteur.'%')->paginate(10);
        }

        
        
        $total = $sectoriels->total();

        return response()->json(["success" => true, "message" => "Liste des sectoriels risque", "data" =>$sectoriels,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allSectoriel(Request $request)
    {
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $sectoriels = Sectoriel::where('status', 'like', '%FIN_PROCESS%')->orderBy('created_at', 'DESC')->get();  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $sectoriels = Sectoriel::where('status', 'like', '%FIN_PROCESS%')->where('secteur', 'like', '%'.$secteur.'%')->orderBy('created_at', 'DESC')->get();
        }

        return response()->json(["success" => true, "message" => "Liste des sectoriels risque", "data" =>$sectoriels]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sectorielMultipleSearch($term)
    {
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $sectoriels = Sectoriel::where('id', 'like', '%'.$term.'%')->orWhere('annee', 'like', '%'.$term.'%')->orWhere('trimestre', 'like', '%'.$term.'%')->orWhere('secteur', 'like', '%'.$term.'%')->paginate(10);  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $sectoriels = Sectoriel::where('id', 'like', '%'.$term.'%')->orWhere('secteur', 'like', '%'.$secteur.'%')->orWhere('annee', 'like', '%'.$term.'%')->orWhere('trimestre', 'like', '%'.$term.'%')->orWhere('secteur', 'like', '%'.$term.'%')->paginate(10);
        }

        $total = $sectoriels->total();
        return response()->json(["success" => true, "message" => "Liste des sectoriel risque", "data" => $sectoriels,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activesectoriel($id)
    {
        $sectoriel = Sectoriel::find($id);

        $message = '';

        if($sectoriel->status=='actif'){
            $message = 'sectoriel desactivé';
            $sectoriel->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'sectoriel activé';
            $sectoriel->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $sectoriel]);   
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
        ['annee' => 'required',
        ]);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $sectoriel = Sectoriel::create(
            ['state' => 'INITIER_SECTORIEL',
            'status' => 'brouillon',
            'annee' => $input['annee'],
            'trimestre' => $input['trimestre'],
            'secteur' => $input['secteur'],
            'composante' => $input['composante'],
            'questionnaire' => $input['questionnaire']
        ]);  

        return response()->json(["success" => true, "message" => "sectoriel crée avec succès.", "data" => $sectoriel]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sectoriel = Sectoriel::find($id);
        if (is_null($sectoriel))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "sectoriel introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "sectoriel trouvé avec succès.", "data" => $sectoriel]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sectoriel $sectoriel)
    {
        $input = $request->all();
        $validator = Validator::make($input, 
        ['annee' => 'required'
        ]);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        if(isset($input['annee']))
        $sectoriel->annee= $input['annee'];

        if(isset($input['trimestre']))
        $sectoriel->trimestre= $input['trimestre'];   
        
        /* if(isset($input['secteur']))
        $sectoriel->secteur= $input['secteur'];

        if(isset($input['composante']))
        $sectoriel->composante= $input['composante'];  */

        if(isset($input['questionnaire']))
        $sectoriel->questionnaire= $input['questionnaire'];

        $sectoriel->save();


        return response()
            ->json(["success" => true, "message" => "sectoriel modifiée avec succès.", "data" => $sectoriel]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sectoriel = Sectoriel::find($id);
        $sectoriel->delete();
        return response()
            ->json(["success" => true, "message" => "sectoriel supprimée avec succès.", "data" => $sectoriel]);
    }

    /////////////////////////////////////////   WORKFLOW / ///////////////////////////
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation_sectoriel(Request $request)
    {
        $input = $request->all();
        

        $sectoriel = Sectoriel::where('id',$input['id'])->first();

        if ($request->user()->hasRole('expert-sectoriel')){
            $sectoriel->state = 'FIN_PROCESS';
            $sectoriel->status = 'FIN_PROCESS';
        }
        $sectoriel->save();

        return response()->json(["success" => true, "message" => "Formulaire sectoriel validé", "data" =>$sectoriel]);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rejet_sectoriel(Request $request)
    {
        $input = $request->all();
        $motif_rejet = $input['motif_rejet'];
        

        $sectoriel = Sectoriel::where('id',$input['id'])->first();

        if ($request->user()->hasRole('agent-deps') || $request->user()->hasRole('super_admin')){          
            $sectoriel->state = 'FIN_PROCESS';
            $sectoriel->status = 'rejete';          
            $sectoriel->motif_rejet = $motif_rejet;          
        }
        
        $sectoriel->save();

        return response()->json(["success" => true, "message" => "sectoriel rejeté avec succés", "data" =>$sectoriel]);  
    }
}
