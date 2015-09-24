<?php

class ProfileController extends RestController
{
    public function getProfile($userId){
        $profile = new UserProfile;
        return $profile->getProfile($userId);
    }

    public function follow($followedId, $followerId){
        $profile = new UserProfile();
        return $profile->follow($followedId, $followerId);
    }

    public function checkIfFollowing($followedId, $followerId){
        $profile = new UserProfile();
        return $profile->checkIfFollowing($followedId, $followerId);
    }

    public function restEvents()
    {
        $this->onRest('req.get.getProfile.render', function () {
            $userId = $_GET['userId'];
            echo $this->restJsonEncode($this->getProfile($userId));
        });

        $this->onRest('req.post.follow.render', function ($data) {
//            $this->printa($data); exit;
            echo $this->restJsonEncode($this->follow($data['followedId'], $data['followerId']));
        });

        $this->onRest('req.get.checkIfFollowing.render', function ($data) {
//            $this->printa($data); exit;
            echo $this->restJsonEncode($this->checkIfFollowing($_GET['followedId'], $_GET['followerId']));
        });
    }
}