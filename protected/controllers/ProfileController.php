<?php

class ProfileController extends RestController
{
    public function getProfile($userId){
        $profile = new UserProfile;
        return $profile->getProfile($userId);
    }

    public function follow($followerId, $userId){
        $profile = new UserProfile();
        return $profile->follow($followerId, $userId);
    }

    public function restEvents()
    {
        $this->onRest('req.get.getProfile.render', function () {
            $userId = $_GET['userId'];
            echo $this->restJsonEncode($this->getProfile($userId));
        });

        $this->onRest('req.post.getProfile.render', function ($data) {
            echo $this->restJsonEncode($this->follow($data['userId'], $data['followingId']));
        });
    }
}