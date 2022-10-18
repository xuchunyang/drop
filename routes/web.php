<?php

use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProjectController::class, 'create']);

Route::resource('projects', ProjectController::class)
    ->only(['store', 'update', 'show', 'index']);

// 在申请 SSL 证书前，为了安全期间，首先验证域名，用于 Caddy：
// https://caddyserver.com/docs/caddyfile/options#on-demand-tls
Route::get('/check', function (Request $request) {
    $custom_domain = $request->get('domain');
    if ($project = Project::where(['custom_domain' => $custom_domain])->first()) {
        if ($project->cname() === $custom_domain) {
            return response('', 200);
        }
    }
    return response('', 404);
});

// FIXME custom_domain.com --(PHP)-> project #3 --(静态文件，Caddy)；先代理到 PHP，处理之后返回 Caddy 处理
// 用于 public2/index.php
Route::get('/projects/search_by_custom_domain/{custom_domain?}', function (string $custom_domain = null) {
    if (!$custom_domain) return [];
    return Project::where(['custom_domain' => $custom_domain])->get();
});
