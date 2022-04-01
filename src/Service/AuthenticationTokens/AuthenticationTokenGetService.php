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
 * @since         3.6.0
 */

namespace App\Service\AuthenticationTokens;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * Class AuthenticationTokenGetService
 *
 * @package App\Service\AuthenticationTokens
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class AuthenticationTokenGetService
{
    use ModelAwareTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel('AuthenticationTokens');
    }

    /**
     * Get active and not expired token or fail
     *
     * @param string $tokenId token value uuid
     * @param string $userId user id uuid
     * @param string $type see AuthenticationToken entity types
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException if token is not found
     * @throws \App\Error\Exception\CustomValidationException if the token is expired or inactive
     * @throws \Cake\Http\Exception\BadRequestException if token id is not a valid uuid
     */
    public function getActiveNotExpiredOrFail(string $tokenId, string $userId, string $type): AuthenticationToken
    {
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The token should be a valid UUID.'));
        }

        try {
            $where = [
                'id' => $tokenId,
                'type' => $type,
                'user_id' => $userId,
            ];
            /** @var \App\Model\Entity\AuthenticationToken $tokenEntity */
            $tokenEntity = $this->AuthenticationTokens->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The authentication token could not be found.'));
        }

        if ($tokenEntity->isExpired()) {
            $error = [
                'token' => [
                    'expired' => 'The token is expired.',
                ],
            ];
            throw new CustomValidationException(__('The authentication token is expired.'), $error);
        }

        if ($tokenEntity->isNotActive()) {
            $error = [
                'token' => [
                    'isActive' => 'The token is already consumed.',
                ],
            ];
            throw new CustomValidationException(__('The authentication token is not active.'), $error);
        }

        return $tokenEntity;
    }
}
