<?php
namespace Modules\Cms\Http\Controllers\Builder;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class CmsEditorController extends Controller
{
    public function redirectToElementor($slug)
    {
        $response = Http::get(config('cms.wp_api_url') . '/wp/v2/pages', [
        'slug' => $slug,
        '_fields' => 'id'
        ]);

        $page = $response->json()[0] ?? abort(404, 'Page not found');

        $url = config('cms.wp_url') . "/wp-admin/post.php?post={$page['id']}&action=elementor";

        return redirect()->away($url);
    }
}
