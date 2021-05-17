<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.2.0
 */

namespace Passbolt\Locale\Test\TestCase\Service;

use Cake\TestSuite\TestCase;
use Passbolt\Locale\Service\LocaleService;

class LocaleServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->loadPlugins(['Passbolt/Locale']);
    }

    /**
     * Staticly check that the supported locales are well defined in the config.
     */
    public function testGetSystemLocales(): void
    {
        $this->assertSame([
            'en-UK',
            'fr-FR',
        ], LocaleService::getSystemLocales());
    }

    public function dataForTestLocaleUtilityLocaleIsValid(): array
    {
        return [
            ['en-UK', true],
            ['en_UK', true],
            ['fr_FR', true],
            ['xx-YY', false],
            ['', false],
            [null, false],
        ];
    }

    /**
     * @param string|null $locale
     * @param bool $expected
     * @dataProvider dataForTestLocaleUtilityLocaleIsValid
     */
    public function testLocaleUtilityLocaleIsValid(?string $locale, bool $expected): void
    {
        $service = new LocaleService();
        $this->assertSame(
            $expected,
            $service->isValidLocale($locale)
        );
    }
}
