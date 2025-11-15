<?php

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\User;
use Modules\Admin\Models\Post;
use Modules\Pos\Models\Outlet;

function get_category()
{
    return DB::table("categoryzation_menu")->get()->toArray();
}

function log_back($id)
{
    return DB::table("post")->where('id', $id)->get()->first()->menu_id;
}

function outlet()
{
    return Outlet::whereNull('deleted_at')->get();
}

function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}


function reserved_words_of_url($get_url)
{
    $arr_word_url = [
        'posting',
        'posting_edit',
        'posting_image',
        'category',
        'menu'
        //	'posting_image_edit'
    ];

    if (in_array($get_url[1], $arr_word_url)) {
        if ($get_url[1] == 'posting') {
            return '/posting_add/' . $get_url[2];
        } else if ($get_url[1] == 'posting_image') {
            return '/posting_image_add/' . $get_url[2] . '/' . $get_url[3];
        } else if ($get_url[1] == 'posting_image_edit') {
            return '/posting_image_add/' . $get_url[2] . '/' . $get_url[3];
        } else if ($get_url[1] == 'category') {
            return '/category_add/' . $get_url[2];
        } else if ($get_url[1] == 'menu') {
            return '/menu_add';
        }
    } else {
        return '';
    }
}

// function db_build_list(){
// 	return DB::connection('mysql2')->table('databases_lists')->where('deleted_at', null)->get();
// }

function reserved_word_position($get_url)
{
    $arr_word_url = [
        'posting_add',
        'posting_edit',
        'posting_image',
        'showing_images',
        'posting_image_add',
        'posting_image_edit',
        'category_add',
        'category_edit',
        'log',
        'comment_post',
        'menu_add',
        'menu_edit'
    ];

    if (in_array($get_url[1], $arr_word_url)) {
        if ($get_url[1] == 'posting_add') {
            return '/posting/' . $get_url[2];
        } else if ($get_url[1] == 'posting_edit') {
            return '/posting/' . $get_url[2];
        } else if ($get_url[1] == 'posting_image') {
            return '/posting/' . $get_url[2];
        } else if ($get_url[1] == 'showing_images') {
            return '/posting/' . $get_url[2];
        } else if ($get_url[1] == 'posting_image_add') {
            return '/posting_image/' . $get_url[2] . '/' . $get_url[3];
        } else if ($get_url[1] == 'posting_image_edit') {
            return '/posting_image/' . $get_url[2] . '/' . $get_url[3];
        } else if ($get_url[1] == 'log') {
            return '/posting/' . log_back($get_url[2]);
        } else if ($get_url[1] == 'comment_post') {
            return '/posting/' . log_back($get_url[2]);
        } else if ($get_url[1] == 'category_add') {
            return '/category/' . $get_url[2];
        } else if ($get_url[1] == 'category_edit') {
            return '/category/' . $get_url[2];
        } else if ($get_url[1] == 'menu_add') {
            return '/menu';
        } else if ($get_url[1] == 'menu_edit') {
            return '/menu';
        }
    } else {
        return '';
    }
}

function get_menu()
{
    $sql_menu = "SELECT
			`menu`.`id` as `id`,
			`menu`.`title` AS `title`,
			`menu`.`type` AS `type`,
			`menu`.`icon` AS `icon`
			from `menu`
			GROUP BY `menu`.`id`
		 ";

    $sql_category_menu = "SELECT
		 	`categoryzation_menu`.`id` as `id`,
		 	`categoryzation_menu`.`title` as `title`,
		 	`categoryzation_menu`.`type` as `type`,
		 	`categoryzation_menu`.`icon` AS `icon`
		 	from `categoryzation_menu`
		 	GROUP BY `categoryzation_menu`.`id`";

    $union = $sql_menu . ' UNION ' . $sql_category_menu;
    $query = DB::select($union);
    return $query;
}

function site_config()
{
    return DB::table('post_site_configuration')->where('id', 1)->get()->first();
}

function get_menu_id($id)
{
    return DB::table("menu")->where('id', $id)->get()->first();
}

function productItem($id)
{
    return DB::table('product')->where('id', $id)->get()->first();
}

function brandItem($id)
{
    return DB::table('brand')->where('id', $id)->get()->first();
}

function categoryItem($id)
{
    return DB::table('category')->where('id', $id)->get()->first();
}

function get_data_by_menu_global($menu_id)
{
    return DB::table('post')
        ->leftJoin('post_has_category', 'post_has_category.post_id', '=', 'post.id')
        ->where([
            'post.menu_id' => $menu_id,
            'post.deleted_at' => null,
            'post_has_category.tags_id' => 1811731122059639
        ])
        ->orderBy('post.id', 'desc')
        ->limit(4)->get();
}

function porductName($id)
{
    return DB::table('product')->where('id', $id)->first();
}

if (!function_exists('cms_helper_function')) {
    function get_data_by_menu_global2($menu_id)
    {
        if (env('SALES') == 1 && !empty(session()->get('selected_outlet_on'))) {
            return Post::where(['menu_id' => $menu_id, 'outlet_id' => session()->get('selected_outlet_on')])->with('categories')->get();
        } else {
            return Post::where('menu_id', $menu_id)->with('categories')->get();
        }
        // return DB::table('post')
        //     ->leftJoin('post_has_category', 'post_has_category.post_id', '=', 'post.id')
        //     ->where([
        //         'post.menu_id' => $menu_id,
        //         'post.deleted_at' => null,
        //     ])
        //     ->orderBy('post.id', 'desc')
        //     ->limit(4)->get();
    }
}

function get_dark_light_image($post)
{
    return DB::table('post_image')->where('post_id', $post)->get();
}

function cek_parent($child) {}

function dbuilder_table($caller, $title, $search = false, $order = false, $class = '')
{
    $buildingCall = ['data' => $caller, 'name' => $caller, 'title' => $title, 'searchable' => $search, 'orderable' => $order, 'className' => $class];
    return $buildingCall;
}

function get_menu_text($child_id, $child_is_true = false)
{
    $arr = json_decode(DB::table("menu_order")->where('id', 1)->get()->first()->menu_text, true);
    $arr_data = [];

    foreach ($arr as $key => $val) {
        if (!isset($val['children'])) {
            $arr_data[$val['id']] = $val['id'];
            return $arr_data;
        } else {
            foreach ($val['children'] as $key2 => $val2) {
                if (isset($val2['children'])) {
                    foreach ($val2['children'] as $key3 => $val3) {

                        if ($val3['id'] == $child_id) {
                            if ($child_is_true == false) {
                                $arr_data[$val3['id']] = $val['id'];
                                return $arr_data;
                            } else {
                                $arr_data[$val3['id']] = $val2['id'];
                                return $arr_data;
                            }
                        }
                    }
                } else {
                    if ($val2['id'] == $child_id) {
                        $arr_data[$val2['id']] = $val['id'];
                        return $arr_data;
                    }
                }
            }
        }
    }
}

function extras_custom($menu)
{
    $fMenu = DB::table('menu')->select('id')->where('custom_links', ltrim($menu, '/'))->first();
    return $fMenu;
}

function category_data($id)
{
    return DB::table("categoryzation")->where('id_menu_category', $id)->get()->toArray();
}

function category_bypost($id)
{
    return DB::table("categoryzation")->where('id', $id)->get()->first();
}

function category_by_id($id)
{
    return DB::table('post_has_category')->where('post_id', $id)->get()->first();
}

function get_post_meta($id)
{
    return DB::table('post_meta')->where('post_id', $id)->get();
}

function category_by_post_id($post, $menu_id_category = '')
{
    $tb = DB::table('post_has_category')
        ->leftJoin('categoryzation', 'categoryzation.id', '=', 'post_has_category.tags_id')
        ->where([
            'post_has_category.post_id' => $post,
            'post_has_category.deleted_at' => null,
            'categoryzation.deleted_at' => null
        ]);

    if (!empty($menu_id_category)) {
        $tb->where('categoryzation.id_menu_category', $menu_id_category);
    }

    $hasil = $tb->get()->first();

    return $hasil;
}

function get_needed($id)
{
    $sql = "SELECT
        `menu`.`id`,
        `menu`.`title`,
        `menu`.`type`,
        `menu`.`icon`
         from `menu`
        WHERE `menu`.`id` = '" . $id . "'";
    $sql .= " UNION ";
    $sql .= "SELECT
        `categoryzation_menu`.`id`,
        `categoryzation_menu`.`title`,
        `categoryzation_menu`.`type`,
        `categoryzation_menu`.`icon` from `categoryzation_menu`
        WHERE `categoryzation_menu`.`id` = '" . $id . "'";

    return DB::select($sql);
}

function get_menu_order()
{
    return DB::table("menu_order")->where('id', 1)->get()->first()->menu_text ?? json_encode(array());
}

function get_content_json($arr)
{
    return json_decode($arr->content, true);
}

function slug_get_content_json($arr)
{
    return json_decode($arr->slug, true);
}

function get_title_json($arr)
{
    return json_decode($arr->title, true);
}

function get_image($location, $image)
{
    return asset(Storage::disk('public')->url($location . '/' . $image));
}

function get_image_photo($img)
{
    return asset(Storage::disk('public')->url($img));
}

function get_imagef($id)
{
    $user = User::find($id);
    $users = ($user->getMeta('photo') ? asset(Storage::disk('public')->url($user->getMeta('photo'))) : asset(Storage::disk('public')->url('vectors/user-default.png')));

    return $users;
}

function get_item_from_category($category)
{
    return DB::table('post_has_category as phc')
        ->select('p.id', 'p.title', 'p.content', 'p.location', 'p.image', 'p.slug')
        ->join('post as p', 'p.id', '=', 'phc.post_id')
        ->where('p.deleted_at', null)
        ->where('phc.deleted_at', null)
        ->where('phc.tags_id', $category)
        ->get();
}

function get_menu_order_child($id)
{
    $child = DB::table("menu_order")->where('id', 1)->get()->first()->menu_text ?? json_encode(array());
    $dec = json_decode($child, true);

    foreach ($dec as $index => $value) {
        if (isset($value['children'])) {
            //dd($value['children']);
            foreach ($value['children'] as $index2 => $value2) {
                if (isset($value2['children'])) {
                    foreach ($value2['children'] as $index3 => $value3) {
                        if ($value3['id'] == $id) {
                            return 'sama';
                        }
                    }
                }

                if ($id === $value2['id']) {
                    return 'sama';
                }
            }
        }
    }
}

function getMidtransNotif($json)
{
    return json_decode($json, true);
}
