<?php

class CommentController extends RestController
{

    public function getComments($id, $commentType){
        $comment = new Comments();
        $commentArr = $comment->getComments($id, $commentType);
        return $commentArr;
    }

    public function postComment($data, $parentId, $commentType){
        try{
            $comment = new Comments();
            $comment->comment = $data['comment'];
            if($commentType == 'user'){
                $comment->user_id = $parentId;
                $comment->item_id = 0;
            }
            if($commentType == 'item'){
                $comment->user_id = 0;
                $comment->item_id = $parentId;
            }
            $comment->date_posted = date('Y-m-d');
            $comment->poster_id = Yii::app()->user->getUser('userId');
            //        $this->printa($comment->attributes);exit;
            if($comment->save()){
                $commentArr = array();
                $commentArr['comment'] = $comment->attributes;
                $commentArr['comment']['poster']['user_id'] = $comment->poster->user_id;
                $commentArr['comment']['poster']['user_fullname'] = $comment->poster->user_fullname;
                return $commentArr;
            }else{
                return null;
            }
        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    public function restEvents()
    {
        $this->onRest('req.post.postComment.render', function ($data) {
            $commentType = $_GET['commentType'];
            $parentId = $_GET['parentId'];
            echo $this->restJsonEncode($this->postComment($data, $parentId, $commentType));
        });
        $this->onRest('req.get.getComments.render', function () {
            $parentId = $_GET['parentId'];
            $commentType = $_GET['commentType'];
            echo $this->restJsonEncode($this->getComments($parentId, $commentType));
        });

    }

}