<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ApilistfacturedetailController extends Controller
{
    // public function list_facture_inpayer_d($id)
    // {

    //     // $facture_cons = DB::table('consultation')
    //     //     ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
    //     //     ->join('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
    //     //     ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
    //     //     ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
    //     //     ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
    //     //     ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
    //     //     ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
    //     //     ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
    //     //     ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
    //     //     ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
    //     //     ->where('consultation.idconsexterne', '=', $id)
    //     //     ->select(
    //     //         'consultation.idconsexterne as idconsexterne',
    //     //         'consultation.idenregistremetpatient as idenregistremetpatient',
    //     //         'consultation.montant as montant',
    //     //         'consultation.date as date',
    //     //         'consultation.numfac as numfac',

    //     //         'consultation.numbon as numbon',
    //     //         'consultation.ticketmod as partpatient',
    //     //         'consultation.partassurance as partassurance',

    //     //         'dossierpatient.numdossier as numdossier',
    //     //         'patient.nomprenomspatient as nom_patient',
    //     //         'patient.telpatient as tel_patient',
    //     //         'patient.assure as assure',
    //     //         'patient.datenaispatient as datenais',
    //     //         'patient.telpatient as telpatient',
    //     //         'patient.matriculeassure as matriculeassure',
    //     //         'medecin.nomprenomsmed as nom_medecin',
    //     //         'medecin.contact as tel_medecin',
    //     //         'specialitemed.nomspecialite as specialite',
    //     //         'factures.remise as remise',
    //     //         'factures.montantregle_pat as montant_regle',
    //     //         'factures.montantreste_pat as montant_reste',
    //     //         'factures.datereglt_pat as date_regle',
    //     //         'factures.numrecu as numrecu',
    //     //         'societeassure.nomsocieteassure as societe',
    //     //         'assurance.libelleassurance as assurance',
    //     //         'tauxcouvertureassure.valeurtaux as taux',
    //     //         'filiation.libellefiliation as filiation',
    //     //     )
    //     //     ->first();

    //     return response()->json(['facture_cons' => $facture_cons]);
    // }

    public function list_facture_hos_d($numhospit)
    {
        $hopital = DB::table('admission')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'admission.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
            ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
            ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
            ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
            ->join('chambres', 'chambres.id', '=', 'admission.codechbre')
            ->join('lits', 'lits.id', '=', 'admission.idtypelit')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('dossierpatient.codetypedossier', '=', 'DH')
            ->where('admission.numhospit', '=', $numhospit)
            ->select(
                'admission.*',
                'patient.nomprenomspatient as patient',
                'dossierpatient.numdossier as numdossier',
                'patient.assure as assure',
                'assurance.libelleassurance as assurance',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite',
            )
            ->first();

        $hopital->taux = $hopital->taux ?? 0;

        $factured = DB::table('orders')
            ->join('orders_detail', 'orders_detail.order_id', '=', 'orders.id')
            ->join('medicine', 'medicine.medicine_id', '=', 'orders_detail.product_id')
            ->where('orders.num_hospit', '=', $numhospit)
            ->select(
                'orders.partassurance as partassurance',
                'orders.taux as taux',
                'orders_detail.qty as quantite',
                'orders_detail.rate as prix_u',
                'orders_detail.amount as prix_t',
                'medicine.name as name',
            )
            ->get();

        return response()->json(['factured' => $factured,'hopital' => $hopital]);
    }

    public function list_facture_hos_d2($numhospit)
    {

        $factured = DB::table('facturation_hospit')
            ->join('garanties_hospit', 'garanties_hospit.id', '=', 'facturation_hospit.idgarhospit')
            ->where('facturation_hospit.numpchr', '=', $numhospit)
            ->select(
                'facturation_hospit.id_fachosp as id',
                'garanties_hospit.libelle as name',
                'facturation_hospit.numfac as numfac',
                'facturation_hospit.pu as prix',
                'facturation_hospit.montaccorde as prix_ass',
                'facturation_hospit.montrefus as prix_pat',
            )
            ->get();

        return response()->json(['factured' => $factured]);
    }

    public function list_facture_exam_d($id)
    {
        $factured = DB::table('detailtestlaboimagerie')
            ->where('idtestlaboimagerie', '=', $id)
            ->select(
                'detailtestlaboimagerie.denomination as examen',
                'detailtestlaboimagerie.resultat as resultat',
                'detailtestlaboimagerie.prix as prix',
            )
            ->get();

        $sumMontantEx = $factured->sum(function ($item) {
            // Retirer le point du montant et le convertir en entier
            $montantEx = str_replace('.', '', $item->prix);
            return (int) $montantEx;
        });

        return response()->json(['factured' => $factured, 'sumMontantEx' => $sumMontantEx]);
    }
}
