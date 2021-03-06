<?php
declare(strict_types = 1);

/**
 * Sorteio ConFLOSS 2020! \o/
 */

$loot = [
    'Cortesia PHP Conference Brasil 2020' => 5,
    'Cortesia ConFLOSS 2021' => 5,
    'Mochila Elastic' => 1,
    'Kit de Vinho - Vinícola Stopassola' => 2,
    'Cortesia LPI Full' => 1, 
    'Cortesia LPI 50%' => 3, 
    'Cortesia LPI 25%' => 30, 
];

clearstatcache();

$dataPool = glob('./data/*.txt');

$comEsperanca = [];
$comEsperancaLPI = file("./data/Tutorial: Aulão Linux Essentials (sala 4).txt");

$comSorte = [];

foreach ($dataPool as $dataFile) {
    $comEsperanca = array_merge($comEsperanca, file($dataFile));
}

$comEsperanca = array_values(array_unique($comEsperanca));

printf('Total de participantes: %d%s', count($comEsperanca), PHP_EOL);

foreach ($loot as $premio => $quantidade) {
    if (preg_match('/LPI/', $premio) and $quantidade != 30) {
        sortear($comEsperancaLPI, $premio, $quantidade);
    } else {
        sortear($comEsperanca, $premio, $quantidade);
    }
}

function sortear(array &$pool, string $prize, int $times): void
{
    global $comSorte;

    for ($t = 0; $t < $times; $t++) {
        shuffle($pool);
        $max = count($pool) - 1;

        $index = random_int(0, $max);

        $winner = $pool[$index];

        // Checagem dupla, por conta do sorteio especial das cortesias 100 e 50% do LPI
        if (in_array($winner, $comSorte)) {
            $t--;
            continue;
        }

        printf('%s vai para...', $prize);
        sleep(2);

        $comSorte[] = $winner;
        
        $show = substr($winner, 0, 3);
        $msg = sprintf('Parabéns, participante #%s!%s', $show, PHP_EOL);
        echo $msg;
        file_put_contents('./winners.txt', sprintf('%s - %s - %s', $show, $prize, $winner), FILE_APPEND);

        $pool = array_diff($pool, $comSorte);
    }
}
