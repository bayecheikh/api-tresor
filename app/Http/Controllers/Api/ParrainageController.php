<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\Parrainage;
use App\Models\User;

class ParrainageController extends Controller
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
        if ($request->user()->hasRole('super_admin')) {
            $Parrainages = Parrainage::all();
        }
        else{           
            $user_id = $request->user()->id;
            $Parrainages = Parrainage::where('user_id', $user_id);                      
        }
        return response()->json(["success" => true, "message" => "Parrainage List", "data" =>$Parrainages]);
        
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

        $user_id = $request->user()->id;

        $validator = Validator::make($input, ['numero_cedeao' => 'required','numero_cin' => 'required','numero_electeur' => 'required','region' => 'required','departement' => 'required','commune' => 'required','prenom' => 'required','nom' => 'required','date_naissance' => 'required','lieu_naissance' => 'required','sexe' => 'required','taille' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
                       
            $Parrainage = Parrainage::create(
                ['numero_cedeao'=>$input['numero_cedeao'],
                'prenom'=>$input['prenom'],
                'nom'=>$input['nom'],
                'date_naissance'=>$input['date_naissance'],
                'lieu_naissance'=>$input['lieu_naissance'],
                'taille'=>$input['taille'],
                'sexe'=>$input['sexe'],
                'numero_electeur'=>$input['numero_electeur'],
                'centre_vote'=>$input['centre_vote'],
                'bureau_vote'=>$input['bureau_vote'],
                'numero_cin'=>$input['numero_cin'],
                'telephone'=>$input['telephone'],
                'prenom_responsable'=>$input['prenom_responsable'],
                'nom_responsable'=>$input['nom_responsable'],
                'telephone_responsable'=>$input['telephone_responsable'],
                'region'=>$input['region'],
                'departement'=>$input['departement'],
                'commune'=>$input['commune'],
                'status'=>'actif',
                'user_id'=>$user_id]
            );

            return response()->json(["success" => true, "message" => "Parrainage enregistré avec succès."]);
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
        $Parrainage = Parrainage::get()
        ->find($id);
        if (is_null($Parrainage))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Parrainage not found."]);
        }
        return response()
            ->json(["success" => true, "message" => "Parrainage retrieved successfully.", "data" => $Parrainage]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parrainage $Parrainage)
    {
        $input = $request->all();

        $user_id = $request->user()->id;

        $validator = Validator::make($input, ['numero_cedeao' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{

            $Parrainage->numero_cedeao = $input['numero_cedeao'];
            $Parrainage->prenom = $input['prenom'];
            $Parrainage->nom = $input['nom'];
            $Parrainage->date_naissance = $input['date_naissance'];
            $Parrainage->lieu_naissance = $input['lieu_naissance'];
            $Parrainage->taille = $input['taille'];
            $Parrainage->sexe = $input['sexe'];
            $Parrainage->numero_electeur = $input['numero_electeur'];
            $Parrainage->centre_vote = $input['centre_vote'];
            $Parrainage->bureau_vote = $input['bureau_vote'];
            $Parrainage->numero_cin = $input['numero_cin'];
            $Parrainage->telephone = $input['telephone'];
            $Parrainage->prenom_responsable = $input['prenom_responsable'];
            $Parrainage->nom_responsable = $input['nom_responsable'];
            $Parrainage->telephone_responsable = $input['telephone_responsable'];
            $Parrainage->region = $input['region'];
            $Parrainage->departement = $input['departement'];
            $Parrainage->commune = $input['commune'];
            

            $Parrainage->save();

            

            return response()
                ->json(["success" => true, "message" => "Parrainage updated successfully.", "data" => $Parrainage]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parrainage $Parrainage)
    {
        $Parrainage->delete();
        return response()
            ->json(["success" => true, "message" => "Parrainage supprimé.", "data" => $Parrainage]);
    }
}
