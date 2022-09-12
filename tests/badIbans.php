<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD3-Clause
 */

declare(strict_types=1);

return [
    'AL4721211009000000023569874', // wrong length
    'AL74212110090000000235698741', // bad checksum digits
    'AL47212110090000000235698742', // bad checksum digits
    'XX47212110090000000235698741', // bad country code
    '47XX212110090000000235698741', // bad format
];
