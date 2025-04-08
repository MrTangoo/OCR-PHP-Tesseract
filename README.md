
# OCR PHP avec Tesseract

Ce projet permet d'utiliser **Tesseract OCR** dans un environnement PHP pour extraire du texte à partir d'une image ou d'un PDF. L'utilisateur peut télécharger une image / PDF, sélectionner la langue pour l'OCR, ainsi que choisir le mode de segmentation de la page (PSM - Page Segmentation Mode).

## Fonctionnalités

- **Téléversement d'image / PDF** : Permet de télécharger une image ou un PDF pour l'OCR.
- **Sélection de la langue** : Choix de la langue pour le traitement OCR.
- **Choix du mode PSM** : Sélection du mode de segmentation de la page pour améliorer l'extraction du texte.

## Prérequis

- PHP 7 ou version supérieure
- [Tesseract OCR](https://github.com/tesseract-ocr/tesseract) installé sur votre machine

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ton-user/ocr-php-tesseract.git
cd ocr-php-tesseract
```

### 2. Installer Tesseract

```bash
sudo apt-get install tesseract-ocr
```

### 3. Installer Imagick

```bash
sudo apt-get install php-imagick
```

### 4. Lancer le projet

Pour démarrer un serveur PHP local :

```bash
php -S localhost:8000
```

Cela lancera le serveur sur `http://localhost:8000`, où vous pourrez tester l'application.

### Exemples de modes PSM (Page Segmentation Mode)

Tesseract propose différents modes de segmentation de la page (PSM). Voici quelques modes utiles :

| PSM | Description                   |
|-----|-------------------------------|
| 3   | Automatique (par défaut)       |
| 4   | Colonne de texte               |
| 6   | Bloc de texte uniforme         |
| 7   | Ligne unique                   |
| 11  | Texte éparse (Sparse text)     |

Le choix du mode PSM peut améliorer la précision de l'OCR selon la structure du texte dans l'image.

Voici une documentation des différents modes :

https://pyimagesearch.com/2021/11/15/tesseract-page-segmentation-modes-psms-explained-how-to-improve-your-ocr-accuracy/

### Aide
```bash
tesseract --help-extra
```