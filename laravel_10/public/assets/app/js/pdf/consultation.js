$(document).ready(function () {

    window.pdfFactureConsultation = function (data) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "Consultation Facture N°" + data.numfac + " du " + formatDate(data.date);
        doc.setProperties({
            title: pdfFilename,
        });

        yPos = 10;

        function drawConsultationSection(doc, yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const consultationDate = new Date(data.date);
            const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
            const formattedTime = consultationDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 25));
            doc.text("Heure: " + formattedTime, 15, (yPos + 30));
            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "FACTURE DE CONSULTATION";
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
            const titleN = "N° "+ data.numfac;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = data.numdossier ? " N° Dossier : " + data.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = data.idenregistremetpatient ? " N° matricule : " + data.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            yPoss = (yPos + 50);

            let assurer;

            if (data.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: data.nom_patient.length > 25 
                        ? data.nom_patient.substring(0, 25) + '...' 
                        : data.nom_patient 
                },
                { label: "Assurer", value: assurer },
                { label: "Age", value: calculateAge(data.datenais)+" an(s)" },
                { label: "Contact", value: data.telpatient }
            ];

            if (data.assure == 1) {
                patientInfo.push(
                    { label: "Société", value: data.societe },
                    { label: "Assurance", value: data.assurance},
                    { label: "Matricule Assurance", value: data.matriculeassure },
                    { label: "N° de Bon", value: data.numbon || 'Aucun' },
                );
            }

            patientInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            if (data.part_patient_regler === (data.montant_verser + data.montant_restant)) {

                yPoss = (yPos + 109);

                const payerInfo = [
                    { label: "Total Verser", value: (formatPriceT(data.part_patient_regler) || '0')+" Fcfa" },
                    { label: "Montant Verser", value: (formatPriceT(data.montant_verser) || '0')+" Fcfa" },
                    { label: "Montant Remis", value: (formatPriceT(data.montant_remis) || '0')+" Fcfa" },
                    { label: "Reste a payé", value: (formatPriceT(data.montant_restant) || '0')+" Fcfa" },
                ];

                payerInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });
            }

            yPoss = (yPos + 50);

            const medecinInfo = [
                { label: "ID", value: data.idconsexterne},
                { 
                    label: "Medecin", 
                    value: data.nom_medecin.length > 20 
                        ? data.nom_medecin.substring(0, 20) + '...' 
                        : data.nom_medecin 
                },
                { label: "Spécialité", value: data.specialite },
                { label: "Prix Consultation", value: formatPriceT(data.montant)+" Fcfa" },
            ];

            medecinInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 90);

            const compteInfo = [
                { label: "Montant Total", value: formatPriceT(data.montant)+" Fcfa" },
                ...(data.assure == 1 
                    ? [
                        { label: "Part assurance", value: formatPriceT(data.partassurance)+" Fcfa" },
                        { label: "Taux", value: data.taux+" %" }
                      ] 
                    : []),
                { label: "Remise", value: formatPriceT(data.remise)+" Fcfa" },
            ];

            compteInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss += 1;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant à payer', leftMargin + 100, yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": "+formatPriceT(data.partpatient)+" Fcfa", leftMargin + 135, yPoss);

            if (data.assure == 1 ) {

                piedPdf(doc, 5, yPoss + 16);
            } else {

                piedPdf(doc, 5, yPoss + 30);
            }

        }

        drawConsultationSection(doc, yPos);

        doc.setFontSize(30);
        doc.setLineWidth(0.5);
        doc.setLineDashPattern([3, 3], 0);
        doc.line(0, (yPos + 137), 300, (yPos + 137));
        doc.setLineDashPattern([], 0);

        drawConsultationSection(doc, yPos + 150);


        doc.output('dataurlnewwindow');
    }

    window.pdfFicheConsultation = function (data) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const pdfFilename = "Fiche Consultation N°" + data.numfac + " du " + formatDate(data.date);
        doc.setProperties({
            title: pdfFilename,
        });

        yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const consultationDate = new Date(data.date);
            const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
            const formattedTime = consultationDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 25));
            doc.text("Heure: " + formattedTime, 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "FICHE DE CONSULTATION";
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
            const titleN = "N° "+ data.numfac;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = data.numdossier ? " N° Dossier : " + data.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = data.idenregistremetpatient ? " N° matricule : " + data.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            yPoss = (yPos + 45);

            let assurer;

            if (data.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { label: "Medecin", value: data.nom_medecin },
                { label: "Spécialité", value: data.specialite },
                { label: "Nom et Prénoms", value: data.nom_patient },
                { label: "Assurer", value: assurer},
                { label: "Age", value: calculateAge(data.datenais)+" an(s)" },
                { label: "Contact", value: data.telpatient },
            ];

            if (data.assure == 1) {
                patientInfo.push(
                    { label: "Société", value: data.societe },
                    { label: "Assurance", value: data.assurance },
                    { label: "Filiation", value: data.filiation },
                    { label: "Matricule Assurance", value: data.matriculeassure },
                );
            }

            patientInfo.forEach(info => {
                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 38, yPoss);
                yPoss += 7;
            });

            doc.setFontSize(30);
            doc.setLineWidth(0.5);
            doc.line(10, yPoss, 200, yPoss);

            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(255, 0, 0);
            const rendu = "Compte Rendu";
            const titleRr = doc.getTextWidth(rendu);
            const titlerendu = (doc.internal.pageSize.getWidth() - titleRr) / 2;
            doc.text(rendu, titlerendu, yPoss + 10);
            // Dessiner une ligne sous le texte pour le souligner
            const underlineY = yPoss + 12; // Ajustez cette valeur selon vos besoins
            doc.setDrawColor(255, 0, 0);
            doc.setLineWidth(0.5); // Épaisseur de la ligne
            doc.line(titlerendu, underlineY, titlerendu + titleRr, underlineY);

            yPoss += 25;
            hPoss = yPoss;
            doc.setTextColor(0, 0, 0);

            const pInfo1 = [
                { label: "BD", value: "..........." },
                { label: "BG", value: "..........." },
            ];

            pInfo1.forEach(info => {
                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 15, yPoss);
                yPoss += 10;
            });

            const pInfo2 = [
                { label: "PoulsD", value: "..........." },
                { label: "PoulsG", value: "..........." },
            ];

            pInfo2.forEach(info => {
                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 40, yPoss - 20);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 55, yPoss - 20);
                yPoss += 10;
            });

            const pInfo3 = [
                { label: "Temps", value: "..........." },
                { label: "Taille", value: "..........." },
            ];

            pInfo3.forEach(info => {
                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 75, yPoss - 40);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 90, yPoss - 40);
                yPoss += 10;
            });

            const pInfo4 = [
                { label: "Poids", value: "..........." },
                { label: "Glycemie", value: "..........." },
            ];

            pInfo4.forEach(info => {
                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 110, yPoss - 60);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 130, yPoss - 60);
                yPoss += 10;
            });

            hPoss += 25;

            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(225, 0, 0);
            const motif = "Motif";
            const titleRm = doc.getTextWidth(motif);
            const titlemotif = (doc.internal.pageSize.getWidth() - titleRm) / 2;
            doc.text(motif, titlemotif, hPoss);
            // Dessiner une ligne sous le texte pour le souligner
            const underlineYm = hPoss + 2; // Ajustez cette valeur selon vos besoins
            doc.setDrawColor(225, 0, 0);
            doc.setLineWidth(0.5); // Épaisseur de la ligne
            doc.line(titlemotif, underlineYm, titlemotif + titleRm, underlineYm);

            piedPdf(doc, 5, 295);
        }

        drawConsultationSection(yPos);

        doc.output('dataurlnewwindow');
    }

    window.pdfFactureRecuConsultation = function (data) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "CONSULTATION Facture N°" + data.numfac + " du " + formatDateHeure(data.date);
        doc.setProperties({
            title: pdfFilename,
        });

        yPos = 10;

        function drawConsultationSection(yPos) {
            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            const consultationDate = new Date(data.date);
            // Formatter la date et l'heure séparément
            const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
            const formattedTime = consultationDate.toLocaleTimeString();
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
            const titleN = "N° "+ data.numrecu;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = data.numdossier ? " N° Dossier : " + data.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = data.idenregistremetpatient ? " N° matricule : " + data.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDate = data.numfac + " | Date de paiement : "+ formatDate(data.datereglt_pat) ;
            const numDateWidth = doc.getTextWidth(numDate);
            doc.text(numDate, (doc.internal.pageSize.getWidth() - numDateWidth) / 2, yPos + 40);                   

            yPoss = (yPos + 50);

            let assurer;

            if (data.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: data.nom_patient.length > 25 
                        ? data.nom_patient.substring(0, 25) + '...' 
                        : data.nom_patient 
                },
                { label: "Assurer", value: assurer },
                { label: "Age", value: calculateAge(data.datenais)+" an(s)" },
                { label: "Contact", value: data.telpatient }
            ];

            if (data.assure == 1) {
                patientInfo.push(
                    { 
                        label: "Société",
                        value: data.societe.length > 25 
                        ? data.societe.substring(0, 25) + '...' 
                        : data.societe
                    },
                    { 
                        label: "Assurance",
                        value: data.assurance.length > 25 
                        ? data.assurance.substring(0, 25) + '...' 
                        : data.assurance
                    },
                    { label: "Matricule", value: data.matriculeassure },
                    { label: "N° de Bon", value: data.numbon || 'Aucun' },
                );
            }

            patientInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 109);

            const payerInfo = [
                { label: "Total Verser", value: (formatPriceT(data.part_patient_regler) || '0')+" Fcfa" },
                { label: "Montant Verser", value: (formatPriceT(data.montant_verser) || '0')+" Fcfa" },
                // { label: "Montant Remis", value: (formatPriceT(data.montant_remis) || '0')+" Fcfa" },
                { label: "Reste a payé", value: (formatPriceT(data.montant_restant) || '0')+" Fcfa" },
            ];

            payerInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 50);

            const medecinInfo = [
                { label: "ID", value: data.idconsexterne},
                { 
                    label: "Medecin", 
                    value: data.nom_medecin.length > 20 
                        ? data.nom_medecin.substring(0, 20) + '...' 
                        : data.nom_medecin 
                },
                { label: "Spécialité", value: data.specialite },
                { label: "Prix Consultation", value: formatPriceT(data.montant)+" Fcfa" },
            ];

            medecinInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 90);

            const compteInfo = [
                { label: "Montant Total", value: formatPriceT(data.montant)+" Fcfa" },
                ...(data.assure == 1 
                    ? [
                        { label: "Part assurance", value: formatPriceT(data.partassurance)+" Fcfa" },
                        { label: "Taux", value: data.taux+" %" }
                      ] 
                    : []),
                { label: "Remise", value: formatPriceT(data.remise)+" Fcfa" },
            ];

            compteInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss += 1;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant à payer', leftMargin + 100, yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": "+formatPriceT(data.part_patient)+" Fcfa", leftMargin + 135, yPoss);

            let textP = "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() + " ( NB: recu valable pour 15 jours uniquement pour la même consultation de la même pathologie )"; 
            if (data.assure == 1) {
                
                piedPdf(doc, 5, yPoss + 16, textP);
            } else {
                
                piedPdf(doc, 5, yPoss + 30, textP);
            }

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