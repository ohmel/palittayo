<?php

class ItemController extends RestController
{
    public function getUserItems($userId){
        $item = new Items;
        return $item->getUserItems($userId);
    }

    public function restEvents()
    {
        $this->onRest('req.get.getUserItems.render', function () {
            $userId = $_GET['userId'];
            echo $this->restJsonEncode($this->getUserItems($userId));
        });
    }
}