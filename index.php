<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if (file_exists('output/result.txt')) {
        unlink('output/result.txt'); // Supprime le fichier texte précédent
    }

    $uploadDir = 'uploads/';
    $filename = basename($_FILES['image']['name']);
    $filePath = $uploadDir . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filePath);

    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $imagePath = $filePath;

    // Si c'est un PDF, le convertir en image PNG
    if ($fileExtension === 'pdf') {
        try {
            $imagick = new Imagick();
            // haute résolution pour la conversion
            $imagick->setResolution(300, 300); 
            // Lecture de la première page
            $imagick->readImage($filePath . '[0]');
            $imagick->setImageFormat('png');
            $imagePath = $uploadDir . pathinfo($filename, PATHINFO_FILENAME) . '.png';
            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();
        } catch (Exception $e) {
            echo "<h2>Erreur avec Imagick :</h2><pre>{$e->getMessage()}</pre>";
            exit;
        }
    }

    $lang = $_POST['lang'] ?? 'eng';
    $psm = $_POST['psm'] ?? 3;
    $outputTextFile = 'output/result';

    // Commande pour exécuter Tesseract OCR
    $command = "tesseract " . escapeshellarg($imagePath) . " " . escapeshellarg($outputTextFile) . " -l $lang --psm $psm";
    exec($command . ' 2>&1', $output, $returnVar);

    // Suppression du fichier uploadé après traitement
    unlink($filePath);

    // Si le fichier était un PDF, supprimer aussi l'image PNG générée
    if ($fileExtension === 'pdf' && file_exists($imagePath)) {
        // Supprime l'image PNG générée depuis le PDF
        unlink($imagePath); 
    }

    // Affichage des erreurs Tesseract, si il y en a
    if ($returnVar !== 0) {
        echo "<h2>Erreur de Tesseract :</h2><pre>";
        foreach ($output as $line) {
            echo $line . "\n";
        }
        echo "</pre>";
    } else {
        // Si tout va bien, afficher le texte détecté
        $text = file_get_contents($outputTextFile . '.txt');
        echo "<h2>Texte détecté :</h2><pre>$text</pre>";
    }
}
?>

<h1>OCR avec Tesseract</h1>
<form method="POST" enctype="multipart/form-data">
    <label>Fichier (Image ou PDF) :</label><br>
    <input type="file" name="image" accept="image/*,.pdf" required><br><br>

    <label>Langue :</label><br>
    <select name="lang">
        <option value="eng">Anglais</option>
        <option value="fra">Français</option>
        <option value="deu">Allemand</option>
    </select><br><br>

    <label>Mode PSM :</label><br>
    <select name="psm">
        <option value="0">0 - Orientation et script detection</option>
        <option value="1">1 - Segmentation de page automatique avec OSD</option>
        <option value="2">2 - Segmentation automatique sans OSD/OCR</option>
        <option value="3" selected>3 - Auto (par défaut)</option>
        <option value="4">4 - Colonne de texte</option>
        <option value="5">5 - Texte unique avec mise en forme</option>
        <option value="6">6 - Bloc de texte uniforme</option>
        <option value="7">7 - Ligne unique</option>
        <option value="8">8 - Un seul mot</option>
        <option value="9">9 - Mot dans un rond</option>
        <option value="10">10 - Un seul caractère</option>
        <option value="11">11 - Sparse text</option>
    </select><br><br>

    <button type="submit">Lancer l'OCR</button>
</form>
