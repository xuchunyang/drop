<x-layout :title="$project->name">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>CNAME</th>
            <th>Custom Domain</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $project->id }}</td>
            <td>{{ $project->name }}</td>
            <td><a href="//{{ $project->name }}">{{ $project->name }}</a></td>
            <td>
                @if($project->custom_domain)
                    <a href="//{{ $project->custom_domain }}">{{ $project->custom_domain }}</a>

                    @if(!$cname)
                        <p>没有 CNAME 记录！请给添加 CNAME 记录指向 <code>{{ $project->name }}</code>.</p>
                    @elseif ($cname !== $project->custom_domain)
                        <p>CNAME 记录不匹配！实际：<code>{{ $cname }}</code>，期望：<code>{{ $project->name }}</code></p>
                    @endif
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <form action="{{ route('projects.update', ['project' => $project]) }}" method="post">
        @csrf
        @method('PATCH')
        <p>
            <label>
                自定义域名：
                <input type="text" name="custom_domain"
                       value="{{ old('custom_domain', $project->custom_domain) }}">
            </label>
        </p>
        <p>
            <button>绑定域名</button>
        </p>
    </form>
</x-layout>
