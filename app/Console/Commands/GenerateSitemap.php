<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Modules\Admin\Models\Post;
use Modules\Admin\Models\Category;
use Modules\Admin\Models\Menu;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Generate an XML Sitemap';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sitemap = Sitemap::create();
        
        // Menambahkan URL posting beserta detailnya
        Post::get()->each(function (Post $post) use ($sitemap) {
            // Menambahkan URL untuk setiap posting
            $menuId = Menu::where('id', $post->menu_id)->first();
            $postHasCat = Category::select("categoryzation.slug as slug")
            ->leftJoin('post_has_category','post_has_category.tags_id','=','categoryzation.id')
            ->where('post_has_category.post_id', $post->id)
            ->first();

            $str = '';
            if($menuId->type == 8){
                $str .= 'blog'.'/';
            } else if(isset($postHasCat->slug)){
                $str .$postHasCat->slug.'/';
            }

            $postData = get_content_json($post);
            
            //konten sitemap
            foreach($postData as $key => $value){
                $sitemap->add(
                    Url::create($key."/".$str."{$value['slug']}")
                        ->setLastModificationDate($post->updated_at) // Tanggal terakhir diperbarui
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
            }
        });

        // Menambahkan halaman statis lainnya, jika ada
        Menu::get()->each(function (Menu $menu) use ($sitemap) {
            
            foreach(slug_get_content_json($menu) as $key => $value){
                if($menu->type == 1){
                    $sitemap->add(Url::create($key.'/'.$value)->setPriority(0.7));
                }
            }
        });

        // Simpan sitemap ke file
        $sitemap->writeToFile(public_path('sitemap.xml'));

        return Command::SUCCESS;
    }
}