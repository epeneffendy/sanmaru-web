<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\Http\Request;

/**
 * @group Blogs Information
 *
 * APIs for Blogs information
 */
class BlogController extends Controller
{
    /**
     * [GET] Retrieve Blogs list
     *
     * Retrieve blogs list, can be filled with limit, blog category, and offset
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @queryParam category slug of blog category to be queried. Example: kegiatan-sekolah
     * @queryParam published published or unpublish blog status. Example 1 / 0
     * @response {
     *      * "data": [
     *         {
     *             "title": "Ibadat Jalan Salib Virtual SMP SANMARU",
     *             "short_desc": "Pekan suci bagi umat Katholik maupun kristiani dalam rangkaian merayakan Paskah 2021,",
     *             "slug": "ibadat-jalan-salib-virtual-smp-sanmaru",
     *             "category": "Berita",
     *             "category_slug": "berita",
     *             "content": "<p style=\"margin-bottom: 20px; padding: 0px; color: rgb(99, 99, 99); font-family: Helvetica, Arial, sans-serif; font-size: 16px;\">Pekan suci bagi umat Katholik maupun kristiani dalam rangkaian merayakan Paskah 2021, masih tetap terjadi dalam suasana duka. Betapa tidak, Negeri ini tengah dilanda pandemi virus&nbsp;<em>corona</em>&nbsp;(Covid-19). Sejak pertengahan Masa Prapaskah awal Maret tahun lalu, pandemi sudah mulai dirasakan</p><p style=\"margin-bottom: 20px; padding: 0px; color: rgb(99, 99, 99); font-family: Helvetica, Arial, sans-serif; font-size: 16px;\">Lantas bagaimana kita tetap mengenang kembali kisah sengsara Yesus di tengah pandemi corona saat ini?</p><p style=\"margin-bottom: 20px; padding: 0px; color: rgb(99, 99, 99); font-family: Helvetica, Arial, sans-serif; font-size: 16px;\">Nah, warga SMP Santa Maria II Sidoarjo tetap bisa melakukan ibadat jalan salib secara virtual melalui link youtube sekolah yang dipandu oleh Pak Rio selaku guru agama.&nbsp;Seluruh peserta didik didampingi oleh wali kelas dalam melaksanakan ibadat jalan salib ini dengan khusyuk dan penuh khidmat</p>",
     *             "publish_date": "2021-03-12 12:00:00",
     *             "published": 1,
     *             "featured_image": "http://sanmaru.test/images/featured_image/cdndo_323237397068706e46756e7776.jpeg"
     *         },
     *         {
     *             "title": "Aksi Asik Guru Nyentrik Masa Kini",
     *             "short_desc": "aksi guru Santa Maria",
     *             "slug": "aksi-asik-guru-nyentrik-masa-kini",
     *             "category": "Seputar Guru",
     *             "category_slug": "seputar-guru",
     *             "content": "<p><span style=\"background-color:rgb(255,255,255);color:rgb(99,99,99);\">Jumat, 26 Februari 2021- Asik menarik siapa sih yang tidak suka? Aksi guru nyentrik mampu membuat anak-anak terpesona dan bersemangat dalam mengikuti pembiasaan pagi senam hari itu di chanel youtube SD Santa Maria 2 Sidoarjo. Menjaga kebugaran tubuh di masa pandemi ini bisa kita lakukan salah satunya dengan senam, guna menambah kekebalan imun dalam diri kita. Tak kalah dengan siswa-siswinya guru jaman now juga harus up to date menghadapi siswa-siswi milenial. Dunia Tik Tok sudah menjadi konsumsi publik sehari-hari karena konten-kontenya yang menarik dan kekinian. Dengan bersemangat guru-guru nyentrik ini dengan lihainya memadukan gerakan senam zumba dengan gerakan Tik Tok yang lagi viral. Liak-liuknya gemulai tak kalah dengan seleb Tik Tok. Menurut Ki Hadjar Dewantara (dalam Dantes, 2017), pendidikan merupakan usaha memanusiakan manusia. Untuk bisa mendidik anak menjadi manusia seutuhnya, guru haruslah memahami dunia anak. Bukan anak yang harus memahami dunia guru. Ini kunci penting dalam membuka pola pikir guru kekinian. (Yov)</span></p>",
     *             "publish_date": "2021-03-10 12:00:00",
     *             "published": 1,
     *             "featured_image": "http://sanmaru.test/images/featured_image/cdndo_32323739706870796a4c4b6d41.jpeg"
     *         },
     *     ],
     *     "meta": {
     *         "limit": 0,
     *         "offset": 0,
     *         "total": 6
     *     }
     * }
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(Request $request, BlogService $service)
    {
        $total = $service->countBlogs();
        $data = $service->listBlogs(
            $request->input('offset'),
            $request->input('limit'),
            $request->input('category'),
            $request->input('published')
        );

        $meta = array(
            'limit' => intval($request->input('limit')),
            'offset' => intval($request->input('offset')),
            'total' => $total
        );

        $return = array(
            'data'    => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [GET] Retrieve Blog
     *
     * Retrieve blog detail based on slug
     *
     * @urlParam slug slug name of the blog 
     *
     * @response {
     *     "data": {
     *         "title": "Aksi Asik Guru Nyentrik Masa Kini",
     *         "short_desc": "aksi guru Santa Maria",
     *         "slug": "aksi-asik-guru-nyentrik-masa-kini",
     *         "category": "Seputar Guru",
     *         "category_slug": "seputar-guru",
     *         "content": "<p><span style=\"background-color:rgb(255,255,255);color:rgb(99,99,99);\">Jumat, 26 Februari 2021- Asik menarik siapa sih yang tidak suka? Aksi guru nyentrik mampu membuat anak-anak terpesona dan bersemangat dalam mengikuti pembiasaan pagi senam hari itu di chanel youtube SD Santa Maria 2 Sidoarjo. Menjaga kebugaran tubuh di masa pandemi ini bisa kita lakukan salah satunya dengan senam, guna menambah kekebalan imun dalam diri kita. Tak kalah dengan siswa-siswinya guru jaman now juga harus up to date menghadapi siswa-siswi milenial. Dunia Tik Tok sudah menjadi konsumsi publik sehari-hari karena konten-kontenya yang menarik dan kekinian. Dengan bersemangat guru-guru nyentrik ini dengan lihainya memadukan gerakan senam zumba dengan gerakan Tik Tok yang lagi viral. Liak-liuknya gemulai tak kalah dengan seleb Tik Tok. Menurut Ki Hadjar Dewantara (dalam Dantes, 2017), pendidikan merupakan usaha memanusiakan manusia. Untuk bisa mendidik anak menjadi manusia seutuhnya, guru haruslah memahami dunia anak. Bukan anak yang harus memahami dunia guru. Ini kunci penting dalam membuka pola pikir guru kekinian. (Yov)</span></p>",
     *         "publish_date": "2021-03-10 12:00:00",
     *         "published": 1,
     *         "featured_image": "http://sanmaru.test/images/featured_image/cdndo_32323739706870796a4c4b6d41.jpeg"
     *     }
     *}
     *
     * @response 404 {
     *    "message": "Not Found"
     *}
     *
     * @authenticated
     */
    public function show($slug, BlogService $service)
    {
        $data = $service->getBlog($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
