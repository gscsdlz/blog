<?php
/**
 * Created by IntelliJ IDEA.
 * User: liruipeng1
 * Date: 2018/8/22
 * Time: 17:32
 */

namespace App\Http\Middleware;

use App\Model\BlogModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SlugDirect
{
    public function handle(Request $request, Closure $next, $graud = null)
    {
        $name = $request->route('bid');

        $blog = new BlogModel();
        $res = $blog->list_all(0);

        foreach ($res[0] as $k => $row) {
            $title = str_replace(" ", "+", strtolower($row['title']));

            if ($name == $title) {
                $request->route()->setParameter("bid", $k);
                break;
            }
        }

        return $next($request);
    }
}