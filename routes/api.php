<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AnalyseRisqueController;
use App\Http\Controllers\Api\AnalyseGenreController;
use App\Http\Controllers\Api\SectorielController;
use App\Http\Controllers\Api\DonneeBaseController;
use App\Http\Controllers\Api\CibleFinController;
use App\Http\Controllers\Api\ResultatAttenduController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\DepartementController;
use App\Http\Controllers\Api\CommuneController;
use App\Http\Controllers\Api\BeneficiaireController;
use App\Http\Controllers\Api\ProjetController;
use App\Http\Controllers\Api\EnquetteController;
use App\Http\Controllers\Api\StructureController;
use App\Http\Controllers\Api\DimensionController;
use App\Http\Controllers\Api\TypeZoneInterventionController;
use App\Http\Controllers\Api\SourceFinancementController;
use App\Http\Controllers\Api\TypeSourceController;
use App\Http\Controllers\Api\AxeController;
use App\Http\Controllers\Api\InvestissementController;
use App\Http\Controllers\Api\RechercheInvestissementController;
use App\Http\Controllers\Api\ExportInvestissementController;
use App\Http\Controllers\Api\LigneFinancementController;
use App\Http\Controllers\Api\ModeFinancementController;
use App\Http\Controllers\Api\PilierController;
use App\Http\Controllers\Api\TypeLigneController;
use App\Http\Controllers\Api\MonnaieController;
use App\Http\Controllers\Api\AnneeController;
use App\Http\Controllers\Api\TypeAnneeController;
use App\Http\Controllers\Api\LigneModeInvestissementController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\DemandeController;
use App\Http\Controllers\Api\StatistiqueController;
use App\Http\Controllers\Api\ActiviteController;
use App\Http\Controllers\Api\FinancementActiviteController;
use App\Http\Controllers\Api\FinancementSourceController;
use App\Http\Controllers\Api\BailleurController;
use App\Http\Controllers\Api\SecteurController;
use App\Http\Controllers\Api\SousSecteurController;
use App\Http\Controllers\Api\ParrainageController;
use App\Http\Controllers\Api\RechercheParrainageController;
use App\Http\Controllers\Api\ExportParrainageController;
use App\Http\Controllers\Api\AnnuaireController;
use App\Http\Controllers\Api\RechercheAnnuaireController;
use App\Http\Controllers\Api\ExportAnnuaireController;
use App\Http\Controllers\Api\SmsAnnuaireController;
use App\Http\Controllers\Api\TypeMilitantController;
use App\Http\Controllers\Api\OperateurController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PaiementController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget_password', [AuthController::class, 'forget_password']);

 /**Statistique*/
 Route::get('allPiliers', [StatistiqueController::class, 'allPilier']);
 Route::get('allAxes', [StatistiqueController::class, 'allAxe']);
 Route::get('allAnnees', [StatistiqueController::class, 'allAnnee']);
 Route::get('allRegions', [StatistiqueController::class, 'allRegion']);
 Route::get('allMonnaies', [StatistiqueController::class, 'allMonnaie']);
 Route::get('allStructures', [StatistiqueController::class, 'allStructure']);
 Route::get('allDimensions', [StatistiqueController::class, 'allDimension']);
 Route::get('allSources', [StatistiqueController::class, 'allSource']);

 Route::get('investissementByPilier/{idPilier}', [StatistiqueController::class, 'investissementByPilier']);
 Route::get('investissementByAxe/{idAxe}', [StatistiqueController::class, 'investissementByAxe']);
 Route::get('investissementByAnnee/{idAnnee}', [StatistiqueController::class, 'investissementByAnnee']);
 Route::get('investissementByRegion/{idRegion}', [StatistiqueController::class, 'investissementByRegion']);
 Route::get('investissementByMonnaie/{idMonnaie}', [StatistiqueController::class, 'investissementByMonnaie']);
 Route::get('investissementByStructure/{idStructure}', [StatistiqueController::class, 'investissementByStructure']);
 Route::get('investissementByDimension/{idDimension}', [StatistiqueController::class, 'investissementByDimension']);
 Route::get('investissementBySource/{idSource}', [StatistiqueController::class, 'investissementBySource']);
  
Route::middleware('auth:api')->group(function () {
    Route::resource('products', ProductController::class);

    /**Gestion des authentification */
    Route::get('get-user', [AuthController::class, 'userInfo']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update_password', [AuthController::class, 'update_password']);

    /**Gestion des utilisateurs */
    Route::resource('users', UserController::class);
    Route::get('user-multiple-search/{term}', [UserController::class, 'userMultipleSearch']);
    Route::get('active_user/{id}', [UserController::class, 'activeUser']);

    /**Gestion des roles */
    Route::resource('roles', RoleController::class);

    /**Gestion des permissions */
    Route::resource('permissions', PermissionController::class);

    /**Gestion des analyses risque */
    Route::resource('analyserisques', AnalyseRisqueController::class);
    Route::get('analyserisques-multiple-search/{term}', [AnalyseRisqueController::class, 'analyseMultipleSearch']);
    Route::get('active_analyse_risques/{id}', [AnalyseRisqueController::class, 'activeanalyse']);
    
    /**Gestion des analyses genre */
    Route::resource('analysegenres', AnalyseGenreController::class);
    Route::get('analysegenres-multiple-search/{term}', [AnalyseGenreController::class, 'analyseMultipleSearch']);
    Route::get('active_analyse_genres/{id}', [AnalyseGenreController::class, 'activeanalyse']);
     
    /**Gestion des analyses genre */
     Route::resource('sectoriels', SectorielController::class);
     Route::get('sectoriels-multiple-search/{term}', [SectorielController::class, 'sectorielMultipleSearch']);
     Route::get('allSectoriel', [SectorielController::class, 'allSectoriel']);
     Route::post('validation_sectoriel', [SectorielController::class, 'validation_sectoriel']);
     Route::post('rejet_sectoriel', [SectorielController::class, 'rejet_sectoriel']);

     /**Gestion des analyses genre */
     Route::resource('donnee_bases', DonneeBaseController::class);
     Route::get('donnee_bases-multiple-search/{term}', [DonneeBaseController::class, 'donneeBaseMultipleSearch']);
     Route::get('allDonneeBase', [DonneeBaseController::class, 'allDonneeBase']);

     /**Gestion des analyses genre */
     Route::resource('cible_fins', CibleFinController::class);
     Route::get('cible_fins-multiple-search/{term}', [CibleFinController::class, 'cibleFinMultipleSearch']);

     /**Gestion des analyses genre */
     Route::resource('resultat_attendus', ResultatAttenduController::class);
     Route::get('resultat_attendus-multiple-search/{term}', [ResultatAttenduController::class, 'resultatAttenduMultipleSearch']);

    /**Gestion des regions */
    Route::resource('regions', RegionController::class);

    /**Gestion des departements */
    Route::resource('departements', DepartementController::class);

    /**Gestion des communes */
    Route::resource('communes', CommuneController::class);

    /**Gestion des communes */
    Route::resource('beneficiaires', BeneficiaireController::class);
    Route::get('beneficiaire-multiple-search/{term}', [BeneficiaireController::class, 'beneficiaireMultipleSearch']);
    Route::get('beneficiaire-by-term/{term}', [BeneficiaireController::class, 'beneficiaireByTerm']);
    Route::get('beneficiaireByCommune/{term}', [BeneficiaireController::class, 'beneficiaireByCommune']);

    /**Gestion des operateurss */
    Route::resource('operateurs', OperateurController::class);

     /**Gestion des paiement */
     Route::resource('paiements', PaiementController::class);

    /**Gestion des transactions */
    Route::resource('transactions', TransactionController::class);
    Route::get('transaction-multiple-search/{term}', [TransactionController::class, 'transactionMultipleSearch']);

    /**Gestion des projets */
    Route::resource('projets', ProjetController::class);
    Route::get('projet-multiple-search/{term}', [projetController::class, 'projetMultipleSearch']);

    /**Gestion des enquettes */
    Route::resource('enquettes', EnquetteController::class);
    Route::get('enquette-multiple-search/{term}', [EnquetteController::class, 'enquetteMultipleSearch']);
    Route::get('active_enquettes/{id}', [EnquetteController::class, 'activeenquette']);

    /**Gestion des structures */
    Route::resource('structures', StructureController::class);
    Route::get('structure-multiple-search/{term}', [StructureController::class, 'structureMultipleSearch']);
    Route::get('selectstructures', [StructureController::class, 'selectstructure']);

    /**Gestion des dimensions */
    Route::resource('dimensions', DimensionController::class);

    /**Gestion des types de zone */
    Route::resource('type_zones', TypeZoneInterventionController::class);

    /**Gestion des sources de financement */
    Route::resource('source_financements', SourceFinancementController::class);

    /**Gestion des types de source */
    Route::resource('type_sources', TypeSourceController::class);

    /**Gestion des axes */
    Route::resource('axes', AxeController::class);

    /**Gestion des investissements */
    Route::resource('investissements', InvestissementController::class);
    Route::post('validation_investissement', [InvestissementController::class, 'validation_investissement']);
    Route::post('rejet_investissement', [InvestissementController::class, 'rejet_investissement']);

    /**Recherche avancée sur les investissements */
    Route::resource('recherche_avances', InvestissementController::class);
    Route::post('recherche_avance_investissements', [RechercheInvestissementController::class, 'recherche']);
    Route::post('export_csv_investissements', [ExportInvestissementController::class, 'exportCSV']);
    Route::post('export_pdf_investissements', [ExportInvestissementController::class, 'exportPDF']);

    /**Recherche avancée parrainage */
    Route::resource('parrainages', ParrainageController::class);
    Route::post('recherche_avance_parrainages', [RechercheParrainageController::class, 'recherche']);
    Route::post('export_csv_parrainages', [ExportParrainageController::class, 'exportCSV']);
    Route::post('export_pdf_parrainages', [ExportParrainageController::class, 'exportPDF']);
    Route::post('parrainageByNumCedeao', [RechercheParrainageController::class, 'parrainageByNumCedeao']);
    Route::post('parrainageByNumElecteur', [RechercheParrainageController::class, 'parrainageByNumElecteur']);
    Route::post('parrainageByNumCin', [RechercheParrainageController::class, 'parrainageByNumCin']);
    Route::post('doublonCedeao', [RechercheParrainageController::class, 'doublonCedeao']);
    Route::post('doublonCin', [RechercheParrainageController::class, 'doublonCin']);
    Route::post('doublonNumElecteur', [RechercheParrainageController::class, 'doublonNumElecteur']);
    Route::post('sansDoublon', [RechercheParrainageController::class, 'sansDoublon']);

    /**Recherche avancée annuaire */
    Route::resource('annuaires', AnnuaireController::class);
    Route::post('recherche_avance_annuaires', [RechercheAnnuaireController::class, 'recherche']);
    Route::post('export_csv_annuaires', [ExportAnnuaireController::class, 'exportCSV']);
    Route::post('export_pdf_annuaires', [ExportAnnuaireController::class, 'exportPDF']);
    Route::post('sendSms', [SmsAnnuaireController::class, 'sendSms']);
    Route::post('annuaireByNumCedeao', [RechercheAnnuaireController::class, 'annuaireByNumCedeao']);
    Route::post('annuaireByNumElecteur', [RechercheAnnuaireController::class, 'annuaireByNumElecteur']);
    Route::post('annuaireByNumCin', [RechercheAnnuaireController::class, 'annuaireByNumCin']);

    /**Gestion des types militants */
    Route::resource('type_militants', TypeMilitantController::class);

    /**Gestion des lignes de financement */
    Route::resource('ligne_financements', LigneFinancementController::class);

    /**Gestion des modes de financement */
    Route::resource('mode_financements', ModeFinancementController::class);

    /**Gestion des piliers */
    Route::resource('piliers', PilierController::class);

    /**Gestion des types de ligne */
    Route::resource('type_lignes', TypeLigneController::class);

    /**Gestion des monnaies */
    Route::resource('monnaies', MonnaieController::class);

    /**Gestion des annees */
    Route::resource('annees', AnneeController::class);

    /**Gestion des types annees */
    Route::resource('type_annees', TypeAnneeController::class);

    /**Gestion des activités */
    Route::resource('activites', ActiviteController::class);


    /**Gestion des activités */
    Route::resource('bailleurs', BailleurController::class);

    /**Gestion des activités */
    Route::resource('secteurs', SecteurController::class);

    /**Gestion des activités */
    Route::resource('sous_secteurs', SousSecteurController::class);

    /**Gestion des financement par activité */
    Route::resource('financement_activites', FinancementActiviteController::class);
    Route::get('activiteByType/{term}', [FinancementActiviteController::class, 'financementByType']);

    /**Gestion des financement par activité */
    Route::resource('financement_sources', FinancementSourceController::class);
    Route::get('sourceByType/{term}', [FinancementSourceController::class, 'financementByType']);

    /**Gestion des lignes mode investissements */
    Route::resource('ligne_mode_investissements', LigneModeInvestissementController::class);

    /**Gestion des demandes */
    Route::resource('demandes', DemandeController::class);

    /**Gestion des profils */
    Route::resource('profils', ProfilController::class);
    /**Statistique*/
    Route::resource('statistiques', LigneModeInvestissementController::class);
});
