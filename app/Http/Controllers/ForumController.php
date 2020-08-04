<?php

namespace App\Http\Controllers;

use App\Events\makeLog;
use App\models\ForumCategory;
use App\models\ForumReply;
use App\models\ForumTagRelation;
use App\models\ForumTopic;
use App\models\ForumLike;
use App\models\Tags;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use phpDocumentor\Reflection\DocBlock\Tag;

class ForumController extends Controller
{
    public function adminForumCategory()
    {
        $category = ForumCategory::where('lang', app()->getLocale())->get();
        foreach ($category as $item)
            $item->topic = ForumTopic::where('categoryId', $item->id)->count();

        return view('profile.admin.forum.forumCategories', compact(['category']));
    }

    public function storeForumCategory(Request $request)
    {
        if(isset($request->name) && isset($request->id)){
            $lang = isset($request->lang) ? $request->lang : app()->getLocale();

            if($request->id == 0){
                $category = new ForumCategory();
                $category->lang = $lang;
            }
            else {
                $category = ForumCategory::find($request->id);
                if($category == null){
                    echo json_encode(['status' => 'notFindId']);
                    return;
                }
            }

            $check = ForumCategory::where('lang', $lang)->where('id', '!=', $request->id)->where('name', $request->name)->first();
            if ($check != null) {
                echo json_encode(['status' => 'duplicate']);
                return;
            }

            $category->name = $request->name;
            $category->summery = $request->summery;
            $category->save();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteForumCategory(Request $request)
    {
        if(isset($request->id)){
            $category = ForumCategory::find($request->id);
            if($category != null) {
                $topics = ForumTopic::where('categoryId', $request->id)->count();
                if($topics == 0){
                    $category->delete();
                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'noneZeroTopic']);
            }
            else
                echo json_encode(['status' => 'notFoundId']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }




    public function forumIndex()
    {
        $header = __('Topical discussion');
        $path = [];

        $categories = ForumCategory::where('lang', app()->getLocale())->get();
        foreach ($categories as $item){
            $topicsId = ForumTopic::where('categoryId', $item->id)->pluck('id')->toArray();
            $repliesId = ForumReply::whereIn('topicId', $topicsId)->pluck('id')->toArray();

            $item->topics  = ForumTopic::where('categoryId', $item->id)->count();
            $item->replies = ForumReply::whereIn('topicId', $topicsId)->count();
            $item->person  = ForumReply::whereIn('topicId', $topicsId)->groupBy('userId')->pluck('userId')->count();
            $item->like    = ForumLike::whereIn('topicId', $topicsId)->orWhereIn('replyId', $repliesId)->where('like', 1)->count();
            $item->dislike = ForumLike::whereIn('topicId', $topicsId)->orWhereIn('replyId', $repliesId)->where('like', -1)->count();
        }

        return view('forum.forumIndex', compact(['header', 'path', 'categories']));
    }

    public function forumCategoryList($categoryId)
    {
        $category = ForumCategory::find($categoryId);
        if($category == null)
            return redirect(route('forum.index'));

        $header = $category->name;
        $path = [
            [
                'title' => $category->name,
                'url' => '#'
            ]
        ];

        $topics = ForumTopic::where('categoryId', $categoryId)->orderByDesc('created_at')->get();
        foreach ($topics as $topic){
            $topic->username = User::find($topic->userId)->name;
            $topic->userPic = User::getUserPic($topic->userId);
            $topic->replies = ForumReply::where('topicId', $topic->id)->count();
            $topic->person = ForumReply::where('topicId', $topic->id)->groupBy('userId')->pluck('userId')->count();
            $topic->time = Carbon::make($topic->created_at)->format('Y-m-d');
            $topic->tag = \DB::table('tags')
                ->join('forumTagRelations', 'tags.id', '=', 'forumTagRelations.tagId')
                ->where('forumTagRelations.topicId', $topic->id)
                ->select(['tags.tag'])->pluck('tag')->toArray();

            $topic->summery = $foo = mb_substr(strip_tags($topic->text), 0, 100, "utf-8") . '...';
        }

        return view('forum.forumList', compact(['header', 'path', 'topics']));
    }

    public function forumNewTopic()
    {
        $header = __('New Topic');
        $path = [];

        $categories = ForumCategory::where('lang', app()->getLocale())->select(['name', 'id'])->get();
        return view('forum.newTopic', compact(['header', 'path', 'categories']));
    }

    public function forumTopic($topicId)
    {
        $topic = ForumTopic::find($topicId);
        if($topic == null)
            return redirect(route('forum.index'));

        $category = ForumCategory::find($topic->categoryId);

        $header = $topic->title;
        $path = [
            [
                'title' => $category->name,
                'url' => route('forum.category.list', ['categoryId' => $category->id])
            ],
            [
                'title' => $topic->id,
                'url' => '#'
            ],
        ];

        $topic->username = User::find($topic->userId)->name;
        $topic->userPic = User::getUserPic($topic->userId);
        $topic->replies = ForumReply::where('topicId', $topic->id)->where('id', '!=', $topic->bestAnsId)->orderByDesc('created_at')->get();
        $topic->repliesCount = ForumReply::where('topicId', $topic->id)->count();
        $topic->time = Carbon::make($topic->created_at)->format('Y-m-d');
        $topic->tag = \DB::table('tags')
            ->join('forumTagRelations', 'tags.id', '=', 'forumTagRelations.tagId')
            ->where('forumTagRelations.topicId', $topic->id)
            ->select(['tags.tag'])->pluck('tag')->toArray();

        $topic->youLike = 0;
        if(\auth()->check()) {
            $user = \auth()->user();
            $topic->youLike = ForumLike::where('userId', $user->id)->where('topicId', $topic->id)->first();
            if($topic->youLike == null)
                $topic->youLike = 0;
            else
                $topic->youLike = $topic->youLike->like;

            if(checkAcl($user->id, 'userAccess') || $user->id == $topic->userId)
                $topic->canDelete = true;

            if($user->id == $topic->userId)
                $topic->yourTopic = true;
        }

        foreach ($topic->replies as $replies)
            $replies = $this->trueReplyFormat($replies);

        if($topic->bestAnsId != null){
            $bestAns = ForumReply::find($topic->bestAnsId);
            if($bestAns != null) {
                $bestAns = $this->trueReplyFormat($bestAns);
                $bestAns->bestAns = true;
                $replyArray = [];
                foreach ($topic->replies as $rep)
                    array_push($replyArray, $rep);

                array_unshift($replyArray, $bestAns);

                $topic->replies = $replyArray;
            }
        }

        return view('forum.showTopic', compact(['header', 'path', 'topic']));
    }

    public function storeNewTopic(Request $request)
    {
        if(isset($request->title) && isset($request->category) && isset($request->description)){
            $topic = new ForumTopic();
            $topic->userId = Auth::user()->id;
            $topic->title = $request->title;
            $topic->text = $request->description;
            $topic->lang = isset($request->lang) ? $request->lang : app()->getLocale();
            $topic->categoryId = $request->category;
            $topic->save();

            $tags = json_decode($request->tags);
            foreach ($tags as $item){
                if($item != ''){
                    $check = Tags::where('tag', $item)->first();
                    if($check == null){
                        $check = new Tags();
                        $check->tag = $item;
                        $check->save();
                    }
                    $newTag = new ForumTagRelation();
                    $newTag->tagId = $check->id;
                    $newTag->topicId = $topic->id;
                    $newTag->save();
                }
            }

            event(new makeLog([
                'subject' => 'create_topic',
                'referenceTable' => 'forumTopics',
                'referenceId' => $topic->id,
            ]));

            echo 'ok';
        }
        else
            echo 'nok';

        return;
    }

    public function storeTopicAns(Request $request)
    {
        if(isset($request->text) && isset($request->topicId)){
            $topic = ForumTopic::find($request->topicId);
            if($topic != null){
                $newReply = new ForumReply();
                $newReply->userId = \auth()->user()->id;
                $newReply->topicId = $topic->id;
                $newReply->text = $request->text;
                $newReply->save();

                event(new makeLog([
                    'subject' => 'create_ans_topic',
                    'referenceTable' => 'forumReply',
                    'referenceId' => $newReply->id,
                ]));

                echo 'ok';
            }
            else
                echo 'nokFound';
        }
        else
            echo 'nok1';

        return;
    }

    public function setBestAnswerTopic(Request $request)
    {
        if(isset($request->ansId)){
            $reply = ForumReply::find($request->ansId);
            if($reply != null){
                $topic = ForumTopic::find($reply->topicId);
                if($topic != null && $topic->userId == \auth()->user()->id){
                    $topic->bestAnsId = $reply->id;
                    $topic->save();

                    event(new makeLog([
                        'subject' => 'choose_best_ans_topic',
                        'referenceTable' => 'forumTopic',
                        'referenceId' => $topic->id,
                    ]));

                    echo 'ok';
                }
                else
                    echo 'nok1';
            }
            else
                echo 'notFound';
        }
        else
            echo 'nok';

        return;
    }

    public function likeForum(Request $request)
    {
        if(isset($request->id) && isset($request->kind) && isset($request->like)){
            $user = \auth()->user();
            if($request->kind == 'topic'){
                $topic = ForumTopic::find($request->id);
                if($topic != null){
                    $userLike = ForumLike::where('userId', $user->id)->where('topicId', $request->id)->first();
                    if($userLike == null){
                        $userLike = new ForumLike();
                        $userLike->userId = $user->id;
                        $userLike->topicId = $request->id;
                    }
                    $userLike->like = $request->like;
                    $userLike->save();

                    $topic->like = ForumLike::where('like', 1)->where('topicId', $request->id)->count();
                    $topic->dislike = ForumLike::where('like', -1)->where('topicId', $request->id)->count();
                    $topic->save();

                    event(new makeLog([
                        'subject' => 'like_topic',
                        'referenceTable' => 'forumLikes',
                        'referenceId' => $userLike->id,
                    ]));

                    echo json_encode(['status' => 'ok', 'like' => $topic->like, 'dislike' => $topic->dislike]);
                }
                else
                    echo 'notFound';
            }
            else{
                $reply = ForumReply::find($request->id);
                if($reply != null){
                    $userLike = ForumLike::where('userId', $user->id)->where('replyId', $request->id)->first();
                    if($userLike == null){
                        $userLike = new ForumLike();
                        $userLike->userId = $user->id;
                        $userLike->replyId = $request->id;
                    }
                    $userLike->like = $request->like;
                    $userLike->save();

                    $reply->like = ForumLike::where('like', 1)->where('replyId', $request->id)->count();
                    $reply->dislike = ForumLike::where('like', -1)->where('replyId', $request->id)->count();
                    $reply->save();

                    event(new makeLog([
                        'subject' => 'like_reply',
                        'referenceTable' => 'forumLikes',
                        'referenceId' => $userLike->id,
                    ]));

                    echo json_encode(['status' => 'ok', 'like' => $reply->like, 'dislike' => $reply->dislike]);
                }
                else
                    echo 'notFound';

            }
        }
        else
            echo 'nok';

        return;
    }

    public function deleteTopic(Request $request)
    {
        if(isset($request->id)){
            $user = \auth()->user();
            $topic = ForumTopic::find($request->id);
            if($topic != null && ($user->id == $topic->userId || checkAcl($user->id, 'userAccess'))){
                ForumLike::where('topicId', $topic->id)->delete();
                ForumTagRelation::where('topicId', $topic->id)->delete();

                $replies = ForumReply::where('topicId', $topic->id)->get();
                foreach ($replies as $item){
                    ForumLike::where('replyId', $item->id)->delete();
                    event(new makeLog([
                        'subject' => 'delete_reply',
                        'referenceTable' => 'forumReply',
                        'referenceId' => $item->id,
                        'text' => 'deleted topic',
                    ]));
                    $item->delete();
                }

                event(new makeLog([
                    'subject' => 'delete_topic',
                    'referenceTable' => 'forumReply',
                    'referenceId' => $topic->id,
                ]));
                $topic->delete();

                echo 'ok';
            }
            else
                echo 'notFound';
        }
        else
            echo 'nok';

        return;
    }

    public function deleteReply(Request $request)
    {
        if(isset($request->id)){
            $user = \auth()->user();
            $reply = ForumReply::find($request->id);
            if($reply != null && ($user->id == $reply->userId || checkAcl($user->id, 'userAccess'))){
                ForumLike::where('replyId', $reply->id)->delete();
                $topic = ForumTopic::find($reply->topicId);
                if($topic->bestAnsId == $reply->id) {
                    $topic->bestAnsId = null;
                    $topic->save();
                }
                $reply->delete();

                event(new makeLog([
                    'subject' => 'delete_reply',
                    'referenceTable' => 'forumReply',
                    'referenceId' => $reply->id,
                ]));

                echo 'ok';
            }
            else
                echo 'notFound';
        }
        else
            echo 'nok';

        return;
    }


    private function trueReplyFormat($reply){
        if(\auth()->check()) {
            $user = \auth()->user();
            $reply->youLike = ForumLike::where('userId', $user->id)->where('replyId', $reply->id)->first();
            if(checkAcl($user->id, 'userAccess') || $user->id == $reply->userId)
                $reply->canDelete = true;
        }

        if($reply->youLike == null)
            $reply->youLike = 0;
        else
            $reply->youLike = $reply->youLike->like;

        $reply->userPic  = User::getUserPic($reply->userId);
        $reply->username = User::find($reply->userId)->name;
        $reply->time     = Carbon::make($reply->created_at)->format('Y-m-d');

        return $reply;
    }
}
