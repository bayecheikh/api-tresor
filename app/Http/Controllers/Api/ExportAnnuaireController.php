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

use App\Models\Annuaire;
use App\Models\User;

class ExportAnnuaireController extends Controller
{
    /**
     * Store a newly created resource in storagrolee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCSV(Request $request){

        $input = $request->all();

        $prenom = $input['prenom'];
        $nom = $input['nom'];     
        $telephone = $input['telephone'];
        $type_militant = $input['type_militant'];
        $region = $input['region'];
        $departement = $input['departement'];
        $commune = $input['commune'];

        $validator = Validator::make($input, []);
        if ($validator->fails())
        {
            return response()
            ->json($validator->errors());
        }
        else{ 
            if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin')) {
                $Annuaires = Annuaire::where('status','like', '%actif%');
            }
            else{           
                $user_id = $request->user()->id;
                $Annuaires = Annuaire::where('user_id', $user_id)->where('status','like', '%actif%');                      
            }
 
            if($prenom!=''){               
                $Annuaires = $Annuaires
                ->where('prenom','like', '%'.$prenom.'%');                  
            }
            if($nom!=''){               
                $Annuaires = $Annuaires
                ->where('nom','like', '%'.$nom.'%');                  
            }
            
            if($telephone!=''){               
                $Annuaires = $Annuaires
                ->where('telephone','like', '%'.$telephone.'%');                  
            }
            if($type_militant!=''){               
                $Annuaires = $Annuaires
                ->where('type_militant','like', '%'.$type_militant.'%');                  
            }
            
            if($region!=''){               
                $Annuaires = $Annuaires
                ->where('region','LIKE','%'.$region.'%');
                                 
            }
            if($departement!=''){               
                $Annuaires = $Annuaires
                ->where('departement','LIKE', '%'.$departement.'%');                  
            }
            if($commune!=''){               
                $Annuaires = $Annuaires
                ->where('commune','LIKE', '%'.$commune.'%');                  
            }

            $Annuaires = $Annuaires->get();

           //$Annuaires = $Annuaires->orderBy('created_at', 'DESC');

            $fileName = 'Annuaires.csv';
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
            /* 'numero_cedeao1',
            'numero_cedeao2', */
            'prenom',
            'nom',         
            'telephone',
            'type_militant',
            'region',
            'departement',
            'commune',        
        );

        $callback = function() use($Annuaires, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns,';');

            foreach ($Annuaires as $Annuaire) {   
                $row['prenom']  = $Annuaire->prenom;
                $row['nom']  = $Annuaire->nom;                             
                $row['telephone']  = $Annuaire->telephone;
                $row['type_militant']  = $Annuaire->type_militant;
                $row['region']  = $Annuaire->region;
                $row['departement']  = $Annuaire->departement;
                $row['commune']  = $Annuaire->commune;

                fputcsv($file, array( 
                    $row['prenom'],
                    $row['nom'],
                    $row['telephone'],
                    $row['type_militant'],
                    $row['region'],
                    $row['departement'],
                    $row['commune'],
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
