<?php
class HomeController extends Controller
{

    public function index()
    {
        // Carga la vista de login
        $this->view('landing');
    }

}