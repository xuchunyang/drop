<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\UploadedFile;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $project->name = \Atrox\Haikunator::haikunate(["tokenLength" => 0]) . '-' . $project->id;
        $project->save();

        // ['example.com/index.html', 'example.com/images/logo.png', ...]
        $validated['paths'] = json_decode($validated['paths'], true);
        foreach ($validated['files'] as $i => $item) {
            // example.com/images/logo.png => images
            $path = $project->name . '/' . \App\Helper::dirname($validated['paths'][$i]);

            /** @var UploadedFile $file */
            $file = $item;
            $file->storeAs($path, $file->getClientOriginalName(), 'public');
        }

        return redirect(route('projects.show', ['project' => $project]));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     */
    public function show(Project $project)
    {
        return view('project.show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateProjectRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
