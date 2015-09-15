<?php

class ProfileController extends RestController
{
    public function getProfile($userId){
        $profile = new UserProfile;
        return $profile->getProfile($userId);
    }

    public function restEvents()
    {
        $this->onRest('req.get.getProfile.render', function () {
            $userId = $_GET['userId'];
            echo $this->restJsonEncode($this->getProfile($userId));
        });
    }
}