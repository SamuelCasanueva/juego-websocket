<?php

namespace App\Services;

class RobcoTerminalGenerator
{
    /**
     * Packs de palabras temáticas de Fallout (6 letras, sin tildes para fuente CRT)
     */
    protected static array $wordPacks = [

        // ── El Yermo: supervivencia post-apocalíptica ──────────────────
        'yermo' => [
            'BOVEDA', 'ATOMOS', 'FUSION', 'SAQUEO', 'GUERRA', 'ACCESO',
            'NUCLEO', 'CODIGO', 'ALERTA', 'SECTOR', 'ESCUDO', 'ACEROS',
            'TOXICO', 'MOTORS', 'CLAVES', 'SIRENA', 'CENTRO', 'BOMBEO',
            'MUTADO', 'CABLES', 'SENSOR', 'FILTRO', 'PULSOS', 'PLASMA',
            'ENLACE', 'FALLOS', 'TIERRA', 'CANYON', 'SOMBRA', 'CARGAS',
            'GRIETA', 'CRIPTA', 'RUINAS', 'BUNKER', 'CAPTUR', 'VENENO',
            'ESCAPA', 'PATRUL', 'RACION', 'MINERA', 'PURIFI', 'RADIAC',
        ],

        // ── RobCo Industries: tecnología y robótica ────────────────────
        'robco' => [
            'MODULO', 'ROBOTS', 'BOBINA', 'TORQUE', 'RELEVO', 'ANTENA',
            'ACTIVO', 'PASIVO', 'CICLOS', 'DIODOS', 'LOGICA', 'PULSAR',
            'MATRIX', 'SWITCH', 'SOCKET', 'ROUTER', 'KERNEL', 'FLUJOS',
            'FRAMES', 'ARRAYS', 'BUFFER', 'DECODE', 'ENCODE', 'BINARI',
            'NEURON', 'CORTEX', 'SERVOS', 'MICROS', 'VOLTIO', 'AMPERS',
            'PIXELA', 'CUANTO', 'CIRCUI', 'LAZARO', 'UPLINK', 'SUBNET',
            'DAEMON', 'PROTOC', 'FLUXOR', 'SYNTHE', 'DRIVER', 'PLUGIN',
        ],

        // ── Vault-Tec: ciencia y experimentación ──────────────────────
        'vault' => [
            'COBALT', 'ISOMER', 'GAMMAS', 'CELULA', 'TEJIDO', 'VIALES',
            'SERINA', 'HELICE', 'MUTAGE', 'EXPERI', 'COBAYA', 'CULTIV',
            'PLACAS', 'BIOLOG', 'DOSIME', 'GEIGER', 'PROTEO', 'LIPIDO',
            'ENZIMA', 'PRIONI', 'CITOSI', 'OSMOSI', 'CATALI', 'HIDROG',
            'OXIGEN', 'NITROG', 'FOSFOR', 'CALCIO', 'IODURO', 'FLUORI',
            'VECTOR', 'SUEROS', 'VACUNA', 'CROMOL', 'GENOMA', 'CADENA',
            'LABORA', 'RADIOG', 'CLONAR', 'MUTANT', 'SIMIOS', 'DROIDS',
        ],

        // ── Nueva Vegas: casino, crimen y política ─────────────────────
        'vegas' => [
            'CASINO', 'APUEST', 'FICHAS', 'RULETA', 'DEALER', 'BARAJA',
            'TRIUNF', 'BONUSX', 'DINERO', 'CREDOS', 'FARAON', 'MAFIAS',
            'TRAICO', 'ALIANZ', 'SENADO', 'LEGION', 'CORREO', 'GUARDA',
            'COHETE', 'PISTOL', 'ESPIAS', 'AGENTE', 'BROKER', 'TRATOS',
            'LUXURY', 'NEONEX', 'FRONTE', 'RECUER', 'CHANTA', 'VENDEX',
            'BARMAN', 'FIESTA', 'TRUCOS', 'MARCAD', 'CARTEL', 'CAIDAS',
            'TOSTON', 'BOTELL', 'PELOTX', 'MONEDA', 'CAMARA', 'ESCENA',
        ],
    ];

    protected static array $punctuationChars = [
        '!', '@', '#', '$', '%', '^', '&', '*', '-', '_', '+', '=',
        '|', ':', ';', '?', '/', '\\', ',', '.', '~'
    ];

    /**
     * Calcula la cantidad de letras que coinciden en la misma posición exacta.
     */
    public static function calculateLikeness(string $guess, string $secret): int
    {
        $guess  = strtoupper(trim($guess));
        $secret = strtoupper(trim($secret));
        $len    = min(strlen($guess), strlen($secret));
        $matches = 0;

        for ($i = 0; $i < $len; $i++) {
            if ($guess[$i] === $secret[$i]) {
                $matches++;
            }
        }

        return $matches;
    }

    /**
     * Devuelve los nombres de los packs de palabras disponibles.
     */
    public static function availablePacks(): array
    {
        return array_keys(self::$wordPacks);
    }

    /**
     * Genera un volcado de memoria completo para la terminal.
     *
     * @param  string $pack  Pack temático: 'yermo' | 'robco' | 'vault' | 'vegas'
     */
    public static function generateGame(
        int    $wordCount      = 12,
        int    $linesPerColumn = 16,
        int    $charsPerLine   = 12,
        string $pack           = 'yermo'
    ): array {
        // 1. Seleccionar palabras del pack elegido
        $dictionary     = self::$wordPacks[$pack] ?? self::$wordPacks['yermo'];
        $shuffled       = $dictionary;
        shuffle($shuffled);
        $candidateWords = array_slice($shuffled, 0, min($wordCount, count($shuffled)));

        // 2. Elegir la palabra secreta
        $secretWord = $candidateWords[array_rand($candidateWords)];
        $wordLength = strlen($secretWord);

        $totalChars = $linesPerColumn * 2 * $charsPerLine; // 384

        // 3. Buffer vacío
        $buffer = array_fill(0, $totalChars, null);

        // 4. Distribuir palabras uniformemente
        $spacing = (int) floor($totalChars / count($candidateWords));

        foreach ($candidateWords as $index => $word) {
            $minPos = $index * $spacing;
            $maxPos = min($totalChars - $wordLength - 1, $minPos + $spacing - $wordLength);
            $pos    = rand(max(0, $minPos), max(0, $maxPos));

            for ($c = 0; $c < $wordLength; $c++) {
                $buffer[$pos + $c] = [
                    'char'       => $word[$c],
                    'type'       => 'word',
                    'word'       => $word,
                    'word_index' => $c
                ];
            }
        }

        // 5. Insertar pares de corchetes de ayuda clásica de Fallout
        $brackets = [
            ['open' => '(', 'close' => ')'],
            ['open' => '[', 'close' => ']'],
            ['open' => '{', 'close' => '}'],
            ['open' => '<', 'close' => '>'],
        ];

        for ($b = 0; $b < 4; $b++) {
            $bracketType = $brackets[array_rand($brackets)];
            $len         = rand(2, 6);
            $foundPos    = -1;

            for ($attempt = 0; $attempt < 30; $attempt++) {
                $candPos = rand(0, $totalChars - $len - 1);
                $free    = true;
                for ($k = 0; $k < $len; $k++) {
                    if ($buffer[$candPos + $k] !== null) {
                        $free = false;
                        break;
                    }
                }
                if ($free) {
                    $foundPos = $candPos;
                    break;
                }
            }

            if ($foundPos !== -1) {
                $bonusType = match($b % 3) {
                    0 => 'restore_attempts',
                    1 => 'remove_dud',
                    2 => 'offensive_glitch',
                };
                $bracketId = 'bracket_' . $b;

                for ($k = 0; $k < $len; $k++) {
                    if ($k === 0) {
                        $char = $bracketType['open'];
                    } elseif ($k === $len - 1) {
                        $char = $bracketType['close'];
                    } else {
                        $char = self::$punctuationChars[array_rand(self::$punctuationChars)];
                    }

                    $buffer[$foundPos + $k] = [
                        'char'       => $char,
                        'type'       => 'bracket',
                        'bracket_id' => $bracketId,
                        'bonus'      => $bonusType
                    ];
                }
            }
        }

        // 6. Rellenar huecos con símbolos de puntuación
        for ($i = 0; $i < $totalChars; $i++) {
            if ($buffer[$i] === null) {
                $buffer[$i] = [
                    'char' => self::$punctuationChars[array_rand(self::$punctuationChars)],
                    'type' => 'junk'
                ];
            }
        }

        // 7. Agrupar en columnas con direcciones de memoria hexadecimal
        $baseAddress = 0xF200 + rand(0x10, 0xA0) * 16;
        $leftColumn  = [];
        $rightColumn = [];

        for ($line = 0; $line < $linesPerColumn; $line++) {
            $leftAddr  = sprintf("0x%04X", $baseAddress + ($line * $charsPerLine));
            $leftSlice = array_slice($buffer, $line * $charsPerLine, $charsPerLine);
            $leftColumn[] = ['address' => $leftAddr, 'cells' => $leftSlice];

            $rightStart = ($linesPerColumn + $line) * $charsPerLine;
            $rightAddr  = sprintf("0x%04X", $baseAddress + (($linesPerColumn + $line) * $charsPerLine));
            $rightSlice = array_slice($buffer, $rightStart, $charsPerLine);
            $rightColumn[] = ['address' => $rightAddr, 'cells' => $rightSlice];
        }

        return [
            'secret_word'     => $secretWord,
            'word_length'     => $wordLength,
            'candidate_words' => $candidateWords,
            'left_column'     => $leftColumn,
            'right_column'    => $rightColumn,
            'pack'            => $pack,
        ];
    }
}
