<?php
class HomeController extends Controller
{

    public function index()
    {
        // Carga la vista de login (la crearemos en el siguiente paso)
        $this->view('landing');
    }

}