<?php

class Controller {
    // Método para cargar vistas y pasarle datos
    protected function view($path, $data = []) {
        // Convierte el array asociativo en variables: ['titulo' => 'Hola'] se vuelve $titulo = 'Hola'
        extract($data);
        
        // Busca la vista en la carpeta views
        $viewFile = __DIR__ . '/../../views/' . $path . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile; 
            return; 
        }
        
        // Si no existe, lanza un error para que sepamos qué pasó
        die("Error: La vista '$viewFile' no existe.");
    }
}