<?php

class CommentController extends RestController
{

    public function getComments($id, $commentType){
        $comment = new Comments();
        $commentArr = $comment->getComments($id, $commentType);
        return $commentArr;
    }

    public function restEvents()
    {
        $this->onRest('req.post.login.render', function ($data) {
            echo $this->restJsonEncode($this->postComments($data));
        });
        $this->onRest('req.get.getComments.render', function () {
            $parentId = $_GET['parentId'];
            $commentType = $_GET['commentType'];
            echo $this->restJsonEncode($this->getComments($parentId, $commentType));
        });

    }

}