<?php
function createComposer() {
    $comp = fopen('composer.json', 'w') or
        die("Unable to open composer.json");

    fwrite($comp, "{\n  \"minimum-stability\": \"dev\"\n}");
    fclose($comp);
    echo("-> Finished writing composer.json\n");
}

function addIndex() {
    $idx = fopen('index.php', 'w') or
        die("Unable to open index.php");

    $txt = <<<EOS
<?php
    require __DIR__ . '/vendor/autoload.php';
    \$larkspur = new Larkspur();
    \$larkspur->load_app('MyApp')->run();
?>
EOS;
    fwrite($idx, $txt);
    fclose($idx);
    echo("-> Created index.php\n");
}

function createTestApp() {
    if (! (mkdir("app/MyApp/Controller", 0777, true))) {
        echo("*** Failed to create controller directory\n");
        exit();
    }

    mkdir("app/MyApp/Model");
    mkdir("app/MyApp/views");

    $root = fopen("app/MyApp/Controller/Root.php", "w") or
        die("Failed to open Root.php\n");

    $root_txt = <<<EOS
<?php
namespace MyApp\Controller;

class Root extends \Controller {
    /**
      * @route /
      * @method get
      */
    public static function index() {
        echo "<h1>Hello, World!</h1>";
    }
}
EOS;
    fwrite($root, $root_txt);
    fclose($root);
    echo("-> Finished writing Root.php\n");
}

createComposer();
createTestApp();
addIndex(); 
echo("-> Attempting to download Larkspur via Composer\n");
system("composer require larkspur/larkspur");
