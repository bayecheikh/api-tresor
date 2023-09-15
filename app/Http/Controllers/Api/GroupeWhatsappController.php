<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\GroupeWhatsapp;
use App\Models\User;

class GroupeWhatsappController extends Controller
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
            $GroupeWhatsapps = GroupeWhatsapp::all();
        }
        else{           
            $user_id = $request->user()->id;
            $GroupeWhatsapps = GroupeWhatsapp::where('user_id', $user_id);                      
        }
        return response()->json(["success" => true, "message" => "GroupeWhatsapp List", "data" =>$GroupeWhatsapps]);
        
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

        $validator = Validator::make($input, ['nom_groupe' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
                       
            $GroupeWhatsapp = GroupeWhatsapp::create(
                [
                'nom_groupe'=>$input['nom_groupe'],
                'nombre_membre'=>$input['nombre_membre'],
                'prenom_admin'=>$input['prenom_admin'],
                'nom_admin'=>$input['nom_admin'],
                'telephone_admin'=>$input['telephone_admin'],
                'email_admin'=>$input['email_admin'],
                'status'=>'actif',
                'user_id'=>$user_id]
            );

            return response()->json(["success" => true, "message" => "Groupe Whatsapp enregistré avec succès."]);
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
        $GroupeWhatsapp = GroupeWhatsapp::get()
        ->find($id);
        if (is_null($GroupeWhatsapp))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Groupe Whatsapp not found."]);
        }
        return response()
            ->json(["success" => true, "message" => "Groupe Whatsapp retrieved successfully.", "data" => $GroupeWhatsapp]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupeWhatsapp $GroupeWhatsapp)
    {
        $input = $request->all();

        $user_id = $request->user()->id;

        $validator = Validator::make($input, ['nom_groupe' => 'required']);

        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{
            $GroupeWhatsapp->prenom = $input['prenom'];
            $GroupeWhatsapp->nom = $input['nom'];
            $GroupeWhatsapp->telephone = $input['telephone'];
            $GroupeWhatsapp->type_militant = $input['type_militant'];
            $GroupeWhatsapp->region = $input['region'];
            $GroupeWhatsapp->departement = $input['departement'];
            $GroupeWhatsapp->commune = $input['commune'];
            $GroupeWhatsapp->save();

            

            return response()
                ->json(["success" => true, "message" => "GroupeWhatsapp updated successfully.", "data" => $GroupeWhatsapp]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupeWhatsapp $GroupeWhatsapp)
    {
        $GroupeWhatsapp->delete();
        return response()
            ->json(["success" => true, "message" => "GroupeWhatsapp supprimé.", "data" => $GroupeWhatsapp]);
    }
}
