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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Command\InstallCommand;
use App\Model\Entity\Role;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Sniffer\SnifferRegistry;
use Faker\Factory;

class InstallCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        InstallCommand::$userIsRoot = false;
        $this->emptyDirectory(CACHE . 'database' . DS);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        SnifferRegistry::get('test')->restart();
        SnifferRegistry::get('test')->markAllTablesAsDirty();
    }

    /**
     * Basic help test
     */
    public function testInstallCommandHelp()
    {
        $this->exec('passbolt install -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Installation shell for the passbolt application.');
        $this->assertOutputContains('cake passbolt install');
    }

    /**
     * @Given I am root
     * @When I run "passbolt migrate"
     * @Then the migrations cannot be run.
     */
    public function testInstallCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(InstallCommand::class);
    }

    /**
     * Quick install with no existing backup
     */
    public function testInstallCommandQuickWithNoExistingBackup()
    {
        $this->exec('passbolt install --quick -q');
        $this->assertExitError();
    }

    /**
     * Quick install with existing backup
     */
    public function testInstallCommandQuickWithExistingBackup()
    {
        // Create a backup
        $this->exec('passbolt mysql_export -q');
        $this->assertExitSuccess();

        $this->exec('passbolt install --quick -q');
        $this->assertExitSuccess();
    }

    /**
     * Normal installation will fail because tables are present
     */
    public function testInstallCommandNormalWithExistingTables()
    {
        $this->exec('passbolt install -q');
        $this->assertExitError();
    }

    /**
     * Normal installation force
     */
    public function testInstallCommandNormalForceWithoutAdmin()
    {
        $this->exec('passbolt install --force --no-admin --backup -q');
        $this->assertExitSuccess();
    }

    /**
     * Normal installation force with data import
     */
    public function testInstallCommandNormalForceWithDataImport()
    {
        $this->exec('passbolt install --force --no-admin --backup -q --data alt0');
        $this->assertExitSuccess();
    }

    /**
     * Normal installation force with admin data
     */
    public function testInstallCommandNormalForceWithAdminData()
    {
        $faker = Factory::create();
        $userName = $faker->email;
        $cmd = 'passbolt install --force --backup -q ';
        $cmd .= ' --admin-first-name ' . $faker->firstNameFemale;
        $cmd .= ' --admin-last-name ' . $faker->lastName;
        $cmd .= ' --admin-username ' . $userName;

        $this->exec($cmd);
        $this->assertExitSuccess();

        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $admins = $UsersTable->find()->innerJoinWith('Roles', function (Query $q) {
            return $q->where(['Roles.name' => Role::ADMIN]);
        });
        $this->assertSame(1, $admins->count());
        $this->assertSame($userName, $admins->first()->get('username'));
        $this->assertFalse($admins->first()->get('active'));
    }
}
