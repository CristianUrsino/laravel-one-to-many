<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::All();
        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $formData = $request->validated();
        
        if($request->hasfile('image')){
            $image = Storage::put('image', $formData['image']); 
            $formData['image'] = $image;
        }
        $userId = Auth::id();
        $formData['user_id'] = $userId;
        $project = Project::create($formData);
        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::All();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $formData = $request->validated();
        $formData['user_id'] = $project->user_id;
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::delete($project->image);
            }

            $path = Storage::put('images', $formData['image']);
            $formData['image'] = $path;
        }
        $project = Project::find($project->id);
        $project->update($formData);    
        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        {
            if($project->image){
                Storage::delete($project->image);
            }
            $project->delete();
            return to_route('admin.projects.index')->with('message',"$project->name eliminato");
        }
    }
}
