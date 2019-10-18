<?php
/**
 * Created by PhpStorm.
 * User: uriel
 * Date: 2018/7/12 0012
 * Time: 17:04
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ThemeController extends Controller
{
    function addTheme(Request $request){
        return view('theme/add_theme');
    }
}