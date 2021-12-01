# Windows

Notice de lancement projet Symfony avec Docker sous Windows.

## Installation WSL (Windows Subsystem for Linux)

1. Ouvrir un PowerShell en mode administrateur

2. Saisir la commande suivante :

```bash
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
```

3. Activation "Plateforme d'ordinateur visuel" pour WSL uniquement via la commande suivante :

```bash
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
```

4. Redémarrer l'ordinateur

5. Télécharger et installer le [package de mise à jour du noyau Linux](https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi)

6. Définir WSL comme version par défaut via la commande suivante (PowerShell admin) :

```bash
wsl --set-default-version 2
```
7. Télécharger et installer [Ubuntu 20.04 LTS](https://www.microsoft.com/store/apps/9n6svws3rx71) via le Microsoft Store

8. Lancer Ubuntu 20.04 LTS 

9. Attendre le message de bienvenue (la création d'un USER peut être demandée)

## Installation cmd MAKE via le package Choco

1. Ouvrir un PowerShell en mode administrateur

2. Installation de Choco via la commande suivante :

```bash
Set-ExecutionPolicy Bypass -Scope Process -Force; `
  iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))
```
3. Fermer et réouvrir un PowerShell admin

4. Installation de MAKE via la commande suivante :

```bash
choco install make
```
## Installation Docker

1. Télécharger et installer [Docker](https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe)

2. Lancer Docker

3. Si tout a été correctement installé précédemment, l'interface ci-dessous doit s'afficher à l'écran : 

![IHM](https://dz2cdn1.dzone.com/storage/temp/14641130-docker.png)

## Installation PhpStorm

1. Télécharger et installer [PhpStorm](https://download.jetbrains.com/webide/PhpStorm-2021.2.3.exe?_gl=1*1rs5jp6*_ga*NzUzOTk0NTg2LjE2MzgzNTc1MjA.*_ga_V0XZL7QHEB*MTYzODM3ODIzMS4yLjEuMTYzODM3ODI0NC4w&_ga=2.64923055.1660027449.1638357520-753994586.1638357520)

2. Installer les plugins "Git" et "Terminal"

## Lancement du projet 

1. Récupérer les fichiers du projet

2. Lancer le projet FrenchCSite sur PHPStorm et ouvrir l'onglet "Terminal" sur l'IDE (vérifier de bien être à la racine du projet)

3. Mettre en route les conteneurs docker avec la commande suivante :

```bash
sudo make up
```

4. Entrer dans le conteneur php via la commande suivante :

```bash
sudo make work
```

5. Récupérer les dépendances Symfony :

```bash
composer install
```

6. Se rendre à l'adresse localhost:8080, si l'interface ci-dessous s'affiche tout est parfait :

![IHM](https://buddy.works/guides/images/symfony/symfony-1.png)

## Annexe

Quitter le conteneur php :

```bash
exit
```

Relancer tout les conteneurs

```bash
sudo make restart
```


Quitter le conteneur php :

```bash
exit
```

Arrêter et supprimer tout les conteneurs :

```bash
sudo make clean
```

Arrêter tout les conteneurs :

```bash
sudo make stop
```

Supprimer tout les conteneurs :

```bash
sudo make delete
```

Idée Design

https://demos.creative-tim.com/material-kit/

https://www.creative-tim.com/learning-lab/bootstrap/alerts/material-kit
