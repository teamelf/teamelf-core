<?php

/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeamELF\View\Controller;

use Symfony\Component\HttpFoundation\Request;
use TeamELF\Http\ViewController;

class ResetPasswordController extends ViewController
{
    protected $template = 'password-reset.twig';

    function __construct(Request $request, array $parameters)
    {
        parent::__construct($request, $parameters);

        $this->data = [
            'token' => $this->getParameter('token')
        ];
    }

    protected function addAssets()
    {
        parent::addAssets();
        $this->assets
            ->addJs(__DIR__ . '/../../../assets/auth/dist/auth.js')
            ->entry('teamelf/auth/reset/main');
    }
}
