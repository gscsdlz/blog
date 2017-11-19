<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 18:05
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\TypeModel;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function edit(Request $request)
    {
        $type = new TypeModel();
        $navbars = $type->get_navbar();
        return view('admin.type_edit', [
            'types' => $type->types,
            'navbars' => $navbars,
            'menu' => 'blog@type'
        ]);
    }

    public function change(Request $request)
    {
        $newName = $request->get('newName', '');
        $lastName = $request->get('lastName', '');
        if(strlen($newName) > 0 && strlen($lastName) > 0)
        {
            $type = new TypeModel(false);
            $res = $type->update($lastName, $newName);

            if($res->getPayload() == "OK")
                return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function add(Request $request)
    {
        $name = $request->get('name');
        if(strlen($name) != 0) {
            $type  = new TypeModel();
            $status = $type->add($name);
            return response()->json(['status' => $status]);
        }
        return response()->json(['status' => false]);
    }

    public function navbar(Request $request)
    {
        $lists = $request->get('data');
        $type = new TypeModel(false);
        $type->set_navbar($lists);
        return response()->json(['status' => true]);
    }

    public function del(Request $request)
    {
        $name = $request->get('name');
        $type = new TypeModel(false);
        $status = $type->del($name);
        return response()->json(['status' => $status]);

    }

}