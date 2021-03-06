<?php

/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeamELF\Api\Controller\Auth;

use Symfony\Component\HttpFoundation\Response;
use TeamELF\Exception\HttpUnauthorizedException;
use TeamELF\Http\AbstractController;

class LogoutController extends AbstractController
{
    protected $needLogin = false;

    /**
     * handle the request
     *
     * @return Response
     * @throws HttpUnauthorizedException
     */
    public function handler(): Response
    {
        $member = $this->getAuth();
        if ($member) {
            $this->log('info', 'Logout');
        }
        $this->auth(null);
        return response();
    }
}
