CREATE TABLE `actes_as` (
  `code_acte` int(5) NOT NULL,
  `libelle_acte` varchar(50) NOT NULL,
  `code_typesoins` int(5) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admission`
--

CREATE TABLE `admission` (
  `numhospit` varchar(225) NOT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `codesocieteassure` varchar(225) DEFAULT NULL,
  `idenregistremetpatient` varchar(225) NOT NULL,
  `codemedecin` varchar(225) NOT NULL,
  `codetypehospit` varchar(225) NOT NULL,
  `codenaturehospit` varchar(225) NOT NULL,
  `dateentree` date NOT NULL,
  `datesortie` date NOT NULL,
  `nbredejrs` int(1) NOT NULL,
  `motifhospit` text NOT NULL,
  `codechbre` varchar(8) NOT NULL,
  `idtypelit` varchar(5) NOT NULL,
  `numfachospit` varchar(10) NOT NULL,
  `numbon` varchar(25) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `statut` varchar(225) DEFAULT NULL,
  `controle` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `assurance`
--

CREATE TABLE `assurance` (
  `idassurance` int(11) NOT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `libelleassurance` text NOT NULL,
  `telassurance` varchar(50) NOT NULL,
  `faxassurance` varchar(225) DEFAULT NULL,
  `emailassurance` varchar(50) NOT NULL,
  `adrassurance` varchar(50) NOT NULL,
  `situationgeo` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `mode_impression` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 : Impression des factures mensuelles par societe 1 : Impression des factures mensuelles par assureur',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `assureur`
--

CREATE TABLE `assureur` (
  `codeassureur` int(11) NOT NULL,
  `libelle_assureur` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `codeop` int(11) NOT NULL,
  `nopiece` varchar(50) NOT NULL,
  `type` varchar(6) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `montant` int(10) NOT NULL,
  `dateop` date NOT NULL,
  `datecreat` datetime DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `beneficiaire` varchar(100) DEFAULT NULL,
  `annule` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 : Saisie conforme 1 : Saisie annulée',
  `user_annule` varchar(15) DEFAULT NULL COMMENT 'Utilisateur ayant fait l''annulation',
  `date_annule` date DEFAULT NULL COMMENT 'Date de l''annulation',
  `reference` varchar(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `mail` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='caisse';

-- --------------------------------------------------------

--
-- Structure de la table `caisse_resume`
--

CREATE TABLE `caisse_resume` (
  `idcaisse` int(11) NOT NULL,
  `datecaisse` datetime DEFAULT NULL,
  `mtcaisse` int(11) NOT NULL,
  `action` tinyint(1) NOT NULL COMMENT '0 : Ouverture de caisse 1 : Opérations journalière 2 : Fermeture de caisse',
  `user` varchar(15) DEFAULT NULL,
  `heurecaisse` time NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `calendrier_medecin`
--

CREATE TABLE `calendrier_medecin` (
  `codecalendriermed` int(10) NOT NULL,
  `codemedecin` varchar(11) NOT NULL,
  `codespecialitemed` varchar(10) NOT NULL,
  `periode` varchar(15) NOT NULL,
  `heuredebut` varchar(5) NOT NULL,
  `heurefin` varchar(5) NOT NULL,
  `jour` varchar(15) NOT NULL,
  `mois` varchar(15) NOT NULL,
  `annee` year(4) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_produit`
--

CREATE TABLE `categorie_produit` (
  `ca_code` tinyint(2) NOT NULL,
  `ca_libelle` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambrehospit`
--

CREATE TABLE `chambrehospit` (
  `codechbre` int(10) NOT NULL,
  `nomchambre` varchar(20) NOT NULL,
  `prixchambre` int(6) NOT NULL,
  `nbredelit` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambres`
--

CREATE TABLE `chambres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `nbre_lit` varchar(191) NOT NULL,
  `prix` varchar(191) NOT NULL,
  `statut` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `civilite`
--

CREATE TABLE `civilite` (
  `code_civilite` int(10) NOT NULL,
  `libelle_civilite` varchar(20) NOT NULL,
  `abreviation` varchar(5) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comptabilite_resume`
--

CREATE TABLE `comptabilite_resume` (
  `id` int(11) NOT NULL,
  `date_crea` date NOT NULL,
  `montant` int(11) NOT NULL,
  `date_update` date DEFAULT NULL,
  `user_update` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

CREATE TABLE `comptes` (
  `numcpt` tinyint(4) NOT NULL,
  `libcpt` varchar(50) NOT NULL,
  `codemvt` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `constante`
--

CREATE TABLE `constante` (
  `numfac` varchar(10) NOT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `date` date NOT NULL,
  `tension_arterielle` varchar(6) DEFAULT NULL,
  `temperature` varchar(6) DEFAULT NULL,
  `poids` varchar(6) DEFAULT NULL,
  `pouls` varchar(6) DEFAULT NULL,
  `taille` varchar(6) DEFAULT NULL,
  `bras_droit` varchar(6) DEFAULT NULL,
  `bras_gauche` varchar(6) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consultation`
--

CREATE TABLE `consultation` (
  `idconsexterne` int(11) NOT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `codesocieteassure` varchar(225) DEFAULT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `numbon` varchar(10) DEFAULT NULL,
  `montant` int(6) DEFAULT 0,
  `taux` int(3) DEFAULT 0,
  `ticketmod` int(5) DEFAULT 0,
  `partassurance` int(5) DEFAULT 0,
  `codemedecin` varchar(11) DEFAULT NULL,
  `codeacte` varchar(10) DEFAULT NULL,
  `regle` tinyint(1) DEFAULT 0,
  `date` datetime DEFAULT NULL,
  `facimprime` tinyint(1) DEFAULT 0,
  `numfac` varchar(10) DEFAULT NULL,
  `fiche` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE `contrat` (
  `code` int(10) NOT NULL COMMENT 'Code du contrat',
  `libelle` text NOT NULL COMMENT 'libellé du contrat',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depotfactures`
--

CREATE TABLE `depotfactures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `periode_mois` varchar(191) NOT NULL,
  `periode_annee` varchar(191) NOT NULL,
  `date_depot` varchar(191) NOT NULL,
  `montant` varchar(191) NOT NULL,
  `type_paiement` varchar(191) DEFAULT NULL,
  `num_cheque` varchar(191) DEFAULT NULL,
  `date_payer` varchar(191) DEFAULT NULL,
  `statut` varchar(191) NOT NULL,
  `assurance_id` varchar(191) NOT NULL,
  `creer_id` varchar(191) NOT NULL,
  `encaisser_id` varchar(191) DEFAULT NULL,
  `montant_accepte` varchar(191) DEFAULT NULL,
  `montant_rejet` varchar(191) DEFAULT NULL,
  `motif_rejet` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detailtestlaboimagerie`
--

CREATE TABLE `detailtestlaboimagerie` (
  `iddetailtestlaboimagerie` int(10) NOT NULL,
  `idtestlaboimagerie` varchar(12) NOT NULL,
  `numexam` varchar(10) NOT NULL,
  `denomination` varchar(250) NOT NULL,
  `cotation` int(3) NOT NULL,
  `resultat` varchar(225) DEFAULT NULL,
  `prix` int(6) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dossierpatient`
--

CREATE TABLE `dossierpatient` (
  `numdossier` varchar(10) NOT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `datecrea` date NOT NULL,
  `codetypedossier` varchar(5) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `matricule` varchar(15) NOT NULL,
  `typepiece` varchar(9) NOT NULL,
  `nopiece` varchar(225) DEFAULT NULL,
  `civilite` varchar(5) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nomprenom` varchar(75) NOT NULL,
  `datenais` date NOT NULL,
  `profession` varchar(30) NOT NULL,
  `niveau` text NOT NULL,
  `diplome` text NOT NULL,
  `residence` varchar(50) NOT NULL,
  `dateenregistre` date NOT NULL,
  `cel` varchar(25) NOT NULL,
  `contacturgence` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `service` varchar(2) NOT NULL,
  `typecontrat` varchar(3) NOT NULL,
  `datecontrat` date NOT NULL,
  `datefincontrat` date DEFAULT NULL,
  `paye` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examen`
--

CREATE TABLE `examen` (
  `numexam` varchar(10) NOT NULL,
  `cot` smallint(6) DEFAULT NULL,
  `denomination` varchar(100) DEFAULT NULL,
  `codgaran` varchar(20) NOT NULL,
  `codfamexam` varchar(5) NOT NULL,
  `fam_acte_bio` varchar(6) DEFAULT NULL,
  `prix` int(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `facturation_chirurgie`
--

CREATE TABLE `facturation_chirurgie` (
  `id` int(11) NOT NULL,
  `numhospit` varchar(20) NOT NULL,
  `numfac` varchar(20) NOT NULL,
  `type_intervenant` tinyint(1) NOT NULL,
  `intervenant` varchar(20) NOT NULL,
  `montant_intervention` int(11) NOT NULL,
  `montant_honoraire` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `facturation_hospit`
--

CREATE TABLE `facturation_hospit` (
  `id_fachosp` int(11) NOT NULL,
  `numpchr` varchar(15) DEFAULT NULL,
  `idgarhospit` int(3) DEFAULT 0,
  `qte` int(5) DEFAULT 0,
  `pu` int(6) DEFAULT 0,
  `montgaran` int(6) DEFAULT 0,
  `montextra` int(6) DEFAULT 0,
  `montaccorde` int(6) DEFAULT 0,
  `montrefus` int(6) DEFAULT 0,
  `traiter` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `numfac` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `numfac` varchar(10) NOT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `montanttotal` int(6) NOT NULL,
  `remise` int(11) NOT NULL DEFAULT 0 COMMENT '	Montant de la remise sur la facture	',
  `type_remise` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 : Sans remise 1 : remise sur ticket modérateur 2 : remise sur montant total',
  `calcul_applique` tinyint(1) DEFAULT 1 COMMENT '1:Calcul par taux 2:Calcul par forfait',
  `taux_applique` int(4) DEFAULT NULL,
  `montant_ass` int(6) DEFAULT NULL,
  `montant_pat` int(11) DEFAULT NULL,
  `montantregle_ass` int(11) NOT NULL DEFAULT 0,
  `montantregle_pat` int(6) DEFAULT 0,
  `montantreste_ass` int(11) NOT NULL DEFAULT 0,
  `montantreste_pat` int(6) DEFAULT NULL,
  `modereglt_ass` varchar(8) DEFAULT NULL,
  `modereglt_pat` varchar(8) DEFAULT NULL,
  `solde_ass` tinyint(1) NOT NULL DEFAULT 0,
  `solde_pat` tinyint(1) NOT NULL DEFAULT 0,
  `datereglt_ass` date DEFAULT NULL,
  `datereglt_pat` datetime DEFAULT NULL,
  `numrecu` varchar(12) DEFAULT NULL,
  `codeassurance` varchar(2256) DEFAULT NULL,
  `numcheque_ass` varchar(20) DEFAULT NULL,
  `numcheque_pat` varchar(20) DEFAULT NULL,
  `datefacture` datetime DEFAULT NULL,
  `type_facture` int(2) NOT NULL DEFAULT 3,
  `numfac_tp` varchar(11) DEFAULT NULL,
  `timbre_fiscal` int(11) NOT NULL DEFAULT 0,
  `a_encaisser` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `montantpat_verser` int(11) DEFAULT 0,
  `montantpat_remis` int(11) DEFAULT 0,
  `numhospit` varchar(225) DEFAULT NULL,
  `login` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures_tierspayant`
--

CREATE TABLE `factures_tierspayant` (
  `numfac_tp` varchar(11) NOT NULL,
  `date_crea` date NOT NULL,
  `date_depot` date DEFAULT NULL,
  `periode_conso` varchar(8) NOT NULL,
  `montant_facture` int(12) NOT NULL,
  `idassurance` varchar(11) NOT NULL,
  `codesocieteassure` varchar(25) NOT NULL,
  `num_cheque` varchar(11) DEFAULT NULL,
  `montant_regle` int(11) DEFAULT NULL,
  `date_reglement` date DEFAULT NULL,
  `regle` tinyint(1) NOT NULL DEFAULT 0,
  `montant_rejete` int(11) DEFAULT NULL,
  `motif_rejet` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `famille_actes_biologie`
--

CREATE TABLE `famille_actes_biologie` (
  `id` varchar(6) NOT NULL,
  `libelle` varchar(250) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `famille_examen`
--

CREATE TABLE `famille_examen` (
  `codfamexam` varchar(5) NOT NULL,
  `nomfamexam` varchar(50) NOT NULL,
  `codtypgar` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiation`
--

CREATE TABLE `filiation` (
  `codefiliation` int(1) NOT NULL,
  `libellefiliation` varchar(15) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `garantie`
--

CREATE TABLE `garantie` (
  `codgaran` varchar(10) NOT NULL,
  `libgaran` varchar(80) NOT NULL,
  `codtypgar` varchar(10) DEFAULT NULL,
  `pratique` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `garanties_hospit`
--

CREATE TABLE `garanties_hospit` (
  `id` int(3) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `affichage_chirurgie` int(2) NOT NULL,
  `affiche_hospitalisation` int(2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historiqueudfac`
--

CREATE TABLE `historiqueudfac` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_assurance`
--

CREATE TABLE `historique_assurance` (
  `id` int(5) NOT NULL,
  `idenregistremetpatient` varchar(50) NOT NULL,
  `assure` varchar(50) DEFAULT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `codefiliation` varchar(225) DEFAULT NULL,
  `matriculeassure` varchar(225) DEFAULT NULL,
  `codesocieteassure` varchar(225) DEFAULT NULL,
  `idtauxcouv` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `honoraire`
--

CREATE TABLE `honoraire` (
  `id` int(10) NOT NULL,
  `code_honoraire` varchar(10) NOT NULL,
  `type_honoraire` tinyint(1) NOT NULL,
  `date_execution` date NOT NULL,
  `user_execution` varchar(20) DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `codemedecin` varchar(11) NOT NULL,
  `montant_honoraire` int(10) NOT NULL,
  `montant_bnc` int(10) NOT NULL,
  `regle` tinyint(1) NOT NULL DEFAULT 0,
  `date_reglement` date DEFAULT NULL,
  `mode_reglement` tinyint(1) DEFAULT NULL COMMENT '0 : espèce 1 : chèque',
  `user_reglement` varchar(20) DEFAULT NULL,
  `numero_cheque` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `idenregistremetpatient` varchar(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `numrecu` varchar(15) NOT NULL,
  `montant_recu` int(11) NOT NULL,
  `numjournal` varchar(225) DEFAULT NULL,
  `numfac` varchar(15) DEFAULT NULL,
  `type_action` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 : Entrée d''espèce 1 : Sortie d''espèce',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journalisation_actions`
--

CREATE TABLE `journalisation_actions` (
  `user_username` varchar(24) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `type_action` varchar(25) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `donnees` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `joursemaines`
--

CREATE TABLE `joursemaines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jour` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lits`
--

CREATE TABLE `lits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `statut` varchar(191) NOT NULL,
  `chambre_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `codemedecin` varchar(11) NOT NULL,
  `titremed` varchar(5) NOT NULL,
  `nommedecin` varchar(10) NOT NULL,
  `prenomsmedecin` varchar(25) NOT NULL,
  `nomprenomsmed` varchar(50) NOT NULL,
  `codespecialitemed` varchar(10) DEFAULT NULL,
  `numordremed` int(8) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `dateservice` date NOT NULL,
  `email` varchar(30) NOT NULL,
  `actif` tinyint(1) DEFAULT NULL,
  `hospital_id` int(5) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medicine`
--

CREATE TABLE `medicine` (
  `medicine_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `medicine_category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `price` longtext NOT NULL,
  `manufacturing_company` text DEFAULT NULL,
  `status` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medicine_category`
--

CREATE TABLE `medicine_category` (
  `medicine_category_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvements`
--

CREATE TABLE `mouvements` (
  `codemvt` tinyint(4) NOT NULL,
  `libemvt` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `naturehospit`
--

CREATE TABLE `naturehospit` (
  `idnathospit` varchar(5) NOT NULL,
  `nomnaturehospit` varchar(25) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operations`
--

CREATE TABLE `operations` (
  `codoper` smallint(6) NOT NULL,
  `liboper` varchar(100) NOT NULL,
  `numcpt` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `numfac` varchar(255) NOT NULL,
  `idenregistremetpatient` varchar(225) DEFAULT NULL,
  `date` date NOT NULL,
  `montant` int(5) NOT NULL,
  `taux` varchar(3) NOT NULL,
  `ticketmod` int(5) NOT NULL,
  `partassurance` int(5) NOT NULL,
  `remise` int(5) NOT NULL DEFAULT 0,
  `user_id` varchar(30) DEFAULT NULL,
  `num_hospit` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders_detail`
--

CREATE TABLE `orders_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders_item`
--

CREATE TABLE `orders_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pathologie`
--

CREATE TABLE `pathologie` (
  `codpat` char(5) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `typat` tinyint(4) NOT NULL,
  `patholourde` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `idenregistremetpatient` varchar(11) NOT NULL,
  `idenregistrementhopital` int(11) DEFAULT NULL,
  `numeroregistre` int(11) DEFAULT NULL,
  `dateenregistrement` datetime DEFAULT NULL,
  `civilite` int(2) NOT NULL DEFAULT 0,
  `nompatient` varchar(25) DEFAULT NULL,
  `prenomspatient` varchar(50) DEFAULT NULL,
  `nomprenomspatient` varchar(50) NOT NULL,
  `datenaispatient` date DEFAULT NULL,
  `sexe` varchar(15) DEFAULT NULL,
  `adressepatient` varchar(45) DEFAULT NULL,
  `assure` int(1) DEFAULT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `telpatient` varchar(12) DEFAULT NULL,
  `telpatient_2` varchar(12) DEFAULT NULL,
  `telurgence_1` varchar(12) DEFAULT NULL,
  `telurgence_2` varchar(12) DEFAULT NULL,
  `nomurgence` varchar(50) DEFAULT NULL,
  `lieuderesidencepat` varchar(225) DEFAULT NULL,
  `codefiliation` varchar(3) DEFAULT NULL,
  `matriculeassure` varchar(225) DEFAULT NULL,
  `codesocieteassure` int(10) DEFAULT NULL,
  `idtauxcouv` int(3) DEFAULT NULL,
  `codeproduit` varchar(12) DEFAULT NULL,
  `patient_photo` tinyint(1) NOT NULL DEFAULT 0,
  `photo` varchar(20) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pharmaprod_detailboncmd`
--

CREATE TABLE `pharmaprod_detailboncmd` (
  `id` int(11) NOT NULL,
  `numboncmd` varchar(15) NOT NULL,
  `codepharmaprod` varchar(15) NOT NULL,
  `qtecmd` int(5) NOT NULL,
  `pu` int(6) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `porte_caisses`
--

CREATE TABLE `porte_caisses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `montant` varchar(191) DEFAULT NULL,
  `statut` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prelevements`
--

CREATE TABLE `prelevements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prix` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestation_honoraire`
--

CREATE TABLE `prestation_honoraire` (
  `id` int(10) NOT NULL,
  `prestation` varchar(30) NOT NULL,
  `libelle_paiement` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit_assurance`
--

CREATE TABLE `produit_assurance` (
  `codeproduit` varchar(12) NOT NULL,
  `libelleproduit` varchar(50) NOT NULL,
  `codeassurance` varchar(12) NOT NULL,
  `codesocieteassure` int(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

CREATE TABLE `profile` (
  `idprofile` int(2) NOT NULL,
  `libprofile` text NOT NULL,
  `user_profil_permission` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `programmemedecins`
--

CREATE TABLE `programmemedecins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `periode` varchar(191) NOT NULL,
  `heure_debut` varchar(191) NOT NULL,
  `heure_fin` varchar(191) NOT NULL,
  `statut` varchar(191) NOT NULL,
  `codemedecin` varchar(191) NOT NULL,
  `jour_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rdvpatients`
--

CREATE TABLE `rdvpatients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `tel` varchar(191) NOT NULL,
  `motif` varchar(191) NOT NULL,
  `statut` varchar(191) NOT NULL,
  `codemedecin` varchar(191) NOT NULL,
  `patient_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `referentiel_biologie`
--

CREATE TABLE `referentiel_biologie` (
  `id` int(11) NOT NULL,
  `valeur_reference` varchar(10) NOT NULL,
  `unite` varchar(10) DEFAULT NULL,
  `sujet` varchar(30) NOT NULL,
  `parametre` varchar(250) NOT NULL,
  `famille_parametre` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

CREATE TABLE `rendez_vous` (
  `appointment_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `appointment_timestamp` date NOT NULL,
  `doctor_id` varchar(11) NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `code` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `type` longtext NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `societeassure`
--

CREATE TABLE `societeassure` (
  `codesocieteassure` int(11) NOT NULL,
  `nomsocieteassure` varchar(225) NOT NULL,
  `codeassurance` varchar(225) NOT NULL,
  `codeassureur` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soins_infirmier`
--

CREATE TABLE `soins_infirmier` (
  `code_soins` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `libelle_soins` varchar(100) NOT NULL,
  `code_typesoins` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soins_medicaux`
--

CREATE TABLE `soins_medicaux` (
  `id_soins` int(11) NOT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `codesocieteassure` varchar(225) DEFAULT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `taux_couverture` int(3) NOT NULL,
  `date_soin` datetime DEFAULT NULL,
  `montant_total` int(11) NOT NULL,
  `ticket_moderateur` int(11) NOT NULL,
  `part_assurance` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `codemedecin` varchar(225) DEFAULT NULL,
  `renseignement_clinique` varchar(225) DEFAULT NULL,
  `numfac_soins` varchar(12) NOT NULL,
  `paid_status` tinyint(1) NOT NULL DEFAULT 0,
  `numhospit` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `numbon` varchar(225) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soins_medicaux_itemmedics`
--

CREATE TABLE `soins_medicaux_itemmedics` (
  `id_detail_medics` int(11) NOT NULL,
  `id_soins` varchar(12) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `qte` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soins_medicaux_itemsoins`
--

CREATE TABLE `soins_medicaux_itemsoins` (
  `id_detail_soins` int(11) NOT NULL,
  `id_soins` varchar(12) NOT NULL,
  `code_soins` int(11) NOT NULL,
  `qte` int(11) NOT NULL,
  `libelle_soins` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specialitemed`
--

CREATE TABLE `specialitemed` (
  `codespecialitemed` varchar(20) NOT NULL COMMENT 'Code Spécialité',
  `nomspecialite` text NOT NULL COMMENT 'Nom Spécialité',
  `abrspecialite` varchar(50) NOT NULL COMMENT 'Abréviation Spécialité',
  `libellespecialite` text NOT NULL COMMENT 'Libellé Spécialtité',
  `dateenregistre` text NOT NULL COMMENT 'Date d''enregistrement',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

CREATE TABLE `tarifs` (
  `idtarif` int(11) NOT NULL,
  `codgaran` varchar(10) NOT NULL,
  `montjour` int(6) NOT NULL,
  `montnuit` int(6) NOT NULL,
  `montferie` int(6) NOT NULL,
  `codeassurance` varchar(10) NOT NULL,
  `forfait` tinyint(1) DEFAULT NULL,
  `codeproduit` varchar(12) DEFAULT ' ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tauxcouvertureassure`
--

CREATE TABLE `tauxcouvertureassure` (
  `idtauxcouv` int(11) NOT NULL,
  `valeurtaux` int(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `testlaboimagerie`
--

CREATE TABLE `testlaboimagerie` (
  `idtestlaboimagerie` varchar(12) NOT NULL,
  `codeassurance` varchar(225) DEFAULT NULL,
  `codesocieteassure` varchar(225) DEFAULT NULL,
  `idenregistremetpatient` varchar(11) NOT NULL,
  `codemedecin` varchar(225) DEFAULT NULL,
  `typedemande` varchar(20) NOT NULL,
  `renseigclini` varchar(225) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `heure` time NOT NULL,
  `numfacbul` varchar(10) NOT NULL,
  `numbon` varchar(20) DEFAULT NULL,
  `medicin_traitant` varchar(225) DEFAULT NULL,
  `numhospit` varchar(225) DEFAULT NULL,
  `prelevement` varchar(225) DEFAULT NULL,
  `mode_patient` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 : Assurance utilisée 1 : Assurance non utilisée	',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typededossier`
--

CREATE TABLE `typededossier` (
  `codetypedossier` varchar(5) NOT NULL,
  `nomtypedossier` varchar(25) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typehospitalsation`
--

CREATE TABLE `typehospitalsation` (
  `idtypehospit` varchar(5) NOT NULL,
  `nomtypehospit` varchar(25) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typeprestations`
--

CREATE TABLE `typeprestations` (
  `idtypeprestation` int(11) NOT NULL,
  `libelletypeprestation` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typesoinsinfirmiers`
--

CREATE TABLE `typesoinsinfirmiers` (
  `code_typesoins` int(11) NOT NULL,
  `libelle_typesoins` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typesoins_as`
--

CREATE TABLE `typesoins_as` (
  `code_typesoins` int(5) NOT NULL,
  `libelle_typesoins` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typgarantie`
--

CREATE TABLE `typgarantie` (
  `codtypgar` char(10) NOT NULL,
  `libtypgar` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_token` varchar(191) DEFAULT NULL,
  `login` varchar(191) NOT NULL,
  `user_first_name` varchar(191) NOT NULL,
  `user_last_name` varchar(191) NOT NULL,
  `tel` varchar(191) DEFAULT NULL,
  `user_profil_id` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `user_rights` varchar(191) DEFAULT NULL,
  `user_make_date` varchar(191) DEFAULT NULL,
  `user_revised_date` varchar(191) DEFAULT NULL,
  `user_ip` varchar(191) DEFAULT NULL,
  `user_history` varchar(191) DEFAULT NULL,
  `user_logs` varchar(191) DEFAULT NULL,
  `user_lang` varchar(191) DEFAULT NULL,
  `user_photo` varchar(191) DEFAULT NULL,
  `user_actif` varchar(191) DEFAULT NULL,
  `user_actions` varchar(191) DEFAULT NULL,
  `code_personnel` varchar(191) DEFAULT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actes_as`
--
ALTER TABLE `actes_as`
  ADD PRIMARY KEY (`code_acte`);

--
-- Index pour la table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`numhospit`);

--
-- Index pour la table `assurance`
--
ALTER TABLE `assurance`
  ADD PRIMARY KEY (`idassurance`);

--
-- Index pour la table `assureur`
--
ALTER TABLE `assureur`
  ADD PRIMARY KEY (`codeassureur`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`codeop`);

--
-- Index pour la table `caisse_resume`
--
ALTER TABLE `caisse_resume`
  ADD PRIMARY KEY (`idcaisse`);

--
-- Index pour la table `calendrier_medecin`
--
ALTER TABLE `calendrier_medecin`
  ADD PRIMARY KEY (`codecalendriermed`);

--
-- Index pour la table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  ADD PRIMARY KEY (`ca_code`);

--
-- Index pour la table `chambrehospit`
--
ALTER TABLE `chambrehospit`
  ADD PRIMARY KEY (`codechbre`);

--
-- Index pour la table `chambres`
--
ALTER TABLE `chambres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chambres_code_index` (`code`),
  ADD KEY `chambres_nbre_lit_index` (`nbre_lit`),
  ADD KEY `chambres_prix_index` (`prix`),
  ADD KEY `chambres_statut_index` (`statut`);

--
-- Index pour la table `civilite`
--
ALTER TABLE `civilite`
  ADD PRIMARY KEY (`code_civilite`);

--
-- Index pour la table `comptabilite_resume`
--
ALTER TABLE `comptabilite_resume`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comptes`
--
ALTER TABLE `comptes`
  ADD PRIMARY KEY (`numcpt`),
  ADD KEY `codemvt` (`codemvt`);

--
-- Index pour la table `constante`
--
ALTER TABLE `constante`
  ADD PRIMARY KEY (`numfac`);

--
-- Index pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`idconsexterne`);

--
-- Index pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD PRIMARY KEY (`code`);

--
-- Index pour la table `depotfactures`
--
ALTER TABLE `depotfactures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `detailtestlaboimagerie`
--
ALTER TABLE `detailtestlaboimagerie`
  ADD PRIMARY KEY (`iddetailtestlaboimagerie`);

--
-- Index pour la table `dossierpatient`
--
ALTER TABLE `dossierpatient`
  ADD PRIMARY KEY (`numdossier`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`matricule`);

--
-- Index pour la table `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`numexam`);

--
-- Index pour la table `facturation_chirurgie`
--
ALTER TABLE `facturation_chirurgie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `facturation_hospit`
--
ALTER TABLE `facturation_hospit`
  ADD PRIMARY KEY (`id_fachosp`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`numfac`);

--
-- Index pour la table `factures_tierspayant`
--
ALTER TABLE `factures_tierspayant`
  ADD PRIMARY KEY (`numfac_tp`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `famille_actes_biologie`
--
ALTER TABLE `famille_actes_biologie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `famille_examen`
--
ALTER TABLE `famille_examen`
  ADD PRIMARY KEY (`codfamexam`);

--
-- Index pour la table `filiation`
--
ALTER TABLE `filiation`
  ADD PRIMARY KEY (`codefiliation`);

--
-- Index pour la table `garantie`
--
ALTER TABLE `garantie`
  ADD PRIMARY KEY (`codgaran`);

--
-- Index pour la table `garanties_hospit`
--
ALTER TABLE `garanties_hospit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historiqueudfac`
--
ALTER TABLE `historiqueudfac`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_assurance`
--
ALTER TABLE `historique_assurance`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `honoraire`
--
ALTER TABLE `honoraire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numfac` (`numfac`);

--
-- Index pour la table `joursemaines`
--
ALTER TABLE `joursemaines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `joursemaines_jour_unique` (`jour`);

--
-- Index pour la table `lits`
--
ALTER TABLE `lits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lits_code_index` (`code`),
  ADD KEY `lits_type_index` (`type`),
  ADD KEY `lits_statut_index` (`statut`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`codemedecin`),
  ADD KEY `fk_doctordetails_doctorspecialization1_idx` (`codespecialitemed`);

--
-- Index pour la table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Index pour la table `medicine_category`
--
ALTER TABLE `medicine_category`
  ADD PRIMARY KEY (`medicine_category_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mouvements`
--
ALTER TABLE `mouvements`
  ADD PRIMARY KEY (`codemvt`);

--
-- Index pour la table `naturehospit`
--
ALTER TABLE `naturehospit`
  ADD PRIMARY KEY (`idnathospit`);

--
-- Index pour la table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`codoper`),
  ADD KEY `numcpt` (`numcpt`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders_detail`
--
ALTER TABLE `orders_detail`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `pathologie`
--
ALTER TABLE `pathologie`
  ADD PRIMARY KEY (`codpat`),
  ADD KEY `typat` (`typat`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`idenregistremetpatient`),
  ADD KEY `fk_patientregistration_hospitalregistration1_idx` (`idenregistrementhopital`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `pharmaprod_detailboncmd`
--
ALTER TABLE `pharmaprod_detailboncmd`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `porte_caisses`
--
ALTER TABLE `porte_caisses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `prelevements`
--
ALTER TABLE `prelevements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prelevements_code_unique` (`code`);

--
-- Index pour la table `prestation_honoraire`
--
ALTER TABLE `prestation_honoraire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produit_assurance`
--
ALTER TABLE `produit_assurance`
  ADD PRIMARY KEY (`codeproduit`);

--
-- Index pour la table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`idprofile`);

--
-- Index pour la table `programmemedecins`
--
ALTER TABLE `programmemedecins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rdvpatients`
--
ALTER TABLE `rdvpatients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rdvpatients_statut_index` (`statut`);

--
-- Index pour la table `referentiel_biologie`
--
ALTER TABLE `referentiel_biologie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`code`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Index pour la table `societeassure`
--
ALTER TABLE `societeassure`
  ADD PRIMARY KEY (`codesocieteassure`);

--
-- Index pour la table `soins_infirmier`
--
ALTER TABLE `soins_infirmier`
  ADD PRIMARY KEY (`code_soins`);

--
-- Index pour la table `soins_medicaux`
--
ALTER TABLE `soins_medicaux`
  ADD PRIMARY KEY (`id_soins`);

--
-- Index pour la table `soins_medicaux_itemmedics`
--
ALTER TABLE `soins_medicaux_itemmedics`
  ADD PRIMARY KEY (`id_detail_medics`);

--
-- Index pour la table `soins_medicaux_itemsoins`
--
ALTER TABLE `soins_medicaux_itemsoins`
  ADD PRIMARY KEY (`id_detail_soins`);

--
-- Index pour la table `specialitemed`
--
ALTER TABLE `specialitemed`
  ADD PRIMARY KEY (`codespecialitemed`);

--
-- Index pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`idtarif`);

--
-- Index pour la table `tauxcouvertureassure`
--
ALTER TABLE `tauxcouvertureassure`
  ADD PRIMARY KEY (`idtauxcouv`);

--
-- Index pour la table `testlaboimagerie`
--
ALTER TABLE `testlaboimagerie`
  ADD PRIMARY KEY (`idtestlaboimagerie`);

--
-- Index pour la table `typededossier`
--
ALTER TABLE `typededossier`
  ADD PRIMARY KEY (`codetypedossier`);

--
-- Index pour la table `typehospitalsation`
--
ALTER TABLE `typehospitalsation`
  ADD PRIMARY KEY (`idtypehospit`);

--
-- Index pour la table `typeprestations`
--
ALTER TABLE `typeprestations`
  ADD PRIMARY KEY (`idtypeprestation`);

--
-- Index pour la table `typesoinsinfirmiers`
--
ALTER TABLE `typesoinsinfirmiers`
  ADD PRIMARY KEY (`code_typesoins`);

--
-- Index pour la table `typesoins_as`
--
ALTER TABLE `typesoins_as`
  ADD PRIMARY KEY (`code_typesoins`);

--
-- Index pour la table `typgarantie`
--
ALTER TABLE `typgarantie`
  ADD PRIMARY KEY (`codtypgar`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_login_index` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actes_as`
--
ALTER TABLE `actes_as`
  MODIFY `code_acte` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `assurance`
--
ALTER TABLE `assurance`
  MODIFY `idassurance` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `assureur`
--
ALTER TABLE `assureur`
  MODIFY `codeassureur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `codeop` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caisse_resume`
--
ALTER TABLE `caisse_resume`
  MODIFY `idcaisse` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `calendrier_medecin`
--
ALTER TABLE `calendrier_medecin`
  MODIFY `codecalendriermed` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chambrehospit`
--
ALTER TABLE `chambrehospit`
  MODIFY `codechbre` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chambres`
--
ALTER TABLE `chambres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `civilite`
--
ALTER TABLE `civilite`
  MODIFY `code_civilite` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `comptabilite_resume`
--
ALTER TABLE `comptabilite_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `idconsexterne` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
  MODIFY `code` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Code du contrat';

--
-- AUTO_INCREMENT pour la table `depotfactures`
--
ALTER TABLE `depotfactures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `detailtestlaboimagerie`
--
ALTER TABLE `detailtestlaboimagerie`
  MODIFY `iddetailtestlaboimagerie` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facturation_chirurgie`
--
ALTER TABLE `facturation_chirurgie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facturation_hospit`
--
ALTER TABLE `facturation_hospit`
  MODIFY `id_fachosp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `garanties_hospit`
--
ALTER TABLE `garanties_hospit`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historiqueudfac`
--
ALTER TABLE `historiqueudfac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_assurance`
--
ALTER TABLE `historique_assurance`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `honoraire`
--
ALTER TABLE `honoraire`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `joursemaines`
--
ALTER TABLE `joursemaines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lits`
--
ALTER TABLE `lits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `medicine_category`
--
ALTER TABLE `medicine_category`
  MODIFY `medicine_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders_detail`
--
ALTER TABLE `orders_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pharmaprod_detailboncmd`
--
ALTER TABLE `pharmaprod_detailboncmd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `porte_caisses`
--
ALTER TABLE `porte_caisses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prelevements`
--
ALTER TABLE `prelevements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prestation_honoraire`
--
ALTER TABLE `prestation_honoraire`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profile`
--
ALTER TABLE `profile`
  MODIFY `idprofile` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `programmemedecins`
--
ALTER TABLE `programmemedecins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rdvpatients`
--
ALTER TABLE `rdvpatients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `referentiel_biologie`
--
ALTER TABLE `referentiel_biologie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `societeassure`
--
ALTER TABLE `societeassure`
  MODIFY `codesocieteassure` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `soins_infirmier`
--
ALTER TABLE `soins_infirmier`
  MODIFY `code_soins` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `soins_medicaux`
--
ALTER TABLE `soins_medicaux`
  MODIFY `id_soins` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `soins_medicaux_itemmedics`
--
ALTER TABLE `soins_medicaux_itemmedics`
  MODIFY `id_detail_medics` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `soins_medicaux_itemsoins`
--
ALTER TABLE `soins_medicaux_itemsoins`
  MODIFY `id_detail_soins` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tarifs`
--
ALTER TABLE `tarifs`
  MODIFY `idtarif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tauxcouvertureassure`
--
ALTER TABLE `tauxcouvertureassure`
  MODIFY `idtauxcouv` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `typeprestations`
--
ALTER TABLE `typeprestations`
  MODIFY `idtypeprestation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `typesoinsinfirmiers`
--
ALTER TABLE `typesoinsinfirmiers`
  MODIFY `code_typesoins` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `typesoins_as`
--
ALTER TABLE `typesoins_as`
  MODIFY `code_typesoins` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
