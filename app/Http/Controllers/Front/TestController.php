<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Receipt;
use App\Models\Test\Meta;
use App\Models\UploadedFile;
use App\Models\User;
use App\Providers\PostsServiceProvider;
use App\Providers\UploadServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Test\Post as PostOld;


class TestController extends Controller
{
    public function postsConverter()
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            if ($post->meta('text') or $post->meta('abstract')) {
                $post->text = $post->meta('text');
                $post->abstract = $post->meta('abstract');
                $post->starts_at = $post->meta('start_time');
                $post->ends_at = $post->meta('end_time');
                $post->updateMeta([
                    'text'     => false,
                    'abstract' => false,
                ]);
                $post->save();
            }
        }

        return "DONE :D";
    }

    public function index()
    {
        die();
        $file = UploadedFile::findBySlug(6, 'id');
        dd($file->related_files, $file->related_files_pathname);
    }

    public function states()
    {
        $states = [
            'alborz',
            'ardabil',
            'azerbaijan-east',
            'azerbaijan-west',
            'bushehr',
            'chahar-mahaal-bakhtiari',
            'fars',
            'gilan',
            'golestan',
            'hamedan',
            'hormozgan',
            'ilam',
            'isfahan',
            'kerman',
            'kermanshah',
            'khorasan-north',
            'khorasan-razavi',
            'khorasan-south',
            'khuzestan',
            'kohgiluyeh-boyer-ahmad',
            'kurdistan',
            'lorestan',
            'markazi',
            'mazandaran',
            'qazvin',
            'qom',
            'semnan',
            'sistan-baluchestan',
            'tehran',
            'yazd',
            'zanjan',
        ];
        $enabledStates = [
            'alborz',
            'ardabil',
            'azerbaijan-east',
            'azerbaijan-west',
            'bushehr',
            'chahar-mahaal-bakhtiari',
            'fars',
            'gilan',
            'golestan',
            'hamedan',
            'ilam',
            'isfahan',
            'kerman',
            'kermanshah',
            'khorasan-north',
            'khorasan-razavi',
            'khorasan-south',
            'khuzestan',
            'kohgiluyeh-boyer-ahmad',
            'lorestan',
            'markazi',
            'mazandaran',
            'semnan',
            'sistan-baluchestan',
            'tehran',
            'yazd',
            'zanjan',
        ];
        foreach ($states as $key => $state) {
            $states[$key] = [
                'name'   => $state,
                'active' => (in_array($state, $enabledStates)) ? true : false,
                'link'   => 'https://' . $state . '.ehda.center',
            ];
        }

        return view('front.iranmap.main', compact('states'));
    }

    public function gallery_archive()
    {
        return view('front.gallery.archive.main');
    }

    public function gallery_single()
    {
        return view('front.gallery.single.main');
    }

    public function post_single()
    {
        $showSideBar = true;
        $sideBarItems = [
            ['label' => 'عنوان تستی عنوان تستی', 'link' => '#'],
            ['label' => 'عنوان خبر عنوان تستی', 'link' => '#'],
            ['label' => 'تستی خبر عنوان تستی', 'link' => '#'],
            ['label' => 'عنوان تستی خبر عنوان تستی', 'link' => '#'],
            ['label' => 'عنوان تستی خبر عنوان تستی', 'link' => '#'],
            ['label' => 'عنوان تستی خبر عنوان تستی', 'link' => '#'],
        ];
        return view('front.test.single_post.main', compact('showSideBar', 'sideBarItems'));
    }

    public function post_archive()
    {
        $twoColumns = false;
        return view('front.test.archive_post.main', compact('twoColumns'));
    }

    public function about()
    {
        $contactFormHTML = PostsServiceProvider::showPost('contact-us', ['showError' => false]);
        return view('front.test.about.main', compact('contactFormHTML'));
    }

    public function volunteers()
    {
        return view('front.test.volunteers.main');
    }

    public function faqs()
    {
        $faqsHTML = PostsServiceProvider::showList(['type' => 'faq']);

        $newFaqPost = Post::findBySlug('ask-question');
        if ($newFaqPost->exists and $newFaqPost->canRecieveComments()) {
            $getNewFaq = true;
            $newFaqForm = PostsServiceProvider::showPost($newFaqPost);
        } else {
            $getNewFaq = false;
        }
        return view('front.test.faqs.main', compact('faqsHTML', 'getNewFaq', 'newFaqForm'));
    }

    public function works_send()
    {
        $postsPrefix = 'send-work-';
        UploadServiceProvider::setUserType('client');
        UploadServiceProvider::setSection('work');

        // get related posts
        $posts = Post::selector(['type' => 'commenting'])
            ->where('slug', 'like', "$postsPrefix%")
            ->get();


        // remove posts that are related to inactive file types (in config/upload.php)
        foreach ($posts as $key => $post) {
            $fileType = str_replace($postsPrefix, '', $post->slug);
            if (UploadServiceProvider::isActive($fileType)) {
                $post->fileType = $fileType;
            } else {
                $posts->forget($key);
            }
        }

        $sendingArea = view('front.test.works.sending_area.main', compact('posts'));
        $postContentHTML = PostsServiceProvider::showPost('send-works-text', ['externalBlade' => $sendingArea]);
        return view('front.test.works.main', compact('postContentHTML'));
    }

    public function works_upload()
    {
        return response()->json(['success' => 'fileName.gif']);
    }
}
