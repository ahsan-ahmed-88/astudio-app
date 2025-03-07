<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function store(Request $request) {
        try {
            $project = Project::create($request->only('name', 'status'));

            if ($request->has('attributes')) {
                foreach ($request->all()['attributes'] as $value) {
                    $attr = Attribute::create([
                        'name' => $value['name' ],
                        'type' => $value['type' ]
                    ]);
                    AttributeValue::updateOrCreate([
                        'attribute_id' => $attr->id,
                        'entity_id' => $project->id
                    ], [
                        'value' => $value['value' ]
                    ]);
                }
            }
            return response()->json($project->load('attributes.attribute'), 201);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function index(Request $request) {
        try {
            $projects = Project::with('attributes.attribute')->get();
            if ($request->has('filters')) {
                foreach ($request->filters as $key => $value) {
                    $projects = $projects->filter(fn($p) => optional($p->attributes->firstWhere('attribute.name', $key))->value == $value);
                }
            }
            return response()->json($projects);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function update($project, Request $request) {

        try {

            return response()->json(Project::where('id', $project)->update($request->all()), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy($project, Request $request) {

        try {

            return response()->json(Project::where('id', $project)->delete(), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
