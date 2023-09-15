<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\Annuaire;
use App\Models\User;

class AnnuaireController extends Controller
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
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin') || $request->user()->hasRole('admin-annuaire')) {
            $Annuaires = Annuaire::all();
        }
        else{           
            $user_id = $request->user()->id;
            $Annuaires = Annuaire::where('user_id', $user_id);                      
        }
        return response()->json(["success" => true, "message" => "Annuaire List", "data" =>$Annuaires]);
        
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

        $validator = Validator::make($input, ['telephone' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
                       
            $Annuaire = Annuaire::create(
                [
                'prenom'=>$input['prenom'],
                'nom'=>$input['nom'],
                'telephone'=>$input['telephone'],
                'type_militant'=>$input['type_militant'],
                'region'=>$input['region'],
                'departement'=>$input['departement'],
                'commune'=>$input['commune'],
                'status'=>'actif',
                'user_id'=>$user_id]
            );

            return response()->json(["success" => true, "message" => "Contact enregistré avec succès."]);
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
        $Annuaire = Annuaire::get()
        ->find($id);
        if (is_null($Annuaire))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Annuaire not found."]);
        }
        return response()
            ->json(["success" => true, "message" => "Annuaire retrieved successfully.", "data" => $Annuaire]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Annuaire $Annuaire)
    {
        $input = $request->all();

        $user_id = $request->user()->id;

        $validator = Validator::make($input, ['telephone' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{
            $Annuaire->prenom = $input['prenom'];
            $Annuaire->nom = $input['nom'];
            $Annuaire->telephone = $input['telephone'];
            $Annuaire->type_militant = $input['type_militant'];
            $Annuaire->region = $input['region'];
            $Annuaire->departement = $input['departement'];
            $Annuaire->commune = $input['commune'];
            $Annuaire->save();

            

            return response()
                ->json(["success" => true, "message" => "Contact modifié avec succès.", "data" => $Annuaire]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Annuaire $Annuaire)
    {
        $Annuaire->delete();
        return response()
            ->json(["success" => true, "message" => "Contact supprimé.", "data" => $Annuaire]);
    }
}
