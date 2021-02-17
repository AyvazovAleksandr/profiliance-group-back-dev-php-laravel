<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class ShortLinksController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Получаем оригинальную ссылку и возвращаем json строку с оригинальной и сокрашенной ссылкой
     */
    public function getShortLink(Request $request) {
        //Проверяем, есть ли http* в начале ссылки, если нет, то добавляем
        $original_link = (preg_match('/^http/', $request->link)) ? $request->link : 'http://'.$request->link;
        $app_url = \Config::get('app.url');
        $alphabet = array_merge(... [range('a', 'z'), range('A', 'Z'), range(0, 9)]);
        do {
            shuffle($alphabet);
            $alphabet = array_slice($alphabet, 0, 6);
            $random_short = implode('', $alphabet);
        } while (Link::where('short', $random_short)->first() !== null);
        $short_link = $app_url .'/'.$random_short;
        $link = new Link();


        $link->original_link = $original_link;
        $link->short_link =  $short_link;
        $link->short = $random_short;
        $link->save();
        return response()->json([
            'original_link' => $original_link,
            'short_link' => $short_link
        ]);
    }

    /**
     * @param $short_link
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * Находим сокращенную ссылку и редиректим на оригинал или возвращаем 404
     */

    public function redirectOriginalLink($short_link) {
        $link = Link::where('short', $short_link)->first();
        if($link !== null) {
            return redirect($link->original_link);
        } else {
            abort('404');
        }
    }
}
