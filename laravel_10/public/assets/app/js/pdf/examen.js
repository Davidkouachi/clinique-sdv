$(document).ready(function () {

    window.pdfFactureExamen = function (facture, details) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "Examen Facture N°" + facture.numfac + " du " + formatDate(facture.date);
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

            const examenDate = new Date(facture.date);
            const formattedDate = examenDate.toLocaleDateString(); // Formater la date
            // const formattedTime = examenDate.toLocaleTimeString();
            doc.text("Date: " + examenDate.toLocaleDateString(), 15, (yPos + 25));
            doc.text("Heure: " + examenDate.toLocaleTimeString(), 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "FACTURE EXAMEN";
            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
            // Définir le padding
            const paddingh = 0; // Padding vertical
            const paddingw = 8; // Padding horizontal
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
            const titleN = "N° "+facture.numfac;
            doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier = facture.numdossier ? " N° Dossier : " + facture.numdossier : " N° Dossier : Aucun";
            const numDossierWidth = doc.getTextWidth(numDossier);
            doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            const numDossier2 = facture.id_patient ? " N° matricule : " + facture.id_patient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            yPoss = (yPos + 50);

            let assurer;

            if (facture.assure == 1) {
                assurer = 'Oui';
            } else {
                assurer = 'Non';
            }

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: facture.nom_patient.length > 25 
                        ? facture.nom_patient.substring(0, 25) + '...' 
                        : facture.nom_patient 
                },
                { label: "Assurer", value: assurer },
                { label: "Age", value: calculateAge(facture.datenais)+" an(s)" },
                { label: "Contact", value: facture.telpatient }
            ];

            if (facture.assure == 1) {
                patientInfo.push(
                    { 
                        label: "Société",
                        value: facture.societe.length > 25 
                        ? facture.societe.substring(0, 25) + '...' 
                        : facture.societe 
                    },
                    { 
                        label: "Assurance",
                        value: facture.assurance.length > 25 
                        ? facture.assurance.substring(0, 25) + '...' 
                        : facture.assurance 
                    },
                    { label: "Matricule assurance", value: facture.matricule },
                    { label: "N° de Bon", value: facture.numcode || 'Aucun' },
                );
            }

            patientInfo.push(
                { label: "libelle", value: facture.rensg == null || facture.rensg == '' ? 'Aucun' : facture.rensg },
            );

            patientInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 50);

            const typeInfo = [];

            if (facture.numcode && facture.numcode !== "" && facture.numcode !== null ) {
                typeInfo.push({ label: "N° prise en charge", value: facture.numcode == null ? 'Aucun' : facture.numcode });
            }

            let medecin; 

            if (facture.medecin !== null) {
                medecin = 'Dr. '+facture.medecin;
            } else {
                medecin = 'Non rensigné';
            }

            typeInfo.push(
                { label: "ID", value: facture.id },
                { 
                    label: "Medecin", 
                    value: medecin.length > 20 
                        ? medecin.substring(0, 20) + '...' 
                        : medecin 
                },
                { label: "N° Hospitalisation", value: facture.numhosp == null || facture.numhosp == '' ? 'Aucun' : facture.numhosp },
            );

            typeInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            yPoss += 30;

            const donneeTables = details;
            let yPossT = yPoss + 10; // Initialisation de la position Y pour le tableau des soins

            // Tableau dynamique pour les détails des soins infirmiers
            doc.autoTable({
                startY: yPossT,
                head: [['N°', 'Type', 'Examen', 'Montant', 'Prélevement', 'Prise en charge ?']],
                body: donneeTables.map((item, index) => [
                    index + 1,
                    item.code === 'B' ? 'ANALYSE' : 'IMAGERIE',  
                    item.examen,
                    formatPriceT(item.montant) + ' Fcfa',
                    formatPriceT(item.prelevement) + ' Fcfa',
                    parseInt(item.assurance) === 0 ? 'Non' : 'Oui',
                ]),
                foot: [[
                    { content: 'Totals', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
                    { content: formatPriceT(facture.montant_total) + " Fcfa", styles: { fontStyle: 'bold' } },
                ]],

                ...configTable([235, 99, 37])
            });

            yPoss = doc.autoTable.previous.finalY || yPossT + 10;
            yPoss = yPoss + 5;

            const compteInfo = [
                { label: "Montant Total", value: formatPriceT(facture.montant_total)+" Fcfa"},
                ...(facture.assure == 1 ? 
                        [{ label: "Part assurance", value: formatPriceT(facture.montant_assurance) + " Fcfa" }] 
                        : []),
            ];

            if (facture.assure == 1 ) {
                compteInfo.push({ label: "Taux", value: facture.taux + "%" });
            }

            compteInfo.push({ label: "Remise", value: formatPriceT(facture.remise) + " Fcfa" });

            compteInfo.forEach(info => {
                doc.setFontSize(9);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 110, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 142, yPoss);
                yPoss += 7;
            });

            doc.setFontSize(11);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant à payer', leftMargin + 110, yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": "+formatPriceT(facture.montant_patient)+" Fcfa", leftMargin + 142, yPoss);

        }

        drawConsultationSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
    }

    // window.pdfFactureRecuExamen = function (facture, details) 
    // {
    //     const { jsPDF } = window.jspdf;
    //     const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

    //     const pdfFilename = "Examen Facture N°" + facture.numfac + " du " + formatDate(facture.date);
    //     doc.setProperties({
    //         title: pdfFilename,
    //     });

    //     let yPos = 10;

    //     function drawConsultationSection(yPos) {
    //         rightMargin = 15;
    //         leftMargin = 15;
    //         pdfWidth = doc.internal.pageSize.getWidth();

    //         // --------------------------------------------

    //         tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

    //         // --------------------------------------------

    //         const examenDate = new Date(facture.date);
    //         const formattedDate = examenDate.toLocaleDateString(); // Formater la date
    //         // const formattedTime = examenDate.toLocaleTimeString();
    //         doc.text("Date: " + formattedDate, 15, (yPos + 25));
    //         doc.text("Heure: " + facture.heure, 15, (yPos + 30));

    //         //Ligne de séparation
    //         doc.setFontSize(15);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setLineWidth(0.5);
    //         doc.setTextColor(0, 0, 0);
    //         // doc.line(10, 35, 200, 35); 
    //         const titleR = "RECU DE PAIEMENT";
    //         const titleRWidth = doc.getTextWidth(titleR);
    //         const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
    //         // Définir le padding
    //         const paddingh = 0; // Padding vertical
    //         const paddingw = 8; // Padding horizontal
    //         // Calculer les dimensions du rectangle
    //         const rectX = titleRX - paddingw; // X du rectangle
    //         const rectY = (yPos + 18) - paddingh; // Y du rectangle
    //         const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
    //         const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
    //         // Définir la couleur pour le cadre (noir)
    //         doc.setDrawColor(0, 0, 0);
    //         doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
    //         // Ajouter le texte centré en gras
    //         doc.setFontSize(15);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setTextColor(0, 0, 0); // Couleur du texte rouge
    //         doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
    //         const titleN = "N° "+facture.numrecu;
    //         doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setTextColor(0, 0, 0);
    //         const numDossier = facture.numdossier ? " N° Dossier : " + facture.numdossier : " N° Dossier : Aucun";
    //         const numDossierWidth = doc.getTextWidth(numDossier);
    //         doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setTextColor(0, 0, 0);
    //         const numDossier2 = facture.id_patient ? " N° matricule : " + facture.id_patient  : " N° matricule : Aucun";
    //         const numDossierWidth2 = doc.getTextWidth(numDossier);
    //         doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setTextColor(0, 0, 0);
    //         const numDate = facture.numfac + " | Date de paiement : "+ formatDate(facture.datereglt_pat) ;
    //         const numDateWidth = doc.getTextWidth(numDate);
    //         doc.text(numDate, (doc.internal.pageSize.getWidth() - numDateWidth) / 2, yPos + 40); 

    //         yPoss = (yPos + 50);

    //         let assurer;

    //         if (facture.assure == 1) {
    //             assurer = 'Oui';
    //         } else {
    //             assurer = 'Non';
    //         }

    //         const patientInfo = [
    //             { 
    //                 label: "Nom et Prénoms", 
    //                 value: facture.nom_patient.length > 25 
    //                     ? facture.nom_patient.substring(0, 25) + '...' 
    //                     : facture.nom_patient 
    //             },
    //             { label: "Assurer", value: assurer },
    //             { label: "Age", value: calculateAge(facture.datenais)+" an(s)" },
    //             { label: "Contact", value: facture.telpatient }
    //         ];

    //         if (facture.assure == 1) {
    //             patientInfo.push(
    //                 { label: "Société", value: facture.societe },
    //                 { label: "Assurance", value: facture.assurance},
    //                 { label: "Matricule assurance", value: facture.matriculeassure },
    //                 { label: "N° de Bon", value: facture.numcode || 'Aucun' },
    //             );
    //         }

    //         patientInfo.push(
    //             { label: "libelle", value: facture.rensg || 'Aucun' },
    //         );

    //         patientInfo.forEach(info => {
    //             doc.setFontSize(9);
    //             doc.setFont("Helvetica", "bold");
    //             doc.text(info.label, leftMargin, yPoss);
    //             doc.setFont("Helvetica", "normal");
    //             doc.text(": " + info.value, leftMargin + 35, yPoss);
    //             yPoss += 7;
    //         });

    //         yPoss = (yPos + 50);

    //         const typeInfo = [];

    //         let medecin; 

    //         if (facture.medecin !== null) {
    //             medecin = 'Dr. '+facture.medecin;
    //         } else {
    //             medecin = 'Non rensigné';
    //         }

    //         typeInfo.push(
    //             { label: "ID", value: facture.id },
    //             { 
    //                 label: "Medecin", 
    //                 value: medecin.length > 20 
    //                     ? medecin.substring(0, 20) + '...' 
    //                     : medecin 
    //             }
    //         );

    //         typeInfo.forEach(info => {
    //             doc.setFontSize(9);
    //             doc.setFont("Helvetica", "bold");
    //             doc.text(info.label, leftMargin + 100, yPoss);
    //             doc.setFont("Helvetica", "normal");
    //             doc.text(": " + info.value, leftMargin + 135, yPoss);
    //             yPoss += 7;
    //         });

    //         // if (facture.assure == 1) {
    //         //     yPoss += 20;
    //         // }

    //         yPoss += 10;

    //         const compteInfo = [
    //             { label: "Prélevement", value: formatPriceT(facture.prelevement)+ " Fcfa" },
    //             { label: "Montant Total", value: formatPriceT(facture.montant)+" Fcfa"},
    //             ...(facture.assure == 1 ? 
    //                     [{ label: "Part assurance", value: formatPriceT(facture.part_assurance) + " Fcfa" }] 
    //                     : []),
    //         ];

    //         if (facture.assure == 1 ) {
    //             compteInfo.push({ label: "Taux", value: facture.taux + "%" });
    //         }

    //         compteInfo.push(
    //             { label: "Remise", value: formatPriceT(facture.remise) + " Fcfa" },
    //         );

    //         compteInfo.forEach(info => {
    //             doc.setFontSize(9);
    //             doc.setFont("Helvetica", "bold");
    //             doc.text(info.label, leftMargin + 100, yPoss);
    //             doc.setFont("Helvetica", "normal");
    //             doc.text(": " + info.value, leftMargin + 135, yPoss);
    //             yPoss += 7;
    //         });

    //         doc.setFontSize(11);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text('Montant à payer', leftMargin + 100, yPoss);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text(": "+formatPriceT(facture.part_patient)+" Fcfa", leftMargin + 135, yPoss);


    //         yPoss += 0;

    //         const payerInfo = [
    //             { label: "Montant Verser", value: (formatPriceT(facture.montant_verser) || '0')+" Fcfa" },
    //             { label: "Total Verser", value: (formatPriceT(facture.part_patient_regler) || '0')+" Fcfa" },
    //             // { label: "Montant Remis", value: (formatPriceT(facture.montant_remis) || '0')+" Fcfa" },
    //             { label: "Reste a payé", value: (formatPriceT(facture.montant_restant) || '0')+" Fcfa" },
    //         ];

    //         payerInfo.forEach(info => {
    //             doc.setFontSize(10);
    //             doc.setFont("Helvetica", "bold");
    //             doc.setTextColor(0, 0, 0);
    //             doc.text(info.label, leftMargin, yPoss);
    //             doc.setFont("Helvetica", "bold");
    //             doc.text(": " + info.value, leftMargin + 35, yPoss);
    //             yPoss += 7;
    //         });

    //         let textP = "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString(); 
                
    //         addpiedFooter(doc, 5, yPoss - 1, textP);
    //     }

    //     // addFooter(doc);

    //     drawConsultationSection(yPos);

    //     doc.setFontSize(30);
    //     doc.setLineWidth(0.5);
    //     doc.setLineDashPattern([3, 3], 0);
    //     doc.line(0, (yPos + 137), 300, (yPos + 137));
    //     doc.setLineDashPattern([], 0);

    //     drawConsultationSection(yPos + 150);

    //     doc.output('dataurlnewwindow');
    // }

});