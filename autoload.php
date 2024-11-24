<?php

/* Autoload simples, usada para carregar automaticamente as classes ou arquivos
 * necessários em projetos sem precisar de require ou include.
 */

 spl_autoload_register(function ($class) {
  $base = __DIR__.'/src/'; 

    $file = $base.str_replace('\\', '/', $class).'.php';

    if (file_exists($file)) {
        require $file; 
    } else {
        die("Erro: Arquivo para a classe $class não encontrado em: $file");
    }
});
