<?php

namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;


class MemosController extends MenuController
{
    /**
     * This function represents the index.php of the memo section.
     */
    public function index(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Memos";
        return view('Memos/memos', $this->controller_data);
    }

    /**
     * This function represents the /Analyse endpoint of the memo section.
     */
    public function frameanalysis(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Analyse de trame";
        return view('Memos/frameanalysis', $this->controller_data);
    }

    /**
     * This function represents the /Classe endpoint of the memo section.
     */
    public function ipclasses(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Classe de l'IP : Trouver la classe correspondante";
        return view('Memos/ipclasses', $this->controller_data);
    }

    /**
     * This function represents the /Structure endpoint of the memo section.
     */
    public function framestructure(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Structure de trame";
        return view('Memos/framestructure', $this->controller_data);
    }

    /**
     * This function represents the /CIDR endpoint of the memo section.
     */
    public function cidrnotation(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "CIDR et Masque";
        return view('Memos/cidrnotation', $this->controller_data);
    }

    /**
     * This function represents the /Routage endpoint of the memo section.
     */
    public function routingtable(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Table de routage";
        return view('Memos/routingtable', $this->controller_data);
    }

    /**
     * This function represents the /SousReseaux endpoint of the memo section.
     */
    public function subnetworks(): string
    {
        $this->controller_data[parent::DATA_TITLE] = "Sous-réseaux";
        return view('Memos/subnetworks', $this->controller_data);
    }
}