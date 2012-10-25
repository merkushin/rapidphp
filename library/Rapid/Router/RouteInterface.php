<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Router;

interface RouteInterface
{
    public function pass(\Rapid\Request $request);
}