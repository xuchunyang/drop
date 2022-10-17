<x-layout :title="$project->name">
    <p>
        {{ $project->name }}
    </p>

    <p>
        <a href="{{ Storage::url($project->name) }}/index.html">{{ Storage::url($project->name) }}/index.html</a>
    </p>

    <p>
        {{ $project->name }}
    </p>
</x-layout>
