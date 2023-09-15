<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ResultatAttendu;

class ResultatAttenduController extends Controller
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

        $ResultatAttendus = ResultatAttendu::orderBy('created_at', 'DESC')->get();
        
        //$total = $ResultatAttendus->total();

        return response()->json(["success" => true, "message" => "Liste des ResultatAttendus risque", "data" =>$ResultatAttendus]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resultatAttenduMultipleSearch($term)
    {
        $ResultatAttendus = ResultatAttendu::where('id', 'like', '%'.$term.'%')->orWhere('annee', 'like', '%'.$term.'%')->orWhere('trimestre', 'like', '%'.$term.'%')->paginate(5);
        $total = $ResultatAttendus->total();
        return response()->json(["success" => true, "message" => "Liste des ResultatAttendu risque", "data" => $ResultatAttendus,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeResultatAttendu($id)
    {
        $ResultatAttendu = ResultatAttendu::find($id);

        $message = '';

        if($ResultatAttendu->status=='actif'){
            $message = 'ResultatAttendu desactivé';
            $ResultatAttendu->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'ResultatAttendu activé';
            $ResultatAttendu->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $ResultatAttendu]);   
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

        $ResultatAttendu = ResultatAttendu::create($input);       

        return response()->json(["success" => true, "message" => "ResultatAttendu crée avec succès.", "data" => $ResultatAttendu]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ResultatAttendu = ResultatAttendu::find($id);
        if (is_null($ResultatAttendu))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "ResultatAttendu introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "ResultatAttendu trouvé avec succès.", "data" => $ResultatAttendu]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResultatAttendu $ResultatAttendu)
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
        $ResultatAttendu->annee= $input['annee'];

        if(isset($input['trimestre']))
        $ResultatAttendu->trimestre= $input['trimestre'];      

        if(isset($input['questionnaire']))
        $ResultatAttendu->questionnaire= $input['questionnaire'];

        $ResultatAttendu->save();


        return response()
            ->json(["success" => true, "message" => "ResultatAttendu modifiée avec succès.", "data" => $ResultatAttendu]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ResultatAttendu = ResultatAttendu::find($id);
        $ResultatAttendu->delete();
        return response()
            ->json(["success" => true, "message" => "ResultatAttendu supprimée avec succès.", "data" => $ResultatAttendu]);
    }
}
