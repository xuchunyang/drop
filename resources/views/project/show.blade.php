<x-layout :title="$project->name">

    <p>访问：<a href="//{{ $project->name }}">{{ $project->name }}</a></p>

    @if($project->custom_domain)
        @php
            $cname = $project->cname();
        @endphp
        @if(!$cname)
            <p>查询不到 {{ $project->custom_domain }} 的 CNAME 记录，请确保你已经添加，如果你已经添加，请等待 DNS 生效！</p>
        @elseif(($cname !== $project->name))
            <p>{{ $project->custom_domain }} 的 CNAME 记录不匹配，请确保你 CNAME 设置正确，并且等待 DNS 缓存刷新！</p>
            <p>目前查询的解析值为：{{ $cname }}.</p>
        @else
            <p>
                自定义域名：
                <a href="//{{ $project->custom_domain }}">{{ $project->custom_domain }}</a>
            </p>
        @endif
    @endif

    <form action="{{ route('projects.update', ['project' => $project]) }}" method="post">
        @csrf
        @method('PATCH')
        <h2>绑定域名</h2>
        <p>你可以绑定自己的域名，请首先到你的域名提供商网站，添加一条 CNAME 记录，指向 <code>{{$project->name}}</code></p>
        <p>
            <label>
                你的域名：
                <input type="text" name="custom_domain"
                       value="{{ old('custom_domain', $project->custom_domain) }}">
            </label>
        </p>
        <p>
            <button>绑定域名</button>
        </p>
    </form>
</x-layout>
