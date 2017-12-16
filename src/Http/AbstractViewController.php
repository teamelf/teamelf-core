<?php

/**
 * This file is part of TeamELF
 *
 * (c) GuessEver <guessever@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeamELF\Http;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractViewController extends AbstractController
{
    /**
     * template's relative path to views/
     * @var string
     */
    protected $template = 'hello.twig';

    /**
     * data
     * @var array
     */
    protected $data = [];

    /**
     * a 301 redirect will be sent if not null
     * @var null|string
     */
    protected $redirect = null;

    /**
     * handle the request
     *
     * @return Response
     */
    public function handler(): Response
    {
        if ($this->redirect) {
            return response(null, 302, ['Location' => $this->redirect]);
        } else {
            return view($this->template, $this->data);
        }
    }
}
