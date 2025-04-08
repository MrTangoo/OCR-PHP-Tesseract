<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $imagePath = 'uploads/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

    $lang = $_POST['lang'] ?? 'eng';
    $psm = $_POST['psm'] ?? 3;

    $outputTextFile = 'output/result';
    $command = "tesseract $imagePath $outputTextFile -l $lang --psm $psm";

    exec($command . ' 2>&1', $output, $returnVar);
    
    $text = file_get_contents($outputTextFile . '.txt');
    echo "<h2>Texte détecté :</h2><pre>$text</pre>";
}
?>

<h1>OCR avec Tesseract</h1>
<form method="POST" enctype="multipart/form-data">
    <label>Image à lire :</label><br>
    <input type="file" name="image" required><br><br>

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
        <option value="2">2 - Segmentation de page automatique mais sans OSD ou OCR</option>
        <option value="3" selected>3 - Auto (par défaut)</option>
        <option value="4">4 - Colonne de texte</option>
        <option value="5">5 - Texte unique avec mise en forme</option>
        <option value="6">6 - Bloc de texte uniforme</option>
        <option value="7">7 - Ligne unique</option>
        <option value="8">8 - Traitement d'image avec un seul mot</option>
        <option value="9">9 - Traitement d'image avec un seul mot dans un rond</option>
        <option value="10">10 - Traitement d'image avec un seul charactère</option>
        <option value="11">11 - Sparse text</option>
    </select><br><br>

    <button type="submit">Lancer l'OCR</button>
</form>
