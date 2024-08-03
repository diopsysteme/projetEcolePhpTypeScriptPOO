<?php
namespace Core;

use TCPDF;

class File
{
    private $file;
    private $fileName;
    private $uploadDir;
    private $defaultImageDir = "public/asset";
    private $defaultPdfDir ="public/pdf";

    

    public function upload($file, $fileName, $uploadDir = '')
    {
        $this->file = $file;
        $this->fileName = $fileName;
        $this->uploadDir = $uploadDir ?: $this->defaultImageDir; 

        if (isset($this->file) && $this->file["error"] === UPLOAD_ERR_OK) {
            $img_tmp = $this->file["tmp_name"];
            $img_name = $this->fileName;
            
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0777, true);
            }
            
            $target_file = $this->uploadDir . '/' . basename($img_name);
            if (move_uploaded_file($img_tmp, $target_file)) {
                return "The file " . htmlspecialchars(basename($img_name)) . " has been uploaded.";
            } else {
                return "Sorry, there was an error uploading your file.";
            }
        } else {
            return "No file was uploaded or there was an error with the file upload.";
        }
    }

    public function paymentReceipt($client, $dette, $paiement)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Company');
        $pdf->SetTitle('Reçu de Paiement');
        $pdf->SetSubject('Reçu de Paiement');
        $pdf->SetKeywords('TCPDF, PDF, receipt, payment');

        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);

        $pdf->AddPage();

        $html = '
        <h1 style="text-align: center; color: #007BFF;">Reçu de Paiement</h1>
        <br>
        <table border="1" cellpadding="5">
            <tr>
                <td><strong>Nom du Client :</strong></td>
                <td>' . $client->nom . ' ' . $client->prenom . '</td>
            </tr>
            <tr>
                <td><strong>Email :</strong></td>
                <td>' . $client->mail . '</td>
            </tr>
            <tr>
                <td><strong>Téléphone :</strong></td>
                <td>' . $client->telephone . '</td>
            </tr>
        </table>
        <br>
        <h2 style="color: #007BFF;">Détails de la Dette</h2>
        <p>Montant de la dette: ' . $dette->montant . ' €<br>Montant Total Verse: ' . $dette->amountPaid .'<br>Montant Restant: ' . $dette->montantRestant . '</p>
        <br>
        <h2 style="color: #007BFF;">Détails du Paiement</h2>
        <p>Montant payé: ' . $paiement->montant . ' €<br>Date du paiement: ' . $paiement->date . '</p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdfFileName = 'receipt_' . $client->id . '_' . time() . '.pdf';
        $pdfFilePath = $this->defaultPdfDir . '/' . $pdfFileName;

        if (!is_dir($this->defaultPdfDir)) {
            mkdir($this->defaultPdfDir, 777, true);
        }

        $pdf->Output($pdfFilePath, 'F');
        return $pdfFilePath;
    }
}
