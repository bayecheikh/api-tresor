<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\CibleFin;

class CibleFinController extends Controller
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

        $CibleFins = CibleFin::paginate(10);
        
        $total = $CibleFins->total();

        return response()->json(["success" => true, "message" => "Liste des CibleFins risque", "data" =>$CibleFins,"total"=> $total]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CibleFinMultipleSearch($term)
    {
        $CibleFins = CibleFin::where('id', 'like', '%'.$term.'%')->orWhere('annee', 'like', '%'.$term.'%')->orWhere('trimestre', 'like', '%'.$term.'%')->paginate(5);
        $total = $CibleFins->total();
        return response()->json(["success" => true, "message" => "Liste des CibleFin risque", "data" => $CibleFins,"total"=> $total]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeCibleFin($id)
    {
        $CibleFin = CibleFin::find($id);

        $message = '';

        if($CibleFin->status=='actif'){
            $message = 'CibleFin desactivé';
            $CibleFin->update([
                'status' => 'inactif'
            ]);
        }
        else{
            $message = 'CibleFin activé';
            $CibleFin->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $CibleFin]);   
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

        $CibleFin = CibleFin::create($input);       

        return response()->json(["success" => true, "message" => "CibleFin crée avec succès.", "data" => $CibleFin]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CibleFin = CibleFin::find($id);
        if (is_null($CibleFin))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "CibleFin introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "CibleFin trouvé avec succès.", "data" => $CibleFin]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CibleFin $CibleFin)
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
        $CibleFin->annee= $input['annee'];

        if(isset($input['trimestre']))
        $CibleFin->trimestre= $input['trimestre'];      

        if(isset($input['questionnaire']))
        $CibleFin->questionnaire= $input['questionnaire'];

        $CibleFin->save();


        return response()
            ->json(["success" => true, "message" => "CibleFin modifiée avec succès.", "data" => $CibleFin]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CibleFin = CibleFin::find($id);
        $CibleFin->delete();
        return response()
            ->json(["success" => true, "message" => "CibleFin supprimée avec succès.", "data" => $CibleFin]);
    }
}
