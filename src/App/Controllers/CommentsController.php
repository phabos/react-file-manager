<?php
// source
// http://salman-w.blogspot.fr/2012/08/php-adjacency-list-hierarchy-tree-traversal.html
namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entities\Comment;

class CommentsController
{
    protected $app;
    protected $parentIndex = null;
    protected $commentDatas = null;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    public function addAction()
    {
        $comment = new Comment();
        $comment->content = 'My second child comment';
        $comment->created = new \DateTime();
        $comment->updated = new \DateTime();
        $comment->parend_id = 3;
        $comment->save();
        echo 'Comment saved ' . $comment->id;
        die();
    }

    protected function displayChildNodes($parent_id, $level)
    {
        $data = $this->commentData;
        $index = $this->parentIndex;
        $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
        $tree = [];
        if (isset($index[$parent_id])) {
            foreach ($index[$parent_id] as $id) {
                $tree[] = str_repeat("-", $level) . $data[$id] . '<br/>';
                $tree = array_merge( $tree, $this->displayChildNodes($id, $level + 1) );
            }
        }
        return $tree;
    }

    public function listAction()
    {
        $comments = Comment::take(Comment::MAX_RESULT)->orderBy('id', 'DESC')->get();
        $treeComment = [];
        $this->getParentNodes($comments);
        $r = $this->displayChildNodes(0, 0);
        echo '<pre>'; var_dump($r); echo'</pre>'; die();
        die();
    }

    public function getParentNodes($comments)
    {
        if( $this->parentIndex !== null )
            return $this->parentIndex;

        $parentIndex = [];
        if( count( $comments ) > 0 ) {
           foreach( $comments as $comment ) {
                $parentIndex[$comment['parend_id']][] = $comment['id'];
                $data[$comment['id']] = $comment['content'];
            }
        }

        $this->parentIndex = $parentIndex;
        $this->commentData = $data;
    }
}
