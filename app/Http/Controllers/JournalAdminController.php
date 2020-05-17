<?php

namespace App\Http\Controllers;

use App\models\DestinationTagRelation;
use App\models\Journal;
use App\models\JournalCategory;
use App\models\JournalPic;
use App\models\JournalPicLimbo;
use App\models\JournalTag;
use App\models\Tags;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class JournalAdminController extends Controller
{
    public function indexCategory()
    {
        $category = JournalCategory::where('lang', app()->getLocale())->get();
        return view('admin.journal.categoryIndex', compact(['category']));
    }

    public function storeCategory(Request $request)
    {
        if(isset($request->id) && isset($request->name)) {
            $check = JournalCategory::where('name', $request->name)->where('id', '!=', $request->id)->where('lang', app()->getLocale())->first();
            if ($check == null) {
                if($request->id == 0) {
                    $category = new JournalCategory();
                    $category->lang = app()->getLocale();
                }
                else
                    $category = JournalCategory::find($request->id);

                $category->name = $request->name;
                $category->viewOrder = $request->viewOrder;
                $category->save();

                echo json_encode(['status' => 'ok', 'id' => $category->id]);
            } else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function editCategory(Request $request){
        if(isset($request->id) && isset($request->name) && $request->name != ''){
            $check = JournalCategory::where('name', $request->name)->where('lang', app()->getLocale())->where('id', '!=', $request->id)->first();
            if($check == null){
                $category = JournalCategory::find($request->id);
                if($category != null){
                    $category->name = $request->name;
                    $category->save();

                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'nok2']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }


    public function indexJournal()
    {
        $this->deleteLimboPic();

        $journal = Journal::where('lang', app()->getLocale())->get();
        foreach ($journal as $item){
            $item->category = JournalCategory::find($item->categoryId);
            if($item->releaseDate == null)
                $item->releaseDate = 'draft';
            $item->user = User::find($item->userId);
        }

        return view('admin.journal.listJournal', compact(['journal']));
    }

    public function newJournal()
    {
        $kind = 'new';
        $category = JournalCategory::where('lang', app()->getLocale())->get();
        $code = random_int(10000, 999999);

        return view('admin.journal.newJournal', compact(['kind', 'category', 'code']));
    }

    public function editJournal($id)
    {
        $kind = 'edit';
        $journal = Journal::find($id);
        if($journal == null)
            return redirect(route('admin.journal.list'));

        if($journal->releaseDate != 'draft') {
            if ($journal->releaseDate > Carbon::now()->format('Y-m-d'))
                $journal->releaseDateType = 'future';
            else
                $journal->releaseDateType = 'now';
        }

        $journal->pic = asset('uploaded/journal/mainPics/' .$journal->pic);

        $tags = JournalTag::where('journalId', $id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $journal->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $journal->tags = [];

        $category = JournalCategory::where('lang', app()->getLocale())->get();
        $code = 0;

        return view('admin.journal.newJournal', compact(['journal', 'kind', 'code', 'category']));
    }

    public function storeDescriptionImgJournal(Request $request)
    {
        $id = json_decode($request->data)->id;
        $code = json_decode($request->data)->code;

        if( $_FILES['file'] && $_FILES['file']['error'] == 0){
            $fileName = time() . $_FILES['file']['name'];
            $location = __DIR__ . '/../../../public/uploaded/journal/description';
            if (!file_exists($location))
                mkdir($location);

            $location .= '/' . $fileName;
            if (storeImage($_FILES['file']['tmp_name'], $location)) {
                if($id != 0){
                    $journal = Journal::find($id);
                    if($journal == null){
                        unlink($location);
                        echo false;
                    }
                    else{
                        $journalPic = new JournalPic();
                        $journalPic->journalId = $journal->id;
                        $journalPic->pic = $fileName;
                        $journalPic->save();
                    }
                }
                else if($code != 0){
                    $journalPic = new JournalPic();
                    $journalPic->code = $code;
                    $journalPic->pic = $fileName;
                    $journalPic->save();
                }
                else{
                    unlink($location);
                    echo false;
                }

                echo json_encode(['url' => asset('uploaded/journal/description/' . $fileName)]);
            }
            else
                echo false;
        }
        else
            echo false;

        return;
    }

    public function storeJournal(Request $request)
    {
        if(isset($request->code) && isset($request->id) && isset($request->name) && isset($request->categoryId)){
            if($request->code == 0 && $request->id != 0){
                $kind = 'edit';
                $journal = Journal::find($request->id);
                if($journal == null){
                    echo json_encode(['status' => 'nok2']);
                    return;
                }
            }
            else if($request->id == 0 && $request->code != 0){
                $kind = 'new';
                $journal = new Journal();
                $journal->userId = Auth::user()->id;
                $journal->lang = JournalCategory::find($request->categoryId)->lang;
                if(!isset($_FILES['pic']) || $_FILES['pic']['error'] != 0){
                    echo json_encode(['status' => 'nok3']);
                    return;
                }
            }
            else{
                echo json_encode(['status' => 'nok1']);
                return;
            }

            $journal->name = $request->name;
            $journal->slug = makeSlug($request->slug);
            $journal->keyword = $request->keyword;
            $journal->meta = $request->meta;
            $journal->seoTitle = $request->seoTitle;
            $journal->categoryId= $request->categoryId;
            $journal->summery = $request->summery;
            $journal->text = $request->description;
            if($request->releaseDateType == 'now')
                $journal->releaseDate = Carbon::now()->format('Y-m-d');
            else if($request->releaseDateType == 'future' && isset($request->releaseDate) && $request->releaseDate != null)
                $journal->releaseDate = $request->releaseDate;
            $journal->save();

            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $fileName = time() . $_FILES['pic']['name'];

                $location = __DIR__ . '/../../../public/uploaded/journal/';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/mainPics';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $fileName;

                if(storeImage($_FILES['pic']['tmp_name'], $location)){
                    \File::delete('uploaded/journal/mainPics/' . $journal->pic);
                    $journal->pic = $fileName;
                    $journal->save();
                }
            }

            $tags = json_decode($request->tags);
            $query = '';
            foreach ($tags as $tag){
                $t = Tags::where('tag', $tag)->first();
                if($t == null){
                    $t = new Tags();
                    $t->tag = $tag;
                    $t->save();
                }

                if($query != '')
                    $query .= ' ,';
                $query .= '(Null, ' . $journal->id . ', ' . $t->id . ')';
            }

            JournalTag::where('journalId', $journal->id)->delete();
            if($query != '')
                \DB::select('INSERT INTO journalTags (id, journalId, tagId) VALUES ' . $query);

            if($kind == 'new' && $request->code != 0)
                JournalPic::where('code', $request->code)->update(['journalId' => $journal->id, 'code' => null]);

            $journalPics = JournalPic::where('journalId', $journal->id)->get();
            foreach ($journalPics as $pic){
                if(strpos($journal->text, $pic->pic) === false){
                    \File::delete('uploaded/journal/description/' . $pic->pic);
                    $pic->delete();
                }
            }

            echo json_encode(['status' => 'ok', 'result' => $journal->id]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    private function deleteLimboPic(){
        $limbo = JournalPic::whereNull('journalId')->get();
        foreach ($limbo as $item){
            $diff = Carbon::now()->diffInDays($item->created_at);
            if($diff > 0){
                \File::delete('uploaded/journal/description/' . $item->pic);
                $item->delete();
            }
        }

        $location = __DIR__ . '/../../../public/uploaded/journalsLimbo';
        if(is_dir($location)){
            $scan = scandir($location);
            foreach ($scan as $item) {
                if($item != '.' && $item != '..'){
                    try {
                        rmdir($location . '/' . $item);
                    }
                    catch (\Exception $exception){
                        continue;
                    }
                }
            }
        }

    }

    public function checkSeo(Request $request)
    {
        $text = $request->description;
        $meta = $request->meta;
        $key = $request->keyword;
        $seoTitle = $request->seoTitle;
        $slug= $request->slug;
        $name = $request->name;

        $goodResultCount = 0;
        $warningResultCount = 0;
        $badResultCount = 0;

        $badResult = '';
        $warningResult = '';
        $goodResult = '';

        $uniqueKey = true;
        $uniqueSlug = true;
        $uniqueSeoTitle = true;


        if($key != null){
            $keyWordDensity = SeoController::keywordDensity($text, $key);
            if($keyWordDensity > 1.5 && $keyWordDensity < 3) {
                $goodResult .= '<div style="color: green;">Repetition of the keyword in the text is appropriate. : %'. $keyWordDensity .'</div>';
                $goodResultCount++;
            }
            else if($keyWordDensity >= 3){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">Repetition of the keyword in the text is too high. : %'. $keyWordDensity .'</div>';
            }
            else {
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">Repetition of the keyword in the text is too low. : %'. $keyWordDensity .'</div>';
            }

            $keywordDensityInTitle = SeoController::keywordDensityInTitle($text, $key);
            if($keywordDensityInTitle > 30) {
                $goodResult .= '<div style="color: green;">Subheadings include keyword. : %'. $keywordDensityInTitle .'</div>';
                $goodResultCount++;
            }
            else if($keywordDensityInTitle == 9999){
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">Use the keyword in the subheadings. : %'. $keywordDensityInTitle .'</div>';
            }

            $keywordInMeta = SeoController::keywordInText($meta, $key, 'common');
            if($keywordInMeta > 0) {
                $goodResult .= '<div style="color: green;">Meta descriptions include keyword</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">Use the keyword in the meta description.</div>';
            }

            if($slug != null){
                $keywordInSlugTitle = SeoController::keywordInText($slug, $key, 'slug');
                if($keywordInSlugTitle > 0) {
                    $goodResult .= '<div style="color: green;">slug contains the keyword</div>';
                    $goodResultCount++;
                }
                else {
                    $badResultCount++;
                    $badResult .= '<div style="color: red;">Use the keyword in slug.</div>';
                }
            }

            $keywordInSeoTitle = SeoController::keywordInText($seoTitle, $key, 'common');
            if($keywordInSeoTitle > 0) {
                $goodResult .= '<div style="color: green;">SEO title is a keyword term</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">Use the keyword in the SEO title.</div>';
            }

            $keywordNumWord = count(explode(' ', $key));
            if($keywordNumWord > 0 && $keywordNumWord <= 6){
                $goodResult .= '<div style="color: green;">The length of the keyword is appropriate</div>';
                $goodResultCount++;
            }
            else if($keywordNumWord > 6 && $keywordNumWord <= 10){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">The length of the keyword can be more optimal</div>';
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">The length of the keyword is inappropriate </div>';
            }

            $keywordSimiralInDataBase = SeoController::uniqueJournalKeyword($key, $request->id);
            if(!$keywordSimiralInDataBase){
                $uniqueKey = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">Your keyword is completely repetitive. Change it.</div>';
            }
            else{
                $goodResult .= '<div style="color: green;"> Your keyword is completely new.</div>';
                $goodResultCount++;
            }

            if($name != null && $name != ''){
                $keywordInName = SeoController::keywordInText($name, $key, 'common');
                if($keywordInSeoTitle > 0) {
                    $goodResult .= '<div style="color: green;">The name contains a key phrase</div>';
                    $goodResultCount++;
                }
                else {
                    $badResultCount++;
                    $badResult .= '<div style="color: red;">Use the key phrase in the name.</div>';
                }
            }

        }

        if($slug != null){
            $slugInDataBase = SeoController::uniqueJournalSlug($slug, $request->id);
            if($slugInDataBase) {
                $goodResult .= '<div style="color: green;">Slug is unique.</div>';
                $goodResultCount++;
            }
            else {
                $uniqueSlug = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">Your Slug is duplicate. Please change it</div>';
            }
        }

        if($seoTitle != null) {
            $seoTitleInDataBase = SeoController::uniqueJournalSeoTitle($seoTitle, $request->id);
            if ($seoTitleInDataBase) {
                $goodResult .= '<div style="color: green;">Seo title is unique.</div>';
                $goodResultCount++;
            } else {
                $uniqueSeoTitle = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">Your Seo title is duplicate. Please change it</div>';
            }
        }

        if($text != null){
            $allWordCountInPTotal = SeoController::allWordCountInP($text);
            $allWordCountInP = $allWordCountInPTotal[0];
            $parError = $allWordCountInPTotal[1];

            if($allWordCountInP[0] > 300 && $allWordCountInP[0] <= 900){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;"> Your page is currently considered a post. If you want the article to be considered, change its length to more than 900 words :' . $allWordCountInP[0] . ' word </div>';
            }
            else if($allWordCountInP[0] > 900){
                $goodResult .= '<div style="color: green;">Your page is considered a specialized text or article and is appropriate.:' . $allWordCountInP[0] . ' word</div>';
                $goodResultCount++;
            }
            else{
                $warningResultCount++;
                $warningResult .= '<div style="color: #ffb938;">Your text is shorter than 300 words and is not recommended at all. :' . $allWordCountInP[0] . 'کلمه</div>';
            }

            if(count($allWordCountInP) > 3) {
                $goodResult .= '<div style="color: green;">The readability of your text is appropriate.</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">The number of paragraphs in your text is very small and may cause problems in its readability</div>';
            }

            if($parError != 0){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">The length of some paragraphs is more than 150 words, which is not recommended. Paragraph: ' . $parError . '</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">The length of the paragraphs is appropriate.</div>';
                $goodResultCount++;
            }

            $titles = SeoController::getAllTitles($text);
            if(count($titles[0]) < 1){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">Your text is probably not comprehensive, it is better to use subheadings</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">The use of subheadings is appropriate</div>';
                $goodResultCount++;
            }
            if($titles[1] == true){
                $badResultCount++;
                $badResult .= '<div style="color: red;">Distribution of headlines in the text is not appropriate. </div>';
            }
            else{
                $goodResult .= '<div style="color: green;">Headline distribution is appropriate</div>';
                $goodResultCount++;
            }

            $sentences = SeoController::sentencesCount($text);
            if($sentences > 30){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">More than thirty percent of sentences have more than twenty words that are not suggested.: %' . $sentences . '</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">All sentences below have 20 words.</div>';
                $goodResultCount++;
            }

            $img = SeoController::imgInText($text);
            if($img[0] > 0){
                $goodResult .= '<div style="color: green;">Your text has a photo.</div>';
                $goodResultCount++;
            }
            else{
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">Your text needs a photo. Without photos, the text is not readable.</div>';
            }
            if($img[1] == $img[0]){
                $goodResult .= '<div style="color: green;">All photos have an alternative phrase.</div>';
                $goodResultCount++;
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">Define an alternative phrase for all photos.</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">It is necessary to write the text of the journal.</div>';
        }

        $countAllWord = SeoController::myWordsCount($text);
        $countError = $countAllWord[1];
        $allWord = $countAllWord[0];
        if(count($countError) > 0){
            $warningResultCount++;
            $warningResult .= '<div style="color: #dec300;">The number of repetitions of the following words is more than 10%:';
            $warningResult .= '<ul>';
            foreach ($countError as $item){
                $warningResult .= '<li>' . $item . '</li>';
            }
            $warningResult .= '</ul></div>';
        }

        if(mb_strlen($seoTitle, 'utf8') <= 85 && mb_strlen($seoTitle, 'utf8') > 60){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">The length of the SEO title is appropriate.</div>';
        }
        else if(mb_strlen($seoTitle, 'utf8') > 85){
            $badResultCount++;
            $badResult .= '<div style="color: red;">The SEO title is too long: ' . mb_strlen($seoTitle, 'utf8') . ' character</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">The SEO title is too short: ' . mb_strlen($seoTitle, 'utf8') . ' character</div>';
        }

        if(mb_strlen($meta, 'utf8') <= 160 && mb_strlen($meta, 'utf8') > 120){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">The length of the meta description is appropriate.</div>';
        }
        else if(mb_strlen($meta, 'utf8') > 160){
            $badResultCount++;
            $badResult .= '<div style="color: red;">Meta\'s explanation is too long: ' . mb_strlen($meta, 'utf8') . ' character</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">The meta description is too short: ' . mb_strlen($meta, 'utf8') . ' character</div>';
        }

        echo json_encode(['status' => 'ok', 'result' => [$badResult, $warningResult, $goodResult, $badResultCount, $warningResultCount, $uniqueKey, $uniqueSlug, $uniqueSeoTitle]]);
        return;
    }


    public function deleteJournal(Request $request)
    {
        if(isset($request->id)){
            if(Journal::deleteWithPics($request->id))
                echo json_encode(['status' => 'ok']);
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

}
