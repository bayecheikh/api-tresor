<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\Parrainage;
use App\Models\User;

class ExportParrainageController extends Controller
{
    /**
     * Store a newly created resource in storagrolee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCSV(Request $request){

        $input = $request->all();

        $numero_cedeao = $input['numero_cedeao'];
        $prenom = $input['prenom'];
        $nom = $input['nom'];
        $date_naissance = $input['date_naissance'];
        $lieu_naissance = $input['lieu_naissance'];
        $taille = $input['taille'];
        $sexe = $input['sexe'];
        $numero_electeur = $input['numero_electeur'];
        $centre_vote = $input['centre_vote'];
        $bureau_vote = $input['bureau_vote'];
        $numero_cin = $input['numero_cin'];
        $telephone = $input['telephone'];
        $prenom_responsable = $input['prenom_responsable'];
        $nom_responsable = $input['nom_responsable'];
        $telephone_responsable = $input['telephone_responsable'];
        $region = $input['region'];
        $departement = $input['departement'];
        $commune = $input['commune'];
        $created_at = $input['created_at'];

        $validator = Validator::make($input, []);
        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
            if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin')) {
                $Parrainages = Parrainage::where('status','like', '%actif%');
            }
            else{           
                $user_id = $request->user()->id;
                $Parrainages = Parrainage::where('user_id', $user_id)->where('status','like', '%actif%');                      
            }
            if(isset($input['user_id'])){
                $Parrainages = $Parrainages
                ->where('user_id','like', '%'.$input['user_id'].'%');   
            }
            if($numero_cedeao!=''){               
                $Parrainages = $Parrainages
                ->where('numero_cedeao','like', '%'.$numero_cedeao.'%');                  
            }
            if($prenom!=''){               
                $Parrainages = $Parrainages
                ->where('prenom','like', '%'.$prenom.'%');                  
            }
            if($nom!=''){               
                $Parrainages = $Parrainages
                ->where('nom','like', '%'.$nom.'%');                  
            }
            if($date_naissance!=''){               
                $Parrainages = $Parrainages
                ->where('date_naissance','like', '%'.$date_naissance.'%');                  
            }
            if($lieu_naissance!=''){               
                $Parrainages = $Parrainages
                ->where('lieu_naissance','like', '%'.$lieu_naissance.'%');                  
            }
            if($taille!=''){               
                $Parrainages = $Parrainages
                ->where('taille','like', '%'.$taille.'%');                  
            }
            if($sexe!=''){               
                $Parrainages = $Parrainages
                ->where('sexe','like', '%'.$sexe.'%');                  
            }
            if($numero_electeur!=''){               
                $Parrainages = $Parrainages
                ->where('numero_electeur','like', '%'.$numero_electeur.'%');                  
            }
            if($centre_vote!=''){               
                $Parrainages = $Parrainages
                ->where('centre_vote','like', '%'.$centre_vote.'%');                  
            }
            if($bureau_vote!=''){               
                $Parrainages = $Parrainages
                ->where('bureau_vote','like', '%'.$bureau_vote.'%');                  
            }
            if($numero_cin!=''){               
                $Parrainages = $Parrainages
                ->where('numero_cin','like', '%'.$numero_cin.'%');                  
            }
            if($telephone!=''){               
                $Parrainages = $Parrainages
                ->where('telephone','like', '%'.$telephone.'%');                  
            }
            if($prenom_responsable!=''){               
                $Parrainages = $Parrainages
                ->where('prenom_responsable','like', '%'.$prenom_responsable.'%');                  
            }
            if($nom_responsable!=''){               
                $Parrainages = $Parrainages
                ->where('nom_responsable','like', '%'.$nom_responsable.'%');                  
            }
            if($telephone_responsable!=''){               
                $Parrainages = $Parrainages
                ->where('telephone_responsable','like', '%'.$telephone_responsable.'%');                  
            }
            if($region!=''){               
                $Parrainages = $Parrainages
                ->where('region','LIKE','%'.$region.'%');
                                 
            }
            if($departement!=''){               
                $Parrainages = $Parrainages
                ->where('departement','LIKE', '%'.$departement.'%');                  
            }
            if($commune!=''){               
                $Parrainages = $Parrainages
                ->where('commune','LIKE', '%'.$commune.'%');                  
            }
            if($created_at!=''){               
                $Parrainages = $Parrainages
                ->where('created_at','LIKE', '%'.$created_at.'%');                  
            }

            $Parrainages = $Parrainages->get();

           //$Parrainages = $Parrainages->orderBy('created_at', 'DESC');

            $fileName = 'Parrainages.csv';
        // these are the headers for the csv file. Not required but good to have one incase of system didn't recongize it properly
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );


        //adding the first row

        $columns = array(
            'numero_cedeao',
            /* 'numero_cedeao1',
            'numero_cedeao2', */
            'prenom',
            'nom',
            'date_naissance',
            'lieu_naissance',
            'taille',
            'sexe',
            'numero_electeur',
            'centre_vote',
            'bureau_vote',
            /* 'numero_cin1',
            'numero_cin2', */
            'numero_cin',
            'telephone',
            'region',
            'departement',
            'commune',
            'prenom_responsable',
            'nom_responsable',
            'telephone_responsable',
            
        );

        $callback = function() use($Parrainages, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns,';');

            foreach ($Parrainages as $Parrainage) {   
                
                $row['numero_cedeao']  = "'".$Parrainage->numero_cedeao;
                /* $row['numero_cedeao1']  = substr($Parrainage->numero_cedeao, 0, 10);
                $row['numero_cedeao2']  = substr($Parrainage->numero_cedeao, -7); */
                $row['prenom']  = $Parrainage->prenom;
                $row['nom']  = $Parrainage->nom;
                $row['date_naissance']  = $Parrainage->date_naissance;
                $row['lieu_naissance']  = $Parrainage->lieu_naissance;
                $row['taille']  = $Parrainage->taille;
                $row['sexe']  = $Parrainage->sexe;
                $row['numero_electeur']  = "'".$Parrainage->numero_electeur;
                $row['centre_vote']  = $Parrainage->centre_vote;
                $row['bureau_vote']  = $Parrainage->bureau_vote;
                /* $row['numero_cin1']  = substr($Parrainage->numero_cin, 0, 10);
                $row['numero_cin2']  = substr($Parrainage->numero_cin, -3); */
                $row['numero_cin']  = "'".$Parrainage->numero_cin;
                $row['telephone']  = $Parrainage->telephone;
                $row['region']  = $Parrainage->region;
                $row['departement']  = $Parrainage->departement;
                $row['commune']  = $Parrainage->commune;
                $row['prenom_responsable']  = $Parrainage->prenom;
                $row['nom_responsable']  = $Parrainage->nom;
                $row['telephone_responsable']  = $Parrainage->telephone;

                fputcsv($file, array( 
                    $row['numero_cedeao'],
                    /* $row['numero_cedeao1'],
                    $row['numero_cedeao2'], */
                    $row['prenom'],
                    $row['nom'],
                    $row['date_naissance'],
                    $row['lieu_naissance'],
                    $row['taille'],
                    $row['sexe'],
                    $row['numero_electeur'],
                    $row['centre_vote'],
                    $row['bureau_vote'],
                    /* $row['numero_cin1'],
                    $row['numero_cin2'], */
                    $row['numero_cin'],
                    $row['telephone'],
                    $row['region'],
                    $row['departement'],
                    $row['commune'],
                    $row['prenom_responsable'],
                    $row['nom_responsable'],
                    $row['telephone_responsable'] 
                ),';');
            }

            fclose($file);
        };


        //download command
        return response()->stream($callback, 200, $headers);
        }
    }

    public function exportPDF(){

    }
}
