$(document).ready(function () {

	window.pdfEtatActe = function (acte_cons,acte_hop,acte_exam,acte_soinsam,date1,date2) {

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        const pdfFilename = "ACTE EFFECTUEE du" + formatDate(date1) + " au " + formatDate(date2);
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawSection(yPos) {

            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            // Définir le style pour le texte
            doc.setFontSize(12);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);

            let titleR;

            if (formatDate(date1) === formatDate(date2)) {
                titleR = "Actes éffectués le "+formatDate(date1);
            }else{
                titleR = "Actes éffectués du "+formatDate(date1)+" au "+formatDate(date2);
            }

            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

            const paddingh = 5;  // Ajuster le padding en hauteur
            const paddingw = 5;  // Ajuster le padding en largeur

            const rectX = titleRX - paddingw;
            let rectY = yPos + 15; // Position initiale du rectangle
            const rectWidth = titleRWidth + (paddingw * 2);
            const rectHeight = 2 + (paddingh * 2);

            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight);

            // Centrer le texte dans le rectangle
            const textY = rectY + (rectHeight / 2) + 2;  // Ajustement de la position Y du texte pour centrer verticalement
            doc.text(titleR, titleRX, textY);

            yPoss = (yPos + 40);
            
            let grandTotalAssurance = 0;
            let grandTotalPatient = 0;
            let grandTotalMontant = 0;

            if (acte_cons.length > 0) {

                yPoss = ensureSpace(doc, yPoss, 40);

                const text = "Consultations";

                titleSpaceEtat(doc, yPoss, text, 14);
                yPoss += 7;

                // Calculate totals
                const totalAssurance = acte_cons.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                const totalPatient = acte_cons.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                const totalMontant = acte_cons.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                grandTotalAssurance += totalAssurance;
                grandTotalPatient += totalPatient;
                grandTotalMontant += totalMontant;

                const bodyData = acte_cons.map((item, index) => [
				    index + 1,
				    item.patient || '',
				    item.assurance || 'Néant',
				    item.medecin || '',
				    item.specialite,
				    formatPriceT(item.montant) + " Fcfa" || '',
				    formatPriceT(item.part_assurance) + " Fcfa" || '',
				    formatPriceT(item.part_patient) + " Fcfa" || '',
				    formatDate(item.date) || '',
				]);

				bodyData.push([
				    { content: 'Totals', colSpan: 5, styles: { halign: 'center', fontStyle: 'bold' } },
				    formatPriceT(totalMontant) + " Fcfa",
				    formatPriceT(totalAssurance) + " Fcfa",
				    formatPriceT(totalPatient) + " Fcfa",
				    ''
				]);

				doc.autoTable({
				    startY: yPoss,
				    head: [['N°', 'Patient', 'Assurance', 'Médecin', 'Spécialité', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
				    body: bodyData,

				    ...configTable([235, 99, 37], 7, true, bodyData.length)
				});

                yPoss = doc.autoTable.previous.finalY + 12;
            }


            if (acte_hop.length > 0) {

            	yPoss = ensureSpace(doc, yPoss, 40);

            	const text = "Hospitalisations";

                titleSpaceEtat(doc, yPoss, text, 14);
                yPoss += 7;

                // Calculate totals
                const totalAssurance = acte_hop.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                const totalPatient = acte_hop.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                const totalMontant = acte_hop.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                grandTotalAssurance += totalAssurance;
                grandTotalPatient += totalPatient;
                grandTotalMontant += totalMontant;

                // Table with a footer row for totals
                const bodyData = acte_hop.map((item, index) => [
				    index + 1,
				    item.patient || '',
				    item.assurance || 'Néant',
				    formatPriceT(item.montant) + " Fcfa" || '',
				    formatPriceT(item.part_assurance) + " Fcfa" || '',
				    formatPriceT(item.part_patient) + " Fcfa" || '',
				    formatDate(item.created_at) || '',
				]);

				// 👉 Ajouter la ligne Totals à la fin du body
				bodyData.push([
				    { content: 'Totals', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
				    formatPriceT(totalMontant) + " Fcfa",
				    formatPriceT(totalAssurance) + " Fcfa",
				    formatPriceT(totalPatient) + " Fcfa",
				    ''
				]);

				doc.autoTable({
				    startY: yPoss,
				    head: [['N°', 'Patient', 'Assurance', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
				    body: bodyData,

				    ...configTable([235, 99, 37], 7, true, bodyData.length)
				});


                yPoss = doc.autoTable.previous.finalY + 12;
            }


            if (acte_exam.length > 0) {

            	yPoss = ensureSpace(doc, yPoss, 40);

            	const text = "Examens";

                titleSpaceEtat(doc, yPoss, text, 14);
                yPoss += 7;

                // Calculate totals
                const totalAssurance = acte_exam.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                const totalPatient = acte_exam.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                const totalMontant = acte_exam.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                grandTotalAssurance += totalAssurance;
                grandTotalPatient += totalPatient;
                grandTotalMontant += totalMontant;

                const bodyData = acte_cons.map((item, index) => [
				    index + 1,
                    item.patient || '',
                    item.assurance || 'Néant',
                    formatPriceT(item.montant) + " Fcfa" || '',
                    formatPriceT(item.part_assurance) + " Fcfa" || '',
                    formatPriceT(item.part_patient) + " Fcfa" || '',
                    formatDate(item.date) || '',
				]);

				bodyData.push([
				    { content: 'Totals', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
				    formatPriceT(totalMontant) + " Fcfa",
				    formatPriceT(totalAssurance) + " Fcfa",
				    formatPriceT(totalPatient) + " Fcfa",
				    ''
				]);

				doc.autoTable({
				    startY: yPoss,
				    head: [['N°', 'Patient', 'Assurance', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
				    body: bodyData,

				    ...configTable([235, 99, 37], 7, true, bodyData.length)
				});

                yPoss = doc.autoTable.previous.finalY + 12;
            }


            if (acte_soinsam.length > 0) {

            	yPoss = ensureSpace(doc, yPoss, 40);

            	const text = "Soins Ambulatoires";

                titleSpaceEtat(doc, yPoss, text, 14);
                yPoss += 7;

                // Calculate totals
                const totalAssurance = acte_soinsam.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                const totalPatient = acte_soinsam.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                const totalMontant = acte_soinsam.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                grandTotalAssurance += totalAssurance;
                grandTotalPatient += totalPatient;
                grandTotalMontant += totalMontant;

                const bodyData = acte_cons.map((item, index) => [
				    index + 1,
                    item.patient || '',
                    item.assurance || 'Néant',
                    formatPriceT(item.montant) + " Fcfa" || '',
                    formatPriceT(item.part_assurance) + " Fcfa" || '',
                    formatPriceT(item.part_patient) + " Fcfa" || '',
                    formatDate(item.date_soin) || '',
				]);

				bodyData.push([
				    { content: 'Totals', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
				    formatPriceT(totalMontant) + " Fcfa",
				    formatPriceT(totalAssurance) + " Fcfa",
				    formatPriceT(totalPatient) + " Fcfa",
				    ''
				]);

				doc.autoTable({
				    startY: yPoss,
				    head: [['N°', 'Patient', 'Assurance', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
				    body: bodyData,

				    ...configTable([235, 99, 37], 7, true, bodyData.length)
				});

                yPoss = doc.autoTable.previous.finalY + 12;
            }

            yPoss = ensureSpace(doc, yPoss, 50);

            doc.setFontSize(14);
            doc.setFont("Helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            doc.text("TOTAL DES ACTES", 15, yPoss);
            yPoss += 10;

            const grandTotalInfo = [
                { label: "Total Assurance", value: formatPriceT(grandTotalAssurance) + " Fcfa" },
                { label: "Total Patient", value: formatPriceT(grandTotalPatient) + " Fcfa" },
                { label: "Montant Total", value: formatPriceT(grandTotalMontant) + " Fcfa" },
            ];

            grandTotalInfo.forEach(info => {
                doc.setFontSize(11);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(info.label, leftMargin, yPoss);
                doc.setFont("Helvetica", "normal");
                doc.text(": " + info.value, leftMargin + 50, yPoss);
                yPoss += 7;
            });

        }

        drawSection(yPos);

        addFooter(doc);

        // doc.output('dataurlnewwindow');

        var blob = doc.output('blob');
        window.open(URL.createObjectURL(blob));
    }

    window.pdfEtatOpCaisse = function (trace,total,date1,date2) {
    	const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        let pdfFilename;

        if (formatDate(date1) === formatDate(date2)) {
            pdfFilename = "POINT DE OPERATION DE CAISSE du " + formatDate(date1);
        }else{
           pdfFilename = "POINT DE OPERATION DE CAISSE du " + formatDate(date1) + " au " + formatDate(date2); 
        }

        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawSection(yPos) {

            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            // Définir le style pour le texte
            doc.setFontSize(12);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);

            let titleR;

            if (formatDate(date1) === formatDate(date2)) {
                titleR = "Point des opérations caisse du "+formatDate(date1);
            }else{
                titleR = "Point des opérations caisse du "+formatDate(date1)+" au "+formatDate(date2);
            }

            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

            const paddingh = 5;  // Ajuster le padding en hauteur
            const paddingw = 5;  // Ajuster le padding en largeur

            const rectX = titleRX - paddingw;
            let rectY = yPos + 15; // Position initiale du rectangle
            const rectWidth = titleRWidth + (paddingw * 2);
            const rectHeight = 2 + (paddingh * 2);

            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight);

            // Centrer le texte dans le rectangle
            const textY = rectY + (rectHeight / 2) + 2;  // Ajustement de la position Y du texte pour centrer verticalement
            doc.text(titleR, titleRX, textY);

            yPoss = (yPos + 40);
                            
            // Calculate the total based on the transaction type
            const total = trace.reduce((sum, item) => {
                const montant = parseInt(item.montant || 0);
                return item.type.toLowerCase() === 'entree' ? sum + montant : sum - montant;
            }, 0);

            const totals = trace.reduce(
                (acc, item) => {
                    if (item.type === 'entree') acc.entree += item.montant;
                    if (item.type === 'sortie') acc.sortie += item.montant;
                    return acc;
                },
                { entree: 0, sortie: 0 }
            );

            // Table with a footer row for total
            doc.autoTable({
                startY: yPoss,
                head: [['N°', 'Type de mouvement', 'Motifs', 'Montant', 'Créer par', 'Date d\'opération', 'Date de création']],
                body: trace.map((item, index) => [
                    index + 1,
                    item.type.toUpperCase(),
                    item.libelle,
                    item.type == 'entree' ? '+ ' + formatPriceT(item.montant) + " Fcfa" : '- ' + formatPriceT(item.montant) + " Fcfa",
                    item.login,
                    formatDate(item.dateop),
                    formatDateHeure(item.datecreat) || '',
                ]),
                ...configTable([235, 99, 37], 7),
                didParseCell: function (data) {
                    // Check if the section is 'body'
                    if (data.section === 'body') {
                        // Apply color based on the value in column index 1
                        if (data.column.index === 3 || data.column.index === 1 ) { // Apply color to index 3
                            if (data.row.cells[1].raw.toLowerCase() === 'entree') {
                                data.cell.styles.textColor = [0, 128, 0]; // Green color
                            } else {
                                data.cell.styles.textColor = [255, 0, 0]; // Red color
                            }
                        }
                    }
                },
            });

            yPoss = doc.autoTable.previous.finalY || yPossT + 10;
            yPoss = yPoss + 10;

            if (yPoss + 30 > doc.internal.pageSize.height) {
                doc.addPage();
                yPoss = 20;
            }

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant Total', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(totals.entree) + " Fcfa", leftMargin + 40, yPoss);
            yPoss += 7;

            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant Total Entrée', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(total) + " Fcfa", leftMargin + 40, yPoss);
            yPoss += 7;

            // Display Reste à Payer
            doc.setFontSize(10);
            doc.setFont("Helvetica", "bold");
            doc.text('Montant Total Sortie', leftMargin , yPoss);
            doc.setFont("Helvetica", "bold");
            doc.text(": " + formatPriceT(totals.sortie) + " Fcfa", leftMargin + 40, yPoss);

        }

        drawSection(yPos);

        addFooter(doc);

        var blob = doc.output('blob');
        window.open(URL.createObjectURL(blob));
    }

    window.pdfEtatProdUtil = function (data,date1,date2) {

    	const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "Produits Pharmacie Utilisés du" + formatDate(date1) + " au " + formatDate(date2);
        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawSection(yPos) {

            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            // Définir le style pour le texte
            doc.setFontSize(12);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);

            let titleR;

            if (formatDate(date1) === formatDate(date2)) {
                titleR = "Produits Pharmacie Utilisés le "+formatDate(date1);
            }else{
                titleR = "Produits Pharmacie Utilisés du "+formatDate(date1)+" au "+formatDate(date2);
            }

            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

            const paddingh = 5;  // Ajuster le padding en hauteur
            const paddingw = 5;  // Ajuster le padding en largeur

            const rectX = titleRX - paddingw;
            let rectY = yPos + 20; // Position initiale du rectangle
            const rectWidth = titleRWidth + (paddingw * 2);
            const rectHeight = 2 + (paddingh * 2);

            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight);

            // Centrer le texte dans le rectangle
            const textY = rectY + (rectHeight / 2) + 2;  // Ajustement de la position Y du texte pour centrer verticalement
            doc.text(titleR, titleRX, textY);

            yPoss = (yPos + 40);
            
            let grandTotal = 0;

            if (data.length > 0) {

                // Trier les données par date croissante
                data.sort((a, b) => new Date(a.date) - new Date(b.date));

                // Calculate totals
                const total = data.reduce((sum, item) => sum + parseInt(item.total || 0), 0);

                grandTotal += total;

                const bodyData = data.map((item, index) => [
				    index + 1,
                    item.nom || '',
                    item.qte || '0',
                    (formatPriceT(item.prix) || '0') + " Fcfa",
                    (formatPriceT(item.total) || '0') + " Fcfa",
                    formatDateHeure(item.date) || '',
				]);

				bodyData.push([
				    { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
				    formatPriceT(grandTotal) + " Fcfa",
				    ''
				]);

				doc.autoTable({
				    startY: yPoss,
				    head: [['N°', 'Produit', 'Quantité', 'Prix unitaire', 'Total', 'Date']],
				    body: bodyData,

				    ...configTable([235, 99, 37], 7, true, bodyData.length)
				});

            }

        }

        drawSection(yPos);

        addFooter(doc);

        // doc.output('dataurlnewwindow');

        var blob = doc.output('blob');
        window.open(URL.createObjectURL(blob));
    }

    window.pdfEtatFacture = function (societes,assurance,month,year,type,m_total,m_assurance,m_patient) {

    	const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        let pdfFilename ;

        if (assurance === null || assurance === undefined) {

            if (type == 'tous') {
                pdfFilename = "Facture_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer') {
                pdfFilename = "Facture_Deposer_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer_regler') {
                pdfFilename = "Facture_Deposer_Regler_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer_non_regler') {
                pdfFilename = "Facture_Deposer_Non_Regler_de_" + genererNomMois(month) + '_' + year;
            }
        } else {
            pdfFilename = assurance.libelleassurance + "_facture_de_" + genererNomMois(month) + '_' + year;
            if (type == 'tous') {
                pdfFilename = assurance.libelleassurance +  "_facture_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer') {
                pdfFilename = assurance.libelleassurance +  "_facture_Deposer_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer_regler') {
                pdfFilename = assurance.libelleassurance +  "_facture_Deposer_Regler_de_" + genererNomMois(month) + '_' + year;
            } else if (type == 'fac_deposer_non_regler') {
                pdfFilename = assurance.libelleassurance +  "_facture_Deposer_Non_Regler_de_" + genererNomMois(month) + '_' + year;
            }
        }

        doc.setProperties({
            title: pdfFilename,
        });

        let yPos = 10;

        function drawSection(yPos) {

            rightMargin = 15;
            leftMargin = 15;
            pdfWidth = doc.internal.pageSize.getWidth();

            // --------------------------------------------

            tetePdf(doc, yPos, rightMargin, leftMargin, pdfWidth);

            // --------------------------------------------

            // Définir le style pour le texte
            doc.setFontSize(12);
            doc.setFont("Helvetica", "bold");
            doc.setLineWidth(0.5);
            doc.setTextColor(0, 0, 0);

            let titleR;

            if (assurance === null || assurance === undefined) {
                if (type == "tous") {
                    titleR = "LISTE DES FACTURES PAR PERIODE";
                } else if (type == "fac_deposer") {
                    titleR = "LISTE DES FACTURES DEPOSER PAR PERIODE";
                } else if (type == "fac_deposer_regler") {
                    titleR = "LISTE DES FACTURES DEPOSER & REGLER PAR PERIODE";
                } else if (type == "fac_deposer_non_regler") {
                    titleR = "LISTE DES FACTURES DEPOSER & NON-REGLER PAR PERIODE";
                } else if (type == "fac_regler_non_regler") {
                    titleR = "LISTE DES FACTURES REGLER & NON-REGLER PAR PERIODE";
                }
            } else {
                if (type == "tous") {
                    titleR = "LISTE DES FACTURES PAR ASSURANCE : " + assurance.libelleassurance;
                } else if (type == "fac_deposer") {
                    titleR = "LISTE DES FACTURES DEPOSER PAR ASSURANCE : " + assurance.libelleassurance;
                } else if (type == "fac_deposer_regler") {
                    titleR = "LISTE DES FACTURES DEPOSER & REGLER PAR ASSURANCE : " + assurance.libelleassurance;
                } else if (type == "fac_deposer_non_regler") {
                    titleR = "LISTE DES FACTURES DEPOSER & NON-REGLER PAR ASSURANCE : " + assurance.libelleassurance;
                } else if (type == "fac_regler_non_regler") {
                    titleR = "LISTE DES FACTURES REGLER & NON-REGLER PAR ASSURANCE : " + assurance.libelleassurance;
                }
            }

            const titleRWidth = doc.getTextWidth(titleR);
            const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

            const paddingh = 5;  // Ajuster le padding en hauteur
            const paddingw = 5;  // Ajuster le padding en largeur

            const rectX = titleRX - paddingw;
            let rectY = yPos + 18; // Position initiale du rectangle
            const rectWidth = titleRWidth + (paddingw * 2);
            const rectHeight = 15 + (paddingh * 2);

            doc.setDrawColor(0, 0, 0);
            doc.rect(rectX, rectY, rectWidth, rectHeight);

            // Centrer le texte dans le rectangle
            const textY = rectY + (rectHeight / 2) - 2;  // Ajustement de la position Y du texte pour centrer verticalement
            doc.text(titleR, titleRX, textY);

            // Ajout de la date sous le titre avec un saut de ligne
            const dateText = "Période de " + genererNomMois(month) + ' ' + year; // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
            const dateTextWidth = doc.getTextWidth(dateText);
            const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date

            // Positionner la date sous le rectangle
            doc.text(dateText, dateTextX, textY + 10);  // Ajuster `+ 10` selon l'espace souhaité entre le titre et la date

            yPoss = (yPos + 60);

            if (societes.length > 0) {
                societes.forEach((societe, index) => {

                    const fac_cons = societe.consultation || [];
                    const fac_exam = societe.testlaboimagerie || [];
                    const fac_soinsam = societe.soins_medicaux || [];
                    const fac_hopital = societe.admission || [];

                    const fac_global = [
                        ...fac_cons.map(item => ({
                            ...item,
                            acte: 'Consultation',
                        })),
                        ...fac_exam.map(item => ({
                            ...item,
                            acte: 'Examen',
                        })),
                        ...fac_soinsam.map(item => ({
                            ...item,
                            acte: 'Soins Ambulatoire',
                        })),
                        ...fac_hopital.map(item => ({
                            ...item,
                            acte: 'Hospitalisation',
                        })),
                    ];

                    if (fac_global.length > 0) {

                    	yPoss = ensureSpace(doc, yPoss, 60);

                        doc.setFontSize(12);
                        doc.setFont("Helvetica", "bold");
                        doc.text("Société : " + (societe.nomsocieteassure || "Inconnue"), 15, yPoss);
                        yPoss += 7;

                        let totalAssurance = fac_global.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                        let totalPatient = fac_global.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                        let totalMontant = fac_global.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                        const bodyData = fac_global.map((item, index) => [
						    index + 1,
                            formatDate(item.created_at) || '',
                            item.num_bon || '',
                            item.patient || '',
                            item.assurance || '',
                            item.societe || '',
                            item.acte || '',
                            (formatPriceT(item.montant) || '0') + " Fcfa",
                            (formatPriceT(item.part_assurance) || '0') + " Fcfa",
                            (formatPriceT(item.part_patient) || '0') + " Fcfa",
						]);

						bodyData.push([
						    { content: 'Totals', colSpan: 7, styles: { halign: 'center', fontStyle: 'bold' } },
						    formatPriceT(totalMontant) + " Fcfa",
						    formatPriceT(totalAssurance) + " Fcfa",
						    formatPriceT(totalPatient) + " Fcfa",
						    ''
						]);

						doc.autoTable({
						    startY: yPoss,
						    head: [['N°', 'Date', 'Numéro de Bon', 'Patient', 'Assurance', 'Société', 'Acte effectué', 'Montant Total', 'Part Assurance', 'Part assuré']],
						    body: bodyData,

						    ...configTable([235, 99, 37], 7, true, bodyData.length)
						});

                        yPoss = doc.autoTable.previous.finalY + 12;
                    }

                });

                yPoss = ensureSpace(doc, yPoss, 50);

                doc.setFontSize(14);
                doc.setFont("Helvetica", "bold");
                doc.text("TOTAL DES FACTURES", 15, yPoss);
                yPoss += 10;

                const grandTotalInfo = [
                    { label: "Total Assurance", value: (formatPriceT(m_assurance) || '0') +" Fcfa" },
                    { label: "Total Patient", value: (formatPriceT(m_patient) || '0' ) + " Fcfa" },
                    { label: "Montant Total", value: (formatPriceT(m_total) || '0' ) + " Fcfa" },
                ];

                grandTotalInfo.forEach((info, index) => {
                    doc.text(info.label + " : " + info.value, 15, yPoss + (index * 8));
                });
            }

        }

        drawSection(yPos);

        addFooter(doc);

        var blob = doc.output('blob');
        var blobURL = URL.createObjectURL(blob);
        window.open(blobURL);
    }

});