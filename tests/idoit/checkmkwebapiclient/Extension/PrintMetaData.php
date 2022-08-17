<?php

/**
 * Copyright (C) 2022 synetics GmbH
 * Copyright (C) 2018-2022 Benjamin Heisig
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Benjamin Heisig <https://benjamin.heisig.name/>
 * @copyright Copyright (C) 2022 synetics GmbH
 * @copyright Copyright (C) 2018-2022 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/i-doit/checkmk-web-api-client-php
 */

declare(strict_types=1);

namespace Idoit\CheckmkWebAPIClient\Extension;

use \Exception;
use PHPUnit\Runner\BeforeFirstTestHook;
use Symfony\Component\Dotenv\Dotenv;

final class PrintMetaData implements BeforeFirstTestHook {

    /**
     * @var array
     */
    protected $composer = [];

    /**
     * @throws Exception on error
     */
    public function executeBeforeFirstTest(): void {
        $this
            ->loadEnvironment()
            ->loadComposer()
            ->printMetaData();
    }

    /**
     * @return self Returns itself
     */
    protected function loadEnvironment(): self {
        (new Dotenv())
            ->usePutenv(true)
            ->load(__DIR__ . '/../../../../.env');
        return $this;
    }

    /**
     * @return self Returns itself
     * @throws Exception on error
     */
    protected function loadComposer(): self {
        $composerFile = __DIR__ . '/../../../../composer.json';
        $composerFileContent = file_get_contents($composerFile);
        if (!is_string($composerFileContent)) {
            throw new Exception(sprintf(
                'Unable to read file "%s"',
                $composerFile
            ));
        }
        $this->composer = json_decode($composerFileContent, true);
        return $this;
    }

    /**
     * @return self Returns itself
     */
    protected function printMetaData(): self {
        $url = getenv('URL');
        $libName = $this->composer['name'];
        $libVersion = $this->composer['extra']['version'];
        $phpVersion = PHP_VERSION;
        $date = date('c');
        $os = PHP_OS;

        fwrite(STDOUT, <<< EOF
Server-side information:
    URL:            $url

Client-side information:
    Library:        $libName $libVersion
    PHP:            $phpVersion
    OS:             $os
    Date:           $date


EOF
        );

        return $this;
    }

}
