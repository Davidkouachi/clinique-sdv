$(document).ready(function () {

    window.pdfFacturehos = function (hopital, prestation) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "HOSPITALISATION Facture N°" + hopital.numfachospit + " du " + formatDateHeure(hopital.created_at);
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const hopitalDate = new Date(hopital.created_at);
            // Formatter la date et l'heure séparément
            const formattedDate = hopitalDate.toLocaleDateString(); // Formater la date
            const formattedTime = hopitalDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 25));
            doc.text("Heure: " + formattedTime, 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "FACTURE HOSPITALISATION";
            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
            // Définir le padding
            const paddingh = 0; // Padding vertical
            const paddingw = 15; // Padding horizontal
            // Calculer les dimensions du rectangle
            const rectX = titleRX - paddingw; // X du rectangle
            const rectY = (yPos + 18) - paddingh; // Y du rectangle
            const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
            const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
            // Définir la couleur pour le cadre (noir)
            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
            // Ajouter le texte centré en gras
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0); // Couleur du texte rouge
            doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
            const titleN = "N° "+hopital.numfachospit;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = hopital.numdossier ? " N° Dossier : " + hopital.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = hopital.idenregistremetpatient ? " N° matricule : " + hopital.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            yPoss = (yPos + 40);

            let assurer;

            if (hopital.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: hopital.patient.length > 25 
                        ? hopital.patient.substring(0, 25) + '...' 
                        : hopital.patient 
                },
                { label: "Assurer", value: assurer },
                { label: "Age", value: calculateAge(hopital.datenais)+" an(s)" },
                { label: "Contact", value: hopital.telpatient }
            ];

            if (hopital.assure == 1) {
                patientInfo.push(
                    { label: "Société", value: hopital.societe },
                    { label: "Assurance", value: hopital.assurance},
                    { label: "Matricule Assurance", value: hopital.matriculeassure },
                    { label: "N° de Bon", value: hopital.numbon || 'Aucun' },
                );
            }

            patientInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 40);

            const medecinInfo = [];

            if (hopital.numbon && hopital.numbon !== null) {
                medecinInfo.push({ label: "N° prise en charge", value: hopital.numbon });
            }

            medecinInfo.push(
                { label: "ID", value: hopital.numhospit },
                { 
                    label: "Medecin", 
                    value: hopital.medecin.length > 20 
                        ? hopital.medecin.substring(0, 20) + '...' 
                        : hopital.medecin 
                },
                { label: "Spécialité", value: hopital.specialite },
                { label: "Date d'entrée le ", value: formatDate(hopital.dateentree) },
                { label: "Date de sortie prévu le ", value: formatDate(hopital.datesortie) },
                { label: "Nombre de jours ", value: calculateDaysBetween(hopital.dateentree, hopital.datesortie)+" Jour(s)" },
                { label: "Chambre Occupée", value: "CH-"+hopital.chambre_code },
                { label: "Lit Occupée", value: "LIT-"+hopital.lit_code+"/"+hopital.lit_type },
                { label: "Prix Chambre", value: formatPriceT(hopital.chambre_prix)+" Fcfa" },
            );

            medecinInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss = (yPoss - 7);

            const typeInfo = [
                { label: "Type d'admission", value: hopital.type_hospit },
                { label: "Nature d'admission", value: hopital.nature_hospit },
            ];

            typeInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPoss);

            const donneeTable = prestation;

            let totalGeneral = 0;
            let totalAssurance = 0; // Total Part Assurance
            let totalPatient = 0;

            if (donneeTable.length > 0) {
                yPossT = yPoss + 10;
                doc.autoTable({
                    startY: yPossT,
                    head: [['N°', 'Nom de la prestation', 'Montant Total', 'Part Assurance', 'Part Patient']],
                    body: donneeTable.map((item, index) => {
                        totalPatient += item.prix_pat || 0;
                        totalAssurance += item.prix_ass || 0;
                        totalGeneral += item.prix || 0;

                        return [
                            index + 1,
                            item.name, 
                            formatPriceT(item.prix) + " Fcfa",
                            formatPriceT(item.prix_ass) + " Fcfa",
                            formatPriceT(item.prix_pat) + " Fcfa"
                        ];
                    }),
                    foot: [
                        [
                            { content: 'Totals', colSpan: 2, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPriceT(totalGeneral) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPriceT(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPriceT(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                        ],
                    ],

                    ...configTable([235, 99, 37])
                });

                const finalY = doc.autoTable.previous.finalY || yPossT + 10;
                yPoss = finalY + 10;

                const finalInfo = [
                    { label: "Montant Total", value: formatPriceT(hopital.montant_total) + " Fcfa" },
                    ...(hopital.assure === 1 ? [{ label: "Part assurance", value: formatPriceT(hopital.montant_ass) + " Fcfa" }] : []),
                    { label: "Remise", value: formatPriceT(hopital.remise) + " Fcfa" },
                ];

                if (hopital.assure === 1) {
                    finalInfo.push({ label: "Taux", value: hopital.taux + "%" });
                }

                finalInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 110, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 150, yPoss);
                    yPoss += 7;
                });

                doc.setFontSize(11);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 110, yPoss);
                doc.text(": " + formatPriceT(hopital.montant_pat) + " Fcfa", leftMargin + 150, yPoss);
            } else {
                yPoss += 7;

                const finalInfo = [
                    { label: "Montant Total", value: formatPriceT(hopital.montant_total) + " Fcfa" },
                    ...(hopital.assure === 1 ? [{ label: "Part assurance", value: formatPriceT(hopital.montant_ass) + " Fcfa" }] : []),
                ];

                if (hopital.assure === 1) {
                    finalInfo.push({ label: "Taux", value: hopital.taux + "%" });
                }

                finalInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 110, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 150, yPoss);
                    yPoss += 7;
                });

                doc.setFontSize(11);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 110, yPoss);
                doc.text(": " + formatPriceT(hopital.montant_pat) + " Fcfa", leftMargin + 150, yPoss);
            }


        }

        drawConsultationSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
	}

	window.pdfFacturehosdetailProd = function (hopital, factureds) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "HOSPITALISATION Facture N°" + hopital.numfachospit + " du " + formatDateHeure(hopital.created_at);
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const hopitalDate = new Date(hopital.created_at);
            // Formatter la date et l'heure séparément
            const formattedDate = hopitalDate.toLocaleDateString(); // Formater la date
            const formattedTime = hopitalDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 25));
            doc.text("Heure: " + formattedTime, 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "LISTE DES PRODUITS";
            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
            // Définir le padding
            const paddingh = 0; // Padding vertical
            const paddingw = 15; // Padding horizontal
            // Calculer les dimensions du rectangle
            const rectX = titleRX - paddingw; // X du rectangle
            const rectY = (yPos + 18) - paddingh; // Y du rectangle
            const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
            const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
            // Définir la couleur pour le cadre (noir)
            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
            // Ajouter le texte centré en gras
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0); // Couleur du texte rouge
            doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
            const titleN = "N° "+hopital.numfachospit;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = hopital.numdossier ? " N° Dossier : " + hopital.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = hopital.idenregistremetpatient ? " N° matricule : " + hopital.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);     

            yPoss = (yPos + 45);

            let assurer;

            if (hopital.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [];

            patientInfo.push(
                { 
                    label: "Nom et Prénoms", 
                    value: hopital.patient.length > 25 
                        ? hopital.patient.substring(0, 25) + '...' 
                        : hopital.patient 
                },
                { label: "Assurer", value: assurer },
            );

            if (hopital.assure == 1) {
                patientInfo.push({ label: "Assurance", value: hopital.assurance });
            }

            patientInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 45);

            const medecinInfo = [];

            if (hopital.numbon && hopital.numbon !== null) {
                medecinInfo.push({ label: "N° prise en charge", value: hopital.numbon });
            }

            medecinInfo.push(
                { 
                    label: "Medecin", 
                    value: hopital.medecin.length > 20 
                        ? hopital.medecin.substring(0, 20) + '...' 
                        : hopital.medecin 
                },
                { label: "Spécialité", value: hopital.specialite },
            );

            medecinInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            const donneeTable = factureds;

            let totalGeneral = 0;

            yPossT = yPoss + 5;
            doc.autoTable({
                startY: yPossT,
                head: [['N°', 'Nom du produit', 'Assurance', 'Taux', 'Prix Unitaire', 'Quantité', 'Prix Total']],
                body: donneeTable.map((item, index) => {

                    const prixTotal = parseFloat(item.prix_t) || 0;
                    const prixUnitaire = parseFloat(item.prix_u) || 0;
                    const quantite = parseInt(item.quantite) || 0;

                    totalGeneral += prixTotal || 0;

                    const assu = item.partassurance > 0 ? `Oui` : `Non`;
                    const taux = item.partassurance > 0 ? item.taux : 0;

                    return [
                        index + 1,
                        item.name, 
                        assu,
                        taux + "%",
                        formatPriceT(prixUnitaire) + " Fcfa",
                        quantite,
                        formatPriceT(prixTotal) + " Fcfa"
                    ];
                }),
                foot: [
                    [
                        { content: 'Totals', colSpan: 6, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPriceT(totalGeneral) + " Fcfa", styles: { fontStyle: 'bold' } },
                    ],
                ],

                ...configTable([235, 99, 37])
            });


        }

        drawConsultationSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
	}

	window.pdfFacturehosList = function (data, date1, date2) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        const pdfFilename = "Liste des patients hospitalisés du " + date1 + " au " + date2;
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "LISTE DES PATIENTS HOSPITALISES";
            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
            // Définir le padding
            const paddingh = 0; // Padding vertical
            const paddingw = 15; // Padding horizontal
            // Calculer les dimensions du rectangle
            const rectX = titleRX - paddingw; // X du rectangle
            const rectY = (yPos + 18) - paddingh; // Y du rectangle
            const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
            const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
            // Définir la couleur pour le cadre (noir)
            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
            // Ajouter le texte centré en gras
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0); // Couleur du texte rouge
            doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
            const titleN = "du " + date1 + " au " + date2;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));     
            yPoss = (yPos + 40);

            const donneeTable = data;

            let totalGeneral = 0;

            yPossT = yPoss + 10;
            doc.autoTable({
                startY: yPossT,
                head: [['N°', 'Identifiant', 'Patient', 'Type', 'Nature', 'Date entrée', 'Date sortie', 'Nbre jours', 'Médecin', 'Statut', 'Montant', 'N° Facture']],
                body: donneeTable.map((item, index) => {

                    const prixTotal = parseFloat(item.montant_total) || 0;

                    totalGeneral += prixTotal || 0;

                    return [
                        index + 1,
                        item.numhospit,
                        item.patient,
                        item.type_hospit, 
                        item.nature_hospit,
                        formatDate(item.dateentree),
                        formatDate(item.datesortie),
                        item.nbredejrs,
                        item.medecin,
                        item.statut,
                        formatPriceT(item.montant_total) + " Fcfa",
                        item.numfachospit
                    ];
                }),
                foot: [
                    [
                        { content: 'Totals', colSpan: 10, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPriceT(totalGeneral) + " Fcfa", styles: { fontStyle: 'bold' } },
                        { content: '' },
                    ],
                ],

                ...configTable([235, 99, 37])
            });


        }

        drawConsultationSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
	}

    window.pdfFactureRecuhos = function (hopital, prestation) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "HOSPITALISATION Facture N°" + hopital.numfachospit + " du " + formatDateHeure(hopital.created_at);
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const hopitalDate = new Date(hopital.created_at);
            // Formatter la date et l'heure séparément
            const formattedDate = hopitalDate.toLocaleDateString(); // Formater la date
            const formattedTime = hopitalDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 25));
            doc.text("Heure: " + formattedTime, 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "RECU DE PAIEMENT";
            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
            // Définir le padding
            const paddingh = 0; // Padding vertical
            const paddingw = 15; // Padding horizontal
            // Calculer les dimensions du rectangle
            const rectX = titleRX - paddingw; // X du rectangle
            const rectY = (yPos + 18) - paddingh; // Y du rectangle
            const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
            const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
            // Définir la couleur pour le cadre (noir)
            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
            // Ajouter le texte centré en gras
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0); // Couleur du texte rouge
            doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
            const titleN = "N° "+hopital.numrecu;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = hopital.numdossier ? " N° Dossier : " + hopital.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = hopital.idenregistremetpatient ? " N° matricule : " + hopital.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDate = hopital.numfachospit +" | Date de paiement : "+ formatDate(hopital.datereglt_pat) ;
            const numDateWidth = doc.getTextWidth(numDate);
            doc.text(numDate, (doc.internal.pageSize.getWidth() - numDateWidth) / 2, yPos + 40); 

            yPoss = (yPos + 50);

            let assurer;

            if (hopital.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: hopital.patient.length > 25 
                        ? hopital.patient.substring(0, 25) + '...' 
                        : hopital.patient 
                },
                { label: "Assurer", value: assurer },
                { label: "Age", value: calculateAge(hopital.datenais)+" an(s)" },
                { label: "Contact", value: hopital.telpatient }
            ];

            if (hopital.assure == 1) {
                patientInfo.push(
                    { label: "Société", value:truncateText(hopital.societe, 20) },
                    { label: "Assurance", value: truncateText(hopital.assurance, 20) },
                    { label: "Matricule", value: hopital.matriculeassure },
                    { label: "N° de Bon", value: hopital.numbon || 'Aucun' },
                );
            }

            patientInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 50);

            const medecinInfo = [];

            if (hopital.numbon && hopital.numbon !== null) {
                medecinInfo.push({ label: "N° prise en charge", value: hopital.numbon });
            }

            medecinInfo.push(
                { label: "ID", value: hopital.numhospit },
                { 
                    label: "Medecin", 
                    value: truncateText(hopital.medecin, 20),
                },
                { label: "Nombre de jours ", value: calculateDaysBetween(hopital.dateentree, hopital.datesortie)+" Jour(s)" },
                { label: "Chambre Occupée", value: "CH-"+hopital.chambre_code },
                { label: "Lit Occupée", value: "LIT-"+hopital.lit_code+"/"+hopital.lit_type },
            );

            medecinInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss = (yPoss - 10);

            const donneeTable = prestation;

            let totalGeneral = 0;
            let totalAssurance = 0; // Total Part Assurance
            let totalPatient = 0;

            yPoss += 20;

            const finalInfo = [
                { label: "Montant Total", value: formatPriceT(hopital.montant_total) + " Fcfa" },
                ...(hopital.assure === 1 ? [{ label: "Part assurance", value: formatPriceT(hopital.montant_ass) + " Fcfa" }] : []),
            ];

            if (hopital.assure === 1) {
                finalInfo.push({ label: "Taux", value: hopital.taux + "%" });
            }

            finalInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            doc.setFontSize(11);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant à payer', leftMargin + 100, yPoss);
            doc.text(": " + formatPriceT(hopital.montant_pat) + " Fcfa", leftMargin + 135, yPoss);

            yPoss -= 7;

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Total Versé', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(hopital.part_patient_regler) + " Fcfa", leftMargin + 40, yPoss);
            yPoss += 7;

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant Versé', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(hopital.montant_verser) + " Fcfa", leftMargin + 40, yPoss);
            yPoss += 7;

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant Remis', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(hopital.montant_remis) + " Fcfa", leftMargin + 40, yPoss);
            yPoss += 7;

            // Display Reste à Payer
            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Reste à Payer', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(hopital.montant_restant) + " Fcfa", leftMargin + 40, yPoss);  

            let textP = "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString(); 
                
            addpiedFooter(doc, 5, yPoss + 6, textP);
        }

        drawConsultationSection(yPos);

        doc.setFontSize(30);
        doc.setLineWidth(0.5);
        doc.setLineDashPattern([3, 3], 0);
        doc.line(0, (yPos + 137), 300, (yPos + 137));
        doc.setLineDashPattern([], 0);

        drawConsultationSection(yPos + 150);

        doc.output('dataurlnewwindow');  
    }

});