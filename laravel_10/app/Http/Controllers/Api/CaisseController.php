<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class CaisseController extends Controller
{

	public function verification()
	{
        $caisse = DB::table('porte_caisses')->where('id', '=', 1)->first();

        return response()->json(['caisse' => $caisse]);
    }

	public function ouverture(Request $request)
	{
	    $caisse = DB::table('porte_caisses')
	        ->where('id', 1)
	        ->first();

	    if (!$caisse) {
	        return response()->json([
	            'error' => true,
	            'message' => 'La porte de caisse est introuvable.'
	        ], 404);
	    }

	    if ($caisse->statut === 'ouvert') {
	        return response()->json(['deja' => true]);
	    }

	    DB::beginTransaction();

	    try {

	        // -------------------------------------------------
	        // 1. OUVERTURE DE LA CAISSE
	        // -------------------------------------------------

	        $updated = DB::table('porte_caisses')
	            ->where('id', 1)
	            ->update([
	                'statut'     => 'ouvert',
	                'updated_at' => now(),
	            ]);

	        if ($updated === 0) {
	            throw new Exception(
	                'Erreur lors de la mise à jour de la table porte_caisses.'
	            );
	        }

	        // -------------------------------------------------
	        // 2. CALCUL DU SOLDE
	        // -------------------------------------------------

	        $solde = $this->getSoldeCaisse();

	        // -------------------------------------------------
	        // 3. ENREGISTREMENT DU RESUME
	        // -------------------------------------------------

	        $this->enregistrerResumeCaisse(
	            montant: $solde,
	            action: 0,
	            login: $request->login
	        );

	        // -------------------------------------------------
	        // 4. ENVOI DE L'EMAIL
	        // -------------------------------------------------

	        $this->envoyerMailOuverture($solde);

	        DB::commit();

	        return response()->json([
	            'success' => true
	        ]);

	    } catch (Exception $e) {

	        DB::rollBack();

	        return response()->json([
	            'error'   => true,
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}

	public function fermeture(Request $request)
	{
	    $caisse = DB::table('porte_caisses')
	        ->where('id', 1)
	        ->first();

	    if (!$caisse) {
	        return response()->json([
	            'error' => true,
	            'message' => 'La porte de caisse est introuvable.'
	        ], 404);
	    }

	    if ($caisse->statut === 'fermer') {
	        return response()->json(['deja' => true]);
	    }

	    DB::beginTransaction();

	    try {

	        // -------------------------------------------------
	        // 1. FERMETURE DE LA CAISSE
	        // -------------------------------------------------

	        $updated = DB::table('porte_caisses')
	            ->where('id', 1)
	            ->update([
	                'statut'     => 'fermer',
	                'updated_at' => now(),
	            ]);

	        if ($updated === 0) {
	            throw new Exception(
	                'Erreur lors de la mise à jour de la table porte_caisses.'
	            );
	        }

	        // -------------------------------------------------
	        // 2. RECUPERATION DES OPERATIONS NON ENVOYEES
	        // -------------------------------------------------

	        $transactions = DB::table('caisse')
	            ->where('mail', 0)
	            ->get();

	        // -------------------------------------------------
	        // 3. CALCUL DU BILAN
	        // -------------------------------------------------

	        $bilan = $this->calculerBilan($transactions);

	        // -------------------------------------------------
	        // 4. ENVOI DE L'EMAIL
	        // -------------------------------------------------

	        $mailEnvoye = $this->envoyerMailFermeture(
			    $transactions,
			    $bilan
			);

	        // -------------------------------------------------
	        // 5. MARQUER LES OPERATIONS COMME ENVOYEES
	        // -------------------------------------------------

	        if ($mailEnvoye) {

			    DB::table('caisse')
			        ->where('mail', 0)
			        ->update([
			            'mail'       => 1,
			            'updated_at' => now(),
			        ]);
			}

	        // -------------------------------------------------
	        // 6. SOLDE GLOBAL
	        // -------------------------------------------------

	        $solde = $this->getSoldeCaisse();

	        // -------------------------------------------------
	        // 7. ENREGISTRER LE RESUME
	        // -------------------------------------------------

	        $this->enregistrerResumeCaisse(
	            montant: $solde,
	            action: 2,
	            login: $request->login
	        );

	        DB::commit();

	        return response()->json([
	            'success' => true
	        ]);

	    } catch (Exception $e) {

	        DB::rollBack();

	        return response()->json([
	            'error'   => true,
	            'message' => $e->getMessage()
	        ], 500);
	    }
	}


	/*
	|--------------------------------------------------------------------------
	| MÉTHODES PRIVÉES
	|--------------------------------------------------------------------------
	*/


	/**
	 * Retourne le solde actuel de la caisse.
	 */
	private function getSoldeCaisse()
	{
	    return DB::table('caisse')
	        ->selectRaw("
	            SUM(
	                CASE
	                    WHEN type = 'entree' THEN montant
	                    ELSE -montant
	                END
	            ) AS solde
	        ")
	        ->value('solde') ?? 0;
	}


	/**
	 * Enregistre une opération dans caisse_resume.
	 */
	private function enregistrerResumeCaisse($montant, $action, $login)
	{
	    $inserted = DB::table('caisse_resume')->insert([
	        'datecaisse'  => now(),
	        'mtcaisse'    => $montant,
	        'action'      => $action,
	        'user'        => $login,
	        'heurecaisse' => now()->format('H:i:s'),
	    ]);

	    if (!$inserted) {
	        throw new Exception(
	            'Erreur lors de l\'insertion dans caisse_resume.'
	        );
	    }
	}


	/**
	 * Calcule les entrées, sorties et solde des transactions.
	 */
	private function calculerBilan($transactions)
	{
	    $entrees = 0;
	    $sorties = 0;

	    foreach ($transactions as $transaction) {

	        $montant = (float) str_replace(
	            '.',
	            '',
	            $transaction->montant
	        );

	        if ($transaction->type === 'entree') {
	            $entrees += $montant;
	        } else {
	            $sorties += $montant;
	        }
	    }

	    return [
	        'entrees' => $entrees,
	        'sorties' => $sorties,
	        'total'   => $entrees - $sorties,
	    ];
	}


	/**
	 * Configuration commune de PHPMailer.
	 */
	private function configurerMailer()
	{
	    $mail = new PHPMailer(true);

	    $mail->isHTML(true);
	    $mail->isSMTP();

	    // $mail->CharSet = 'UTF-8';
        // $mail->SMTPDebug = 2; // ou 3 pour plus de détails
        // $mail->Debugoutput = function($str, $level) {
        //     Log::info("SMTP Debug level $level: $str");
        // };

	    $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificationMail2000@gmail.com';
        $mail->Password = 'trav mpmj shqj nyhl';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('notificationMail2000@gmail.com', 'ESPACE SANTE TEST');

	    $mail->CharSet = 'UTF-8';

	    foreach ($this->getRecipients() as $recipient) {
	        $mail->addAddress($recipient);
	    }

	    return $mail;
	}


	/**
	 * Destinataires des alertes caisse.
	 */
	private function getRecipients()
	{
	    return [
	        'davidkouachi01@gmail.com',
	        // 'myghislainyao@gmail.com',
	    ];
	}


	/**
	 * Couleurs utilisées dans les emails caisse.
	 */
	private function getMailColors()
	{
	    return [
	        'primary'      => '#116aef',
	        'primaryDark'  => '#0d56c7',
	        'success'      => '#198754',
	        'danger'       => '#dc3545',
	        'light'        => '#f8f9fa',
	        'border'       => '#dee2e6',
	        'text'         => '#212529',
	        'muted'        => '#6c757d',
	        'white'        => '#ffffff',
	    ];
	}


	/**
	 * Format monétaire.
	 */
	private function formatMoney($montant)
	{
	    return number_format(
	        (float) $montant,
	        0,
	        ',',
	        '.'
	    ) . ' Fcfa';
	}


	/**
	 * Envoie l'email d'ouverture.
	 */
	private function envoyerMailOuverture($solde)
	{
	    try {

	        $colors = $this->getMailColors();

	        $mail = $this->configurerMailer();

	        $soldeFormatted = $this->formatMoney($solde);

	        $mail->Subject = 'ALERT ! Ouverture de la caisse';

	        $mail->Body = "
	            <div style=\"
	                font-family: Arial, Helvetica, sans-serif;
	                color: {$colors['text']};
	                max-width: 700px;
	                margin: auto;
	            \">

	                <div style=\"
	                    background: {$colors['primary']};
	                    color: {$colors['white']};
	                    padding: 20px;
	                    text-align: center;
	                    border-radius: 8px 8px 0 0;
	                \">
	                    <h2 style=\"margin:0;\">
	                        OUVERTURE DE LA CAISSE
	                    </h2>
	                </div>

	                <div style=\"
	                    border: 1px solid {$colors['border']};
	                    border-top: none;
	                    padding: 25px;
	                    background: {$colors['white']};
	                \">

	                    <p>
	                        La caisse vient d'être ouverte.
	                    </p>

	                    <div style=\"
	                        background: {$colors['light']};
	                        border-left: 5px solid {$colors['success']};
	                        padding: 15px;
	                        margin: 20px 0;
	                    \">
	                        <strong>Solde actuel :</strong>
	                        <span style=\"
	                            color: {$colors['success']};
	                            font-size: 20px;
	                            font-weight: bold;
	                        \">
	                            {$soldeFormatted}
	                        </span>
	                    </div>

	                    <p style=\"
	                        color: {$colors['muted']};
	                        font-size: 13px;
	                    \">
	                        Date :
	                        " . now()->format('d/m/Y à H:i:s') . "
	                    </p>

	                </div>

	                <div style=\"
	                    background: {$colors['light']};
	                    padding: 15px;
	                    text-align: center;
	                    font-size: 12px;
	                    color: {$colors['muted']};
	                    border-radius: 0 0 8px 8px;
	                \">
	                    ESPACE MEDICO-SOCIAL LA PYRAMIDE
	                </div>

	            </div>
	        ";

	        $mail->send();

	         return true;

	    } catch (Throwable $e) {

	        Log::warning('Email ouverture caisse non envoyé', [
	            'message' => $e->getMessage(),
	        ]);

	        return false;
	    }
	}


	/**
	 * Envoie l'email de fermeture avec le détail des opérations.
	 */
	private function envoyerMailFermeture($transactions, $bilan)
	{
	    try {

	        $colors = $this->getMailColors();

	        $mail = $this->configurerMailer();

	        $totalFormatted   = $this->formatMoney($bilan['total']);
	        $entreesFormatted = $this->formatMoney($bilan['entrees']);
	        $sortiesFormatted = $this->formatMoney($bilan['sorties']);

	        $tableRows = $this->genererLignesOperations(
	            $transactions,
	            $colors
	        );

	        $date = now()->format('d/m/Y à H:i:s');

	        $mail->Subject = 'ALERT ! Fermeture de la caisse';

	        $mail->Body = "
	            <div style=\"
	                font-family: Arial, Helvetica, sans-serif;
	                color: {$colors['text']};
	                max-width: 1000px;
	                margin: auto;
	            \">

	                <!-- HEADER -->

	                <div style=\"
	                    background: {$colors['danger']};
	                    color: {$colors['white']};
	                    padding: 20px;
	                    text-align: center;
	                    border-radius: 8px 8px 0 0;
	                \">

	                    <h2 style=\"margin:0;\">
	                        FERMETURE DE LA CAISSE
	                    </h2>

	                    <p style=\"
	                        margin: 8px 0 0;
	                        font-size: 14px;
	                    \">
	                        {$date}
	                    </p>

	                </div>

	                <!-- CONTENU -->

	                <div style=\"
	                    border: 1px solid {$colors['border']};
	                    border-top: none;
	                    padding: 20px;
	                    background: {$colors['white']};
	                \">

	                    <h3 style=\"margin-top:0;\">
	                        Bilan des opérations
	                    </h3>

	                    <!-- RESUME -->

	                    <table style=\"
	                        width:100%;
	                        border-collapse:collapse;
	                        margin-bottom:25px;
	                    \">

	                        <tr>

	                            <td style=\"
	                                padding:15px;
	                                border:1px solid {$colors['border']};
	                                text-align:center;
	                            \">

	                                <div style=\"
	                                    color:{$colors['muted']};
	                                    font-size:13px;
	                                \">
	                                    ENTRÉES
	                                </div>

	                                <strong style=\"
	                                    color:{$colors['success']};
	                                    font-size:18px;
	                                \">
	                                    + {$entreesFormatted}
	                                </strong>

	                            </td>

	                            <td style=\"
	                                padding:15px;
	                                border:1px solid {$colors['border']};
	                                text-align:center;
	                            \">

	                                <div style=\"
	                                    color:{$colors['muted']};
	                                    font-size:13px;
	                                \">
	                                    SORTIES
	                                </div>

	                                <strong style=\"
	                                    color:{$colors['danger']};
	                                    font-size:18px;
	                                \">
	                                    - {$sortiesFormatted}
	                                </strong>

	                            </td>

	                            <td style=\"
	                                padding:15px;
	                                border:1px solid {$colors['border']};
	                                text-align:center;
	                                background:{$colors['light']};
	                            \">

	                                <div style=\"
	                                    color:{$colors['muted']};
	                                    font-size:13px;
	                                \">
	                                    SOLDE
	                                </div>

	                                <strong style=\"
	                                    color:{$colors['primary']};
	                                    font-size:20px;
	                                \">
	                                    {$totalFormatted}
	                                </strong>

	                            </td>

	                        </tr>

	                    </table>


	                    <!-- OPERATIONS -->

	                    <h3>
	                        Détail des opérations
	                    </h3>

	                    <table style=\"
	                        width:100%;
	                        border-collapse:collapse;
	                        font-size:13px;
	                    \">

	                        <thead>

	                            <tr style=\"
	                                background:{$colors['primary']};
	                                color:{$colors['white']};
	                            \">

	                                <th style=\"padding:10px;\">
	                                    OPÉRATION
	                                </th>

	                                <th style=\"padding:10px;\">
	                                    ENTRÉES
	                                </th>

	                                <th style=\"padding:10px;\">
	                                    SORTIES
	                                </th>

	                                <th style=\"padding:10px;\">
	                                    DATE & HEURE
	                                </th>

	                            </tr>

	                        </thead>

	                        <tbody>

	                            {$tableRows}

	                        </tbody>

	                        <tfoot>

	                            <tr style=\"
	                                background:{$colors['light']};
	                                font-weight:bold;
	                            \">

	                                <td style=\"padding:10px;\">
	                                    TOTAUX
	                                </td>

	                                <td style=\"
	                                    padding:10px;
	                                    color:{$colors['success']};
	                                \">
	                                    + {$entreesFormatted}
	                                </td>

	                                <td style=\"
	                                    padding:10px;
	                                    color:{$colors['danger']};
	                                \">
	                                    - {$sortiesFormatted}
	                                </td>

	                                <td></td>

	                            </tr>

	                            <tr style=\"
	                                background:{$colors['primary']};
	                                color:{$colors['white']};
	                            \">

	                                <td colspan=\"3\"
	                                    style=\"
	                                        padding:15px;
	                                        text-align:right;
	                                        font-weight:bold;
	                                    \">
	                                    BILAN DES OPÉRATIONS
	                                </td>

	                                <td style=\"
	                                    padding:15px;
	                                    text-align:center;
	                                    font-size:17px;
	                                    font-weight:bold;
	                                \">
	                                    {$totalFormatted}
	                                </td>

	                            </tr>

	                        </tfoot>

	                    </table>

	                </div>

	                <!-- FOOTER -->

	                <div style=\"
	                    background:{$colors['light']};
	                    padding:15px;
	                    text-align:center;
	                    font-size:12px;
	                    color:{$colors['muted']};
	                    border-radius:0 0 8px 8px;
	                \">

	                    ESPACE MEDICO-SOCIAL LA PYRAMIDE

	                </div>

	            </div>
	        ";

	        $mail->send();

	        return true;

	    } catch (Throwable $e) {

	        Log::warning('Email fermeture caisse non envoyé', [
	            'message' => $e->getMessage(),
	        ]);

	        return false;
	    }
	}


	/**
	 * Génère les lignes HTML du tableau des opérations.
	 */
	private function genererLignesOperations($transactions, $colors)
	{
	    $rows = '';

	    foreach ($transactions as $transaction) {

	        $montant = (float) str_replace(
	            '.',
	            '',
	            $transaction->montant
	        );

	        $montantFormatted = number_format(
	            $montant,
	            0,
	            ',',
	            '.'
	        ) . ' Fcfa';

	        $date = Carbon::parse($transaction->datecreat)
	            ->format('d/m/Y à H:i:s');

	        if ($transaction->type === 'entree') {

	            $entree = "
	                <span style=\"
	                    color:{$colors['success']};
	                    font-weight:bold;
	                \">
	                    + {$montantFormatted}
	                </span>
	            ";

	            $sortie = '';

	        } else {

	            $entree = '';

	            $sortie = "
	                <span style=\"
	                    color:{$colors['danger']};
	                    font-weight:bold;
	                \">
	                    - {$montantFormatted}
	                </span>
	            ";
	        }

	        $rows .= "
	            <tr>

	                <td style=\"
	                    padding:10px;
	                    border:1px solid {$colors['border']};
	                \">
	                    {$transaction->libelle}
	                </td>

	                <td style=\"
	                    padding:10px;
	                    border:1px solid {$colors['border']};
	                    text-align:right;
	                \">
	                    {$entree}
	                </td>

	                <td style=\"
	                    padding:10px;
	                    border:1px solid {$colors['border']};
	                    text-align:right;
	                \">
	                    {$sortie}
	                </td>

	                <td style=\"
	                    padding:10px;
	                    border:1px solid {$colors['border']};
	                    text-align:center;
	                    white-space:nowrap;
	                \">
	                    {$date}
	                </td>

	            </tr>
	        ";
	    }

	    return $rows;
	}

}