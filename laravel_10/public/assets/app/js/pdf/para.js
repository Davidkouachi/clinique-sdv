$(document).ready(function () {

    window.titlePdf = "CLINIQUE MEDICALE SOURCE DE VIE";
    window.address = "ABIDJAN";
    window.phone = "Tél.: 27 33 71 41 04 - Cel.: 07 59 11 37 73";
    window.colorbase = "#0148b0";

    window.configTable = function (
        themeColor = [235, 99, 37], 
        font = 8, 
        footer = false, 
        bodyLength = 0
    ) {
        return {
            theme: 'striped',
            tableWidth: 'auto',

            styles: {
                fontSize: font,
                overflow: 'linebreak',
            },

            headStyles: {
                fillColor: themeColor,
                textColor: 255,
                fontStyle: 'bold',
            },

            bodyStyles: {
                fillColor: [255, 255, 255],
            },

            alternateRowStyles: {
                fillColor: [245, 245, 245],
            },

            didParseCell: function (data) {
                if (!footer) return;

                const lastRowIndex = bodyLength - 1;

                if (data.section === 'body' && data.row.index === lastRowIndex) {
                    data.cell.styles.fillColor = [224, 190, 0]; // background
                    data.cell.styles.textColor = 255;        // texte blanc
                    data.cell.styles.fontStyle = 'bold';
                }
            }
        }   
    };

    window.tetePdf = function (doc, yPos, rightMargin, leftMargin, pdfWidth) {

        // Texte en filigrane
        // doc.setFontSize(100);
        // doc.setTextColor(242, 237, 237);
        // doc.setFont("Helvetica", "bold");
        // doc.text(titleFac, pdfWidth / 2, yPos + 120, { align: 'center', angle: 40 });

        // Logo
        // const logoSrc = "https://espacemedicosociallapyramideducomplexe.net/amitie/public/assets/images/logo.jpg";
        const logoSrc = "http://127.0.0.1:8000/assets/images/logo.jpg";
        doc.addImage(logoSrc, 'JPEG', leftMargin, yPos - 7, 22, 22);

        // Nom entreprise
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        doc.setFont("Helvetica", "bold");
        doc.text(window.titlePdf, pdfWidth / 2, yPos, { align: "center" });

        // Adresse
        doc.setFont("Helvetica", "normal");
        doc.text(window.address, pdfWidth / 2, yPos + 5, { align: "center" });

        // Téléphone
        doc.text(window.phone, pdfWidth / 2, yPos + 10, { align: "center" });
    };

    window.piedPdf = function (doc, w, h, t = null) {
        const td = t ?? "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString();
        doc.setFontSize(8);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text(td , w, h);
    };

    window.addpiedFooter = function (doc, w, h, t = null) {
        // Add footer with current date and page number in X/Y format
        const pageCount = doc.internal.getNumberOfPages();

        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(0, 0, 0);
            const pageText = `Page ${i} sur ${pageCount}`;
            const pageTextWidth = doc.getTextWidth(pageText);
            const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
            const td = t ?? "Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString();
            doc.text(pageText, centerX, h);
            doc.text(td, w, h); // Left-aligned
        }
    }

    window.addFooter = function (doc) {
        // Add footer with current date and page number in X/Y format
        const pageCount = doc.internal.getNumberOfPages();
        const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(0, 0, 0);
            const pageText = `Page ${i} sur ${pageCount}`;
            const pageTextWidth = doc.getTextWidth(pageText);
            const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
            doc.text(pageText, centerX, footerY);
            doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
        }
    }

    // Etat --------------------------------------

    window.ensureSpace = function (doc, yPos, marginBottom = 30) {
        const pageHeight = doc.internal.pageSize.height;
        if (yPos + marginBottom > pageHeight) {
            doc.addPage();
            return 20; // marge haute
        }
        return yPos;
    }

    window.titleSpaceEtat = function (doc, yPoss, text, font = 14) {
        doc.setFontSize(font);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        const textWidth_c = doc.getTextWidth(text);
        const pageWidth_c = doc.internal.pageSize.getWidth();
        const centerX_c = (pageWidth_c - textWidth_c) / 2;
        doc.text(text, centerX_c, yPoss);
        const underlineY = yPoss + 2;
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(centerX_c, underlineY, centerX_c + textWidth_c, underlineY);
    }

    // --------------------------------------

});