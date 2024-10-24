<?php
declare(strict_types=1);

namespace iutnc\deefy\loader;

class Psr4ClassLoader{
    protected $prefix;
    protected $base_dir;

    public function __construct($prefix, $base_dir)
    {
        $this->prefix = $prefix;
        $this->base_dir = $base_dir;
    }

    public function loadClass($className)
    {
        if (strpos($className, $this->prefix) !== 0) {
            return;
        }

        $relativeClass = substr($className, strlen($this->prefix));

        $file = $this->base_dir . DIRECTORY_SEPARATOR . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
        } else {
            echo "Fichier non trouvé : " . $file . "<br>";
        }
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }


}
