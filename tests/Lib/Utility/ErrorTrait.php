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
 * @since         2.0.0
 */
namespace App\Test\Lib\Utility;

trait ErrorTrait
{
    /**
     * Asserts that the latest json request failed.
     *
     * @param null $code (optional) Expected response code
     * @param string $message (optional) Expected response message.
     * @param string $errorMessage (optional) Test case error message to be displayed
     * @return void
     */
    public function assertError($code = null, $message = '', $errorMessage = '')
    {
        $this->assertEquals('error', $this->_responseJsonHeader->status, 'The request should be an error');

        // If expected response code given.
        if (!is_null($code)) {
            $this->assertResponseCode($code);
        } else {
            $this->assertResponseError();
        }

        // If message given.
        if (!empty($message)) {
            $this->assertMatchesRegularExpression("/$message/", $this->_responseJsonHeader->message, $errorMessage);
        }
    }

    /**
     * Asserts that the json response is relative to an authentication error.
     * From CakePHP 4.x, with the Authentication plugin, an error 401 is returned
     * instead of 403 (https://stackoverflow.com/questions/3297048/403-forbidden-vs-401-unauthorized-http-responses)
     *
     * @param string $msg The message displayed to the user.
     * @return void
     */
    public function assertAuthenticationError($msg = 'Authentication is required to continue')
    {
        $this->assertError(401, $msg);
    }

    /**
     * Asserts that the json response is relative to a forbidden error.
     *
     * @return void
     */
    public function assertForbiddenError($msg = 'Forbidden')
    {
        $this->assertError(403, $msg);
    }

    /**
     * Asserts that the json response is relative to a forbidden error.
     *
     * @return void
     */
    public function assertBadRequestError($msg = 'Bad Request')
    {
        $this->assertError(400, $msg);
    }
}
