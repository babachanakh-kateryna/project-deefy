<?php

namespace iutnc\deefy\action;

/**
 * Class DefaultAction est une classe qui represente l'action par defaut
 */
class DefaultAction extends Action
{

    public function execute(): string
    {
        return "<h3>Welcome !</h3>";
    }
}