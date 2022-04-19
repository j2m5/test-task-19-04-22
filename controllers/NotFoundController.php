<?php
require_once 'AbstractController.php';

class NotFoundController extends AbstractController
{

    protected function getTitle()
    {
        return 'Страница не найдена';
    }

    protected function getDescription()
    {
        return '';
    }

    protected function getKeyWords()
    {
        return 'ничего не найдено';
    }

    protected function getMain()
    {
        return $this->getTemplate('404');
    }
}
