<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Permissions\HasPermissionsTrait;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Commune;
use App\Models\Beneficiaire;
use App\Models\Operateur;
use App\Models\Paiement;
use App\Models\Transaction;

class TransactionController extends Controller
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
        if ($request->user()->hasPermission('all-transactions')) {
            $Transactions = Transaction::Where('status','like', '%'.$request->status.'%')->with('beneficiaire')->with('operateur')->with('paiement')->paginate(20);
        }
        else{           
            $user_id = $request->user()->id;
            $Transactions = Transaction::where('user_id', $user_id)->Where('status','like', '%'.$request->status.'%')->with('beneficiaire')->with('operateur')->with('paiement')->paginate(20);                      
        }      
        $total = $Transactions->total();

        return response()->json(["success" => true, "message" => "Liste des Transactions", "data" => $Transactions,"total"=> $total]);       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function TransactionMultipleSearch($term)
    {
        $Transactions = Transaction::where('id', 'like', '%'.$term.'%')
            ->orWhere('reference_transaction', 'like', '%'.$term.'%')
            ->orWhere('prenom_beneficiaire','like', '%'.$term.'%')
            ->orWhere('nom_beneficiaire','like', '%'.$term.'%')
            ->orWhere('cni_beneficiaire','like', '%'.$term.'%')
            ->orWhere('telephone_beneficiaire','like', '%'.$term.'%')
            ->orWhere('libelle_paiement','like', '%'.$term.'%')
            ->orWhere('slug_paiement','like', '%'.$term.'%')
            ->orWhere('libelle_operateur','like', '%'.$term.'%')
            ->orWhere('slug_operateur','like', '%'.$term.'%')
            ->orWhere('status','like', '%'.$term.'%')
            ->with('beneficiaire')->with('operateur')->paginate(20);
        $total = $Transactions->total();
        return response()->json(["success" => true, "message" => "Liste des Transactions", "data" => $Transactions,"total"=> $total]);   
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $ref = "#".bin2hex(openssl_random_pseudo_bytes(6));
        $input['reference_transaction'] = $ref;

        $validator = Validator::make($input, [
            'reference_transaction'=> 'required',
            'prenom_beneficiaire'=> 'required',
            'nom_beneficiaire'=> 'required',
            'telephone_beneficiaire'=> 'required',
            'libelle_operateur'=> 'required',
            'slug_operateur'=> 'required',
            'montant'=> 'required',
        ]);

        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $Transaction = Transaction::create($input);

        if(isset($input['id_beneficiaire'])){          
            $beneficiaireObj = Beneficiaire::where('id',$input['id_beneficiaire'])->first();
            $Transaction-> beneficiaire()->attach( $beneficiaireObj);           
        }else{
            $beneficiaire = Beneficiaire::create(
                [
                    'numero_cin' => $input['cni_beneficiaire'],
                    'nom_beneficiaire' => $input['nom_beneficiaire'],
                    'prenom_beneficiaire' => $input['prenom_beneficiaire'],
                    'adresse_beneficiaire' => $input['adresse_beneficiaire'],
                    'telephone_beneficiaire' => $input['telephone_beneficiaire']
                ]
            );
            $beneficiaireObj = Beneficiaire::where('id',$beneficiaire->id)->first();
            $Transaction-> beneficiaire()->attach( $beneficiaireObj); 
        }

        if(isset($input['id_operateur'])){          
            $operateurObj = Operateur::where('id',$input['id_operateur'])->first();
            $Transaction-> operateur()->attach( $operateurObj);           
        }

        if(isset($input['id_paiement'])){          
            $paiementObj = Operateur::where('id',$input['id_paiement'])->first();
            $Transaction-> paiement()->attach( $paiementObj);           
        }


        return response()->json(["success" => true, "message" => "Transaction créé avec succès.", "data" => $input]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Transaction = Transaction::with('beneficiaire')->with('operateur')->with('paiement')->get()->find($id);
        if (is_null($Transaction))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Transaction introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Transaction  retrouvé avec succès.", "data" => $Transaction]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $Transaction)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['reference_transaction'=> 'required',
            'prenom_beneficiaire'=> 'required',
            'nom_beneficiaire'=> 'required',
            'telephone_beneficiaire'=> 'required',
            'libelle_operateur'=> 'required',
            'slug_operateur'=> 'required',
            'montant'=> 'required',
        ]);

        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
  
        $Transaction->id_beneficiaire = $input['id_beneficiaire'];
        $Transaction->prenom_beneficiaire = $input['prenom_beneficiaire'];
        $Transaction->nom_beneficiaire = $input['nom_beneficiaire'];
        $Transaction->cni_beneficiaire = $input['cni_beneficiaire'];
        $Transaction->telephone_beneficiaire = $input['telephone_beneficiaire'];
        $Transaction->id_paiement = $input['id_paiement'];
        $Transaction->libelle_paiement = $input['libelle_paiement'];
        $Transaction->slug_paiement = $input['slug_paiement'];
        $Transaction->id_operateur = $input['id_operateur'];
        $Transaction->libelle_operateur = $input['libelle_operateur'];
        $Transaction->slug_operateur = $input['slug_operateur'];
        $Transaction->montant = $input['montant'];
        $Transaction->commentaire = $input['commentaire'];
        $Transaction->motif_rejet = $input['motif_rejet'];
        $Transaction->user_id = $input['user_id'];
        $Transaction->status = $input['status'];
        $Transaction->state = $input['state'];

        $Transaction->save();

        
        $old_beneficiaires = $Transaction->beneficiaire();
        $old_operateurs = $Transaction->operateur();
        $old_paiements = $Transaction->paiement();

        
        
        
        if(isset($input['id_beneficiaire'])){    
            foreach($old_beneficiaires as $beneficiaire){
                $beneficiaireObj = Beneficiaire::where('id',$beneficiaire)->first();
                $Transaction->beneficiaire()->detach($beneficiaireObj);
            }

            $beneficiaireObj = Beneficiaire::where('id',$input['id_beneficiaire'])->first();
            $Transaction-> beneficiaire()->attach( $beneficiaireObj);           
        }

        if(isset($input['id_operateur'])){       
            foreach($old_operateurs as $operateur){
                $operateurObj = Operateur::where('id',$operateur)->first();
                $Transaction->operateur()->detach($operateurObj);
            }

            $operateurObj = Operateur::where('id',$input['id_operateur'])->first();
            $Transaction-> operateur()->attach( $operateurObj);           
        }

        if(isset($input['id_paiement'])){      
            foreach($old_paiements as $paiement){
                $paiementObj = Paiement::where('id',$paiement)->first();
                $Transaction->paiement()->detach($paiementObj);
            } 

            $paiementObj = Operateur::where('id',$input['id_paiement'])->first();
            $Transaction-> paiement()->attach( $paiementObj);           
        }

        return response()
            ->json(["success" => true, "message" => "Transaction modifié avec succès.", "data" => $Transaction]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $Transaction)
    {
        $Transaction->delete();
        return response()
            ->json(["success" => true, "message" => "Transaction supprimée avec succès.", "data" => $Transaction]);
    }
}
