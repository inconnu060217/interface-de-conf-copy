<?php

namespace App\Services\logErrors;
use App\Services\logErrors\ILogErrorService;

class LogErrorService implements ILogErrorService
{
    const ERROR = 1;
    const WARNING = 2;
    const DEBUG1 = 3;
    const DEBUG2 = 4;
    const DEBUG3 = 5;

    protected $niveauTraces = "";
    protected $nomFichierTraces = "";
    private $directory;
    protected $extensionFichier = "_logError.log";
    protected $_fichierTraces = false;

    public function __construct($tracesNiveau = "error", ){
        $this->directory = storage_path('app/traces');

        // Adaptation pour les anciennes versions :
        if ($tracesNiveau == "info") {
            $tracesNiveau = "debug1";
        }

        if ($tracesNiveau == "debug") {
            $tracesNiveau = "debug2";
        }

        // Association des différents niveaux :
        switch ($tracesNiveau) {
            case "debug3":
                $this->niveauTraces = self::DEBUG3;
                break;
            case "debug2":
                $this->niveauTraces = self::DEBUG2;
                break;
            case "debug1":
                $this->niveauTraces = self::DEBUG1;
                break;
            case "warning":
                $this->niveauTraces = self::WARNING;
                break;
            case "error":
            default:
                $this->niveauTraces = self::ERROR;
                break;
        }

        if (strlen($this->directory)) {
            $this->directory;
            $this->nomFichierTraces = $this->directory . "/" . date("Ymd") . $this->extensionFichier;
        } else {
            $this->nomFichierTraces = $this->directory . "/" . date("Ymd") . $this->extensionFichier;
        }
    }
    public function ecrireTrace($message, $gravite = "debug", $sourcePhp = "", $callRef = 0){
        $tmp = explode("\\", $sourcePhp);
        $file = $tmp[count($tmp) - 1];
        $enregistrerMessage = true;

        switch ($gravite) {
            case "debug3":
                if ($this->niveauTraces < self::DEBUG3) {
                    $enregistrerMessage = false;
                }
                break;
            case "debug2":
                if ($this->niveauTraces < self::DEBUG2) {
                    $enregistrerMessage = false;
                }
                break;
            case "debug1":
                if ($this->niveauTraces < self::DEBUG1) {
                    $enregistrerMessage = false;
                }
                break;
            case "warning":
                if ($this->niveauTraces < self::WARNING) {
                    $enregistrerMessage = false;
                }
                break;
        }

        if ($enregistrerMessage) {
            if ($this->_fichierTraces === false) {
                @chmod($this->nomFichierTraces, 0777);
                $this->_fichierTraces = fopen($this->nomFichierTraces, "a");
                @chmod($this->nomFichierTraces, 0777);
            }

            // Ligne qui va être écrite dans le fichier traces :
            $ligneTexte = sprintf("%s | C%08d | %-7s | %-30s | %s%s",date("Y/m/d-H:i:s"),$callRef,strtoupper($gravite),$file,$message,PHP_EOL);

            fwrite($this->_fichierTraces, $ligneTexte);

            if ($gravite == 'error') {
                // Faites quelque chose avec le message d'erreur, si nécessaire
            }
        }
    }

    // Déclaration de la méthode de fermeture :
    public function fermerTraces() {
        if (false != $this->_fichierTraces) {
            fclose($this->_fichierTraces);
        }
    }

    public function deleteOldFiles ($nbDays, $sourcePhp = "", $callRef = 0) {
        $nbSec =  $nbDays * 86400;
        
        $this->ecrireTrace ("Demande de suppression des traces > $nbDays jours", "debug1", $sourcePhp, $callRef);
        $sc = scandir($this->directory);
        $old = getcwd(); // Save the current directory
        chdir($this->directory);
        foreach($sc as $file) {
            if(!is_dir($file) && ((filemtime($file) + $nbSec) < time()))  {
                $this->ecrireTrace ("Suppression de $file", "debug2", $sourcePhp, $callRef);
                @unlink($file);
            }

        }
        chdir($old); // Restore the old working directory
    }
}