<?php
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
 * @since         3.4.0
 */
use Migrations\AbstractMigration;

class V340PostgresUUIDCommunity extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('account_settings')
            ->changeColumn('id', 'uuid')
            ->changeColumn('property_id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('action_logs')
            ->changeColumn('action_id', 'uuid')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid', ['null' => true])
            ->save();

        $this->table('actions')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('authentication_tokens')
            ->changeColumn('id', 'uuid')
            ->changeColumn('token', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('avatars')
            ->changeColumn('id', 'uuid')
            ->changeColumn('profile_id', 'uuid')
            ->save();

        $this->table('comments')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('foreign_key', 'uuid')
            ->changeColumn('id', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->changeColumn('parent_id', 'uuid', ['null' => true])
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('entities_history')
            ->changeColumn('action_log_id', 'uuid')
            ->changeColumn('foreign_key', 'uuid')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('favorites')
            ->changeColumn('foreign_key', 'uuid')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid', ['null' => true])
            ->save();

        $this->table('gpgkeys')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('groups')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('id', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->save();

        $this->table('groups_users')
            ->changeColumn('group_id', 'uuid', ['null' => true])
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid', ['null' => true])
            ->save();

        $this->table('organization_settings')
            ->changeColumn('id', 'uuid')
            ->changeColumn('property_id', 'uuid')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->save();

        $this->table('permissions')
            ->changeColumn('aco_foreign_key', 'uuid')
            ->changeColumn('aro_foreign_key', 'uuid', ['null' => true])
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('permissions_history')
            ->changeColumn('id','uuid')
            ->changeColumn('aco_foreign_key','uuid')
            ->changeColumn('aro_foreign_key','uuid', ['null' => true, 'default' => null])
            ->save();

        $this->table('profiles')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('resource_types')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('resources')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('id', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->changeColumn('resource_type_id', 'uuid', ['null' => true])
            ->save();

        $this->table('roles')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('secret_accesses')
            ->changeColumn('id', 'uuid')
            ->changeColumn('resource_id', 'uuid')
            ->changeColumn('secret_id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('secrets')
            ->changeColumn('id', 'uuid')
            ->changeColumn('resource_id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('secrets_history')
            ->changeColumn('id', 'uuid')
            ->changeColumn('resource_id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('transfers')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->changeColumn('authentication_token_id', 'uuid')
            ->save();

        $this->table('user_agents')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('users')
            ->changeColumn('id', 'uuid')
            ->changeColumn('role_id', 'uuid')
            ->save();
    }
}
