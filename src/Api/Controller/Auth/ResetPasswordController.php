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
use Symfony\Component\Validator\Constraints\NotBlank;
use TeamELF\Core\Member;
use TeamELF\Core\PasswordResetToken;
use TeamELF\Exception\HttpForbiddenException;
use TeamELF\Http\AbstractController;

class ResetPasswordController extends AbstractController
{
    /**
     * handle the request
     *
     * @return Response
     * @throws HttpForbiddenException
     */
    public function handler(): Response
    {
        $data = $this->validate([
            'username' => [
                new NotBlank()
            ],
            'password' => [
                new NotBlank()
            ],
            'password_confirmation' => [
                new NotBlank()
            ]
        ]);
        if ($data['password'] !== $data['password_confirmation']) {
            throw new HttpForbiddenException();
        }
        $token = PasswordResetToken::find($this->getParameter('token'));
        $member = Member::search($data['username']);
        if (!$token || !$token->isValid() || !$member || $token->getMember()->getId() !== $member->getId()) {
            throw new HttpForbiddenException();
        }
        $token->delete();
        $member->password($data['password'])
            ->save();
        $this->auth(null);
        return response();
    }
}
