$(document).ready(function () {

    window.pdfFactureSoins = function (facture, soins, produits) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "SOINS AMBULATOIRE Facture N°" + facture.numfac + " du " + formatDate(facture.date);
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

            const spatientDate = new Date(facture.date);
            // Formatter la date et l'heure séparément
            const formattedDate = spatientDate.toLocaleDateString();
            // const formattedTime = spatientDate.toLocaleTimeString();
            doc.text("Date: " + formattedDate, 15, (yPos + 28));
            // doc.text("Heure: " + formattedTime, 15, (yPos + 30));

            //Ligne de séparation
            doc.setFontSize(15);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);
            // doc.line(10, 35, 200, 35); 
            const titleR = "FACTURE SOINS AMBULATOIRES";
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
            const numDossier2 = facture.idenregistremetpatient ? " N° matricule : " + facture.idenregistremetpatient  : " N° matricule : Aucun";
            const numDossierWidth2 = doc.getTextWidth(numDossier);
            doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

            yPoss = (yPos + 40);

            const patientInfo = [
                { 
                    label: "Nom et Prénoms", 
                    value: facture.nom_patient.length > 25 
                        ? facture.nom_patient.substring(0, 25) + '...' 
                        : facture.nom_patient 
                },
                { label: "Assurer", value: facture.assure === 1 ? "Oui" : "Non"  },
                { label: "Age", value: calculateAge(facture.datenais) + " Ans" },
                { label: "Contact", value: facture.telpatient }
            ];

            if (facture.assure == 1) {
                patientInfo.push(
                    { label: "Société", value: facture.societe },
                    { label: "Assurance", value: facture.assurance },
                    { label: "Matricule assurance", value: facture.matriculeassure },
                );
            }

            patientInfo.push(
                { label: "libelle", value: facture.renseigclini == null || facture.renseigclini == '' ? 'Aucun' : facture.renseigclini },
            );

            patientInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 35, yPoss);
                yPoss += 7;
            });

            yPoss = (yPos + 40);

            const typeInfo = [];

            typeInfo.push(
                { label: "ID", value: facture.id_soins },
                { label: "N° Hospitalisation", value: facture.numhospit == null || facture.numhospit == '' ? 'Aucun' : facture.numhospit },
                { label: "Nbre Soins Infirmiers", value: facture.nbre_soins },
                { label: "Nbre Produits Utilisés", value: facture.nbre_produits },
            );

            typeInfo.forEach(info => {
                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.text(info.label, leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 135, yPoss);
                yPoss += 7;
            });

            if (facture.assure == 1) {
                yPoss += 15;
            }

            const donneeTables = soins;
            let yPossT = yPoss + 15; 

            const totalGeneralSoins = donneeTables.reduce((total, item) => {

                const prix = parseInt(item.price) || 0;
                const qte = parseInt(item.qte) || 0;

                const montant = item.total != null
                    ? parseInt(item.total) || 0
                    : prix * qte;

                return total + montant;
            }, 0);

            doc.autoTable({
                startY: yPossT,
                head: [['N°', 'Soins', 'Prix Unitaire', 'quantité', 'total', 'prise en charge ?']],
                body: donneeTables.map((item, index) => [

                    index + 1,

                    item.name,

                    formatPriceT(item.price) + " Fcfa",

                    parseInt(item.qte) || 0,

                    item.total != null
                        ? formatPriceT(item.total) + " Fcfa"
                        : formatPriceT(
                            (parseInt(item.price) || 0) *
                            (parseInt(item.qte) || 0)
                        ) + " Fcfa",

                    item.assure === true
                        ? "Oui"
                        : "Non"

                ]),
                foot: [[
                    { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                    { content: formatPriceT(totalGeneralSoins) + " Fcfa", styles: { fontStyle: 'bold' } },
                ]],

                ...configTable([235, 99, 37])
            });


            if (produits.length > 0) {

                // Récupérer la position Y de la dernière ligne du tableau
                yPoss = doc.autoTable.previous.finalY || yPossT + 10;
                yPoss = yPoss + 5;
                
                const donneeTable = produits;
                yPossT = yPoss + 5; // Ajuster la position Y pour le tableau des produits

                const totalGeneralProduit = donneeTable.reduce((total, item) => {

                    const prix = parseInt(item.price) || 0;
                    const qte = parseInt(item.qte) || 0;

                    const montant = item.total != null
                        ? parseInt(item.total) || 0
                        : prix * qte;

                    return total + montant;
                }, 0);

                doc.autoTable({
                    startY: yPossT,
                    head: [['N°', 'Produit utilisé', 'Prix Unitaire', 'Quantité', 'Total', 'Prise en charge ?']],
                    body: donneeTable.map((item, index) => [

                    index + 1,

                    item.name,

                    formatPriceT(item.price) + " Fcfa",

                    parseInt(item.qte) || 0,

                    item.total != null
                        ? formatPriceT(item.total) + " Fcfa"
                        : formatPriceT(
                            (parseInt(item.price) || 0) *
                            (parseInt(item.qte) || 0)
                        ) + " Fcfa",

                    item.assure === true
                        ? "Oui"
                        : "Non"

                ]),
                    foot: [[
                        { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPriceT(totalGeneralProduit) + " Fcfa", styles: { fontStyle: 'bold' } },
                    ]],

                    ...configTable([235, 99, 37])
                });
            }

            // Position Y après le tableau des produits
            yPoss = doc.autoTable.previous.finalY || yPossT + 10;
            yPoss = yPoss + 10;

            const compteInfo = [
                { label: "Total", value: formatPriceT(facture.montant) + " Fcfa" },
                ...(facture.assure == 1 ? 
                    [{ label: "Part assurance", value: formatPriceT(facture.part_assurance) + " Fcfa" }] 
                    : []),
                { label: "Remise", value: formatPriceT(facture.remise) + " Fcfa" },
            ];


            if (facture.assure == 1) {
                compteInfo.push({ label: "Taux", value: facture.taux + "%" });
            }

            compteInfo.forEach(info => {
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
            doc.setFont("Helvetica", "bold");
            doc.text(": "+formatPriceT(facture.part_patient)+" Fcfa", leftMargin + 150, yPoss);

        }

        drawConsultationSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
    }

    // window.pdfFactureRecuSoins = function (patient, soins, produit) 
    // {
    //     const { jsPDF } = window.jspdf;
    //     const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

    //     const pdfFilename = "SOINS AMBULATOIRE Facture N°" + facture.numfac + " du " + formatDate(facture.date);
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

    //         const spatientDate = new Date(facture.date);
    //         // Formatter la date et l'heure séparément
    //         const formattedDate = spatientDate.toLocaleDateString();
    //         // const formattedTime = spatientDate.toLocaleTimeString();
    //         doc.text("Date: " + formattedDate, 15, (yPos + 28));
    //         // doc.text("Heure: " + formattedTime, 15, (yPos + 30));

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
    //         const titleN = "N° "+patient.numrecu;
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
    //         const numDossier2 = facture.idenregistremetpatient ? " N° matricule : " + facture.idenregistremetpatient  : " N° matricule : Aucun";
    //         const numDossierWidth2 = doc.getTextWidth(numDossier);
    //         doc.text(numDossier2, (pdfWidth - rightMargin - numDossierWidth2) + 5, yPos + 30);

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.setTextColor(0, 0, 0);
    //         const numDate = facture.numfac + " | Date de paiement : "+ formatDate(patient.datereglt_pat) ;
    //         const numDateWidth = doc.getTextWidth(numDate);
    //         doc.text(numDate, (doc.internal.pageSize.getWidth() - numDateWidth) / 2, yPos + 40);      

    //         yPoss = (yPos + 50);

    //         const patientInfo = [
    //             { 
    //                 label: "Nom et Prénoms", 
    //                 value: facture.nom_patient.length > 25 
    //                     ? facture.nom_patient.substring(0, 25) + '...' 
    //                     : facture.nom_patient 
    //             },
    //             { label: "Assurer", value: facture.assure === 1 ? "Oui" : "Non"  },
    //             { label: "Age", value: calculateAge(patient.datenais) + " Ans" },
    //             { label: "Contact", value: patient.telpatient }
    //         ];

    //         if (facture.assure == 1) {
    //             patientInfo.push(
    //                 { label: "Société", value: patient.assurance },
    //                 { label: "Assurance", value: patient.assurance },
    //                 { label: "Matricule assurance", value: patient.matriculeassure },
    //             );
    //         }

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

    //         typeInfo.push(
    //             { label: "ID", value: patient.id_soins },
    //             { label: "Nbre Soins Infirmiers", value: patient.nbre_soins },
    //             { label: "Nbre Produits Utilisés", value: patient.nbre_produits },
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

    //         yPoss += 5;

    //         const compteInfo = [
    //             { label: "Total", value: formatPriceT(patient.montant_total) + " Fcfa" },
    //             ...(facture.assure == 1 ? 
    //                 [{ label: "Part assurance", value: formatPriceT(patient.part_assurance) + " Fcfa" }] 
    //                 : []),
    //         ];


    //         if (facture.assure == 1) {
    //             compteInfo.push({ label: "Taux", value: patient.taux + "%" });
    //         }

    //         compteInfo.push({ label: "Remise", value: formatPriceT(patient.remise) + " Fcfa" });

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
    //         doc.text(": "+formatPriceT(patient.part_patient)+" Fcfa", leftMargin + 135, yPoss);

    //         yPoss += 5;

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text('Total Versé', leftMargin , yPoss);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text(": " + formatPriceT(patient.part_patient_regler) + " Fcfa", leftMargin + 40, yPoss);
    //         yPoss += 7;

    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text('Montant Versé', leftMargin , yPoss);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text(": " + formatPriceT(patient.montant_verser) + " Fcfa", leftMargin + 40, yPoss);
    //         yPoss += 7;

    //         // Display Reste à Payer
    //         doc.setFontSize(10);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text('Reste à Payer', leftMargin , yPoss);
    //         doc.setFont("Helvetica", "bold");
    //         doc.text(": " + formatPriceT(patient.montant_restant) + " Fcfa", leftMargin + 40, yPoss);

    //         let textP = "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString(); 
                
    //         addpiedFooter(doc, 5, yPoss + 10, textP);

    //     }

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