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

createComposer();
addIndex(); 
echo("-> Attempting to download Larkspur via Composer\n");
system("composer require larkspur/larkspur");
