<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Atrox\Haikunator;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Spatie\Dns\Dns;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = Project::create();
        $project->name = Haikunator::haikunate(["tokenLength" => 0]) . '-' . $project->id . '.' . $request->getHost();
        $project->save();

        // ['example.com/index.html', 'example.com/images/logo.png', ...]
        $validated['paths'] = json_decode($validated['paths'], true);
        foreach ($validated['files'] as $i => $item) {
            // example.com/images/logo.png => images
            $path = $project->name . '/' . Helper::dirname($validated['paths'][$i]);

            /** @var UploadedFile $file */
            $file = $item;
            $file->storeAs($path, $file->getClientOriginalName(), 'public');
        }

        return redirect(route('projects.show', ['project' => $project]))->with('success', '项目新建成功!');
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     */
    public function show(Project $project)
    {
        $cname = null;
        if ($project->custom_domain) {
            $dns = new Dns();
            if ($records = $dns->getRecords($project->custom_domain, 'CNAME')) {
                $cname = $records[0]->host();
            }
        }
        return view('project.show', [
            'project' => $project,
            'cname' => $cname,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        return back()->with('success', '更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
