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

Route::get('/', function () {
    return redirect(route('projects.create'));
});

Route::resource('projects', ProjectController::class)
    ->only(['create', 'store']);

Route::get('/projects/{project:name}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::get('/projects/search_by_custom_domain/{custom_domain?}', function (string $custom_domain = null) {
    if (!$custom_domain) return [];
    return Project::where(['custom_domain' => $custom_domain])->get();
});

// https://caddyserver.com/docs/caddyfile/options#on-demand-tls
Route::get('/check', function (Request $request) {
    $project = Project::where(['custom_domain' => $request->get('domain')])->exists();
    return response('', $project ? 200 : 404);
});
