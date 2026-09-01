$(document).ready(function () {

    window.pdfFactureEmis = function (societes,assurance,month,year) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        const pdfFilename = "FACTURE EMISE - Période de " + genererNomMois(month) + ' ' + year;
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

            const titleR = "LISTE DES FACTURES PAR ASSURANCE : " + assurance.libelleassurance;
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

            yPoss = (yPos + 40);

            let grandTotalAssurance = 0;
            let grandTotalPatient = 0;
            let grandTotalMontant = 0;

            if (societes.length > 0) {
                societes.forEach((societe, indexSociete) => {
                    const fac_cons = societe.fac_cons || [];
                    const fac_exam = societe.fac_exam || [];
                    const fac_soinsam = societe.fac_soinsam || [];
                    const fac_hopital = societe.fac_hopital || [];

                    // Fusionner consultations, examens et soins ambulatoires dans un tableau unique
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
                        // Titre de la société
                        yPoss += 20;
                        doc.setFontSize(14);
                        doc.setFont("Helvetica", "bold");
                        doc.text("Société : " + societe.nomsocieteassure, 15, yPoss);
                        yPoss += 5;

                        // Calculer les totaux pour la société
                        const totalAssurance = fac_global.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                        const totalPatient = fac_global.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                        const totalMontant = fac_global.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                        // Ajouter les totaux de cette société aux grands totaux
                        grandTotalAssurance += totalAssurance;
                        grandTotalPatient += totalPatient;
                        grandTotalMontant += totalMontant;

                        // Générer le tableau unique pour consultations, examens et soins ambulatoires avec une ligne de pied
                        const bodyData = fac_global.map((item, index) => [
                            index + 1,
                            formatDate(item.created_at) || '',
                            item.num_bon || '',
                            item.patient || '',
                            item.acte,
                            formatPriceT(item.montant) + " Fcfa",
                            formatPriceT(item.part_assurance) + " Fcfa",
                            formatPriceT(item.part_patient) + " Fcfa",
                        ]);

                        // ➜ Ajout du TOTAL comme dernière ligne
                        bodyData.push([
                            {
                                content: 'Totals',
                                colSpan: 5,
                                styles: {
                                    halign: 'center',
                                    fontStyle: 'bold',
                                    fillColor: [224, 190, 0], // background
                                    textColor: 255             // texte blanc
                                }
                            },
                            {
                                content: formatPriceT(totalMontant) + " Fcfa",
                                styles: {
                                    fontStyle: 'bold',
                                    fillColor: [224, 190, 0],
                                    textColor: 255
                                }
                            },
                            {
                                content: formatPriceT(totalAssurance) + " Fcfa",
                                styles: {
                                    fontStyle: 'bold',
                                    fillColor: [224, 190, 0],
                                    textColor: 255
                                }
                            },
                            {
                                content: formatPriceT(totalPatient) + " Fcfa",
                                styles: {
                                    fontStyle: 'bold',
                                    fillColor: [224, 190, 0],
                                    textColor: 255
                                }
                            }
                        ]);
                        
                        doc.autoTable({
                            startY: yPoss,
                            head: [[
                                'N°',
                                'Date',
                                'Numéro de Bon',
                                'Patient',
                                'Acte effectué',
                                'Montant Total',
                                'Part Assurance',
                                'Part assuré'
                            ]],
                            body: bodyData,

                            ...configTable([235, 99, 37])
                        });


                        const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                        yPoss = finalY + 10;

                        if (indexSociete < societes.length - 1) {

                            if (yPoss + 50 > doc.internal.pageSize.height) {
                                doc.addPage();
                                yPoss = 10;
                            }
                        }
                        
                    }
                });

                const finalY = doc.autoTable.previous.finalY || yPoss + 20;
                yPoss = finalY + 20;

                if (yPoss + 40 > doc.internal.pageSize.height) {
                    doc.addPage();
                    yPoss = 20;
                }

                // Afficher les grands totaux sur cette page
                doc.setFontSize(14);
                doc.setFont("Helvetica", "bold");
                doc.text("TOTAL DES FACTURES", 15, yPoss);
                yPoss += 10;

                const grandTotalInfo = [
                    { label: "Total Assurance", value: formatPriceT(grandTotalAssurance) +" Fcfa" },
                    { label: "Total Patient", value: formatPriceT(grandTotalPatient) + " Fcfa" },
                    { label: "Montant Total", value: formatPriceT(grandTotalMontant) + " Fcfa" },
                ];

                // Afficher les grands totaux sur la nouvelle page
                grandTotalInfo.forEach(info => {
                    doc.setFontSize(11);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 50, yPoss);
                    yPoss += 7;
                });
            }

        }

        drawSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
    }

    window.pdfFactureEmisBordo = function (societes,assurance,month,year) 
    {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const pdfFilename = "BORDEREAUX DES FACTURES EMISES - Période de " + genererNomMois(month) + ' ' + year;
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

            const titleR = "BORDEREAUX PAR ASSURANCE : " + assurance.libelleassurance;
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
            doc.text(dateText, dateTextX, textY + 10);

            yPoss = (yPos + 50);

            if (societes.length > 0) {

                // Totals
                const totalAssurance = societes.reduce((sum, item) => sum + parseInt(item.total_assurance || 0), 0);
                const totalPatient = societes.reduce((sum, item) => sum + parseInt(item.total_patient || 0), 0);
                const totalMontant = societes.reduce((sum, item) => sum + parseInt(item.total_montant || 0), 0);

                let bodyData = societes.map((item, index) => ([
                    index + 1,
                    item.nomsocieteassure || '',
                    (formatPriceT(item.total_montant) || '') + " Fcfa",
                    (formatPriceT(item.total_assurance) || '') + " Fcfa",
                    (formatPriceT(item.total_patient) || '') + " Fcfa",
                ]));

                bodyData.push([
                    {
                        content: 'Totals',
                        colSpan: 2,
                        styles: {
                            halign: 'center',
                            fontStyle: 'bold',
                            fillColor: [224, 190, 0],
                            textColor: 255
                        }
                    },
                    {
                        content: formatPriceT(totalMontant) + " Fcfa",
                        styles: {
                            fontStyle: 'bold',
                            fillColor: [224, 190, 0],
                            textColor: 255
                        }
                    },
                    {
                        content: formatPriceT(totalAssurance) + " Fcfa",
                        styles: {
                            fontStyle: 'bold',
                            fillColor: [224, 190, 0],
                            textColor: 255
                        }
                    },
                    {
                        content: formatPriceT(totalPatient) + " Fcfa",
                        styles: {
                            fontStyle: 'bold',
                            fillColor: [224, 190, 0],
                            textColor: 255
                        }
                    }
                ]);

                doc.autoTable({
                    startY: yPoss,
                    head: [['N°', 'Société', 'Montant Total', 'Part Assurance', 'Part assuré']],
                    body: bodyData,
                    ...configTable([235, 99, 37])
                });

            }

        }

        drawSection(yPos);

        addFooter(doc);

        doc.output('dataurlnewwindow');
    }

});