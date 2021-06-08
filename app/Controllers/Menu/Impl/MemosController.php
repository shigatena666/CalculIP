<?php

namespace App\Controllers\Menu\Impl;

use App\Controllers\Menu\MenuController;


class MemosController extends MenuController
{
    public function index(): string
    {
        $data = [
            "title" => "Memos",
            "menu_view" => view('templates/menu', [ "path_array" => $this->path_array ]),
        ];

        return view('Memos/memos', $data);
    }

    public function frameanalysis(): string
    {
        $data = [
            "title" => "Analyse de trame",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/frameanalysis', $data);
    }

    public function ipclasses(): string
    {
        $data = [
            "title" => "Classe de l'IP : Trouver la classe correspondante",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/ipclasses', $data);
    }

    public function framestructure(): string
    {
        $data = [
            "title" => "Structure de trame",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/framestructure', $data);
    }

    public function cidrnotation(): string
    {
        $data = [
            "title" => "CIDR et Masque",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/cidrnotation', $data);
    }

    public function routingtable(): string
    {
        $data = [
            "title" => "Table de routage",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/routingtable', $data);
    }

    public function subnetworks(): string
    {
        $data = [
            "title" => "Sous-réseaux",
            "menu_view" => view('templates/menu'),
        ];

        return view('Memos/subnetworks', $data);
    }
}