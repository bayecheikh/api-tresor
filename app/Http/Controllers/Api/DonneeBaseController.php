<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\DonneeBase;

class DonneeBaseController extends Controller
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

        $DonneeBases = DonneeBase::paginate(10);
        
        $total = $DonneeBases->total();

        return response()->json(["success" => true, "message" => "Liste des DonneeBases risque", "data" =>$DonneeBases,"total"=> $total]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allDonneeBase(Request $request)
    {
        if (!$request->user()->hasRole('expert-sectoriel')) {
            $DonneeBases = DonneeBase::orderBy('created_at', 'DESC')->get();  
        }
        else{
            $secteur = User::find($request->user()->id)->secteur[0]->libelle;
            $DonneeBases = DonneeBase::where('secteur', 'like', '%'.$secteur.'%')->orderBy('created_at', 'DESC')->get();
        }

        return response()->json(["success" => true, "message" => "Liste des données de base", "data" =>$DonneeBases]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function donneeBaseMultipleSearch($term)
    {
        $DonneeBases = DonneeBase::where('id', 'like', '%'.$term.'%')->orWhere('annee', 'like', '%'.$term.'%')->orWhere('trimestre', 'like', '%'.$term.'%')->paginate(5);
        $total = $DonneeBases->total();
        return response()->json(["success" => true, "message" => "Liste des DonneeBase risque", "data" => $DonneeBases,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeDonneeBase($id)
    {
        $DonneeBase = DonneeBase::find($id);

        $message = '';

        if($DonneeBase->status=='actif'){
            $message = 'DonneeBase desactivé';
            $DonneeBase->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'DonneeBase activé';
            $DonneeBase->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $DonneeBase]);   
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

        $DonneeBase = DonneeBase::create($input);       

        return response()->json(["success" => true, "message" => "DonneeBase crée avec succès.", "data" => $DonneeBase]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $DonneeBase = DonneeBase::find($id);
        if (is_null($DonneeBase))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "DonneeBase introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "DonneeBase trouvé avec succès.", "data" => $DonneeBase]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DonneeBase $DonneeBase)
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
        $DonneeBase->annee= $input['annee'];

        if(isset($input['trimestre']))
        $DonneeBase->trimestre= $input['trimestre'];      

        if(isset($input['questionnaire']))
        $DonneeBase->questionnaire= $input['questionnaire'];

        $DonneeBase->save();


        return response()
            ->json(["success" => true, "message" => "DonneeBase modifiée avec succès.", "data" => $DonneeBase]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $DonneeBase = DonneeBase::find($id);
        $DonneeBase->delete();
        return response()
            ->json(["success" => true, "message" => "DonneeBase supprimée avec succès.", "data" => $DonneeBase]);
    }
}
