<x-layout>
    <h1>静态网站托管</h1>
    <p>
        拖拽或者选择你的网站文件夹，点击【上传】，你的网站就上线了.
    </p>
    <ul>
        <li>
            我们会给你分配一个单独的域名，由于我们的域名使用了 Wildcard Certificate，所以生成的子域名自带 HTTPS；
        </li>
        <li>
            随后你还可以绑定你自己的域名，利用 Caddy 的 <a
                href="https://caddyserver.com/docs/automatic-https#on-demand-tls">On-Demand
                TLS</a> 技术，我们会自动替代你申请 HTTPS 证书；
        </li>
    </ul>
    <form action="{{ route('projects.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="paths" value="[]">
        <p>
            <label>选择文件夹：<input type="file" name="files[]" webkitdirectory></label>
        </p>
        <p>
            <button type="submit">上传</button>
        </p>
    </form>

    <script>
        const filesInput = document.querySelector('[name="files[]"]');
        const pathsInput = document.querySelector('[name="paths"]');

        filesInput.addEventListener('change', (event) => {
            const files = [...event.target.files];
            pathsInput.value = JSON.stringify(files.map((file) => file.webkitRelativePath));

            const maxTotalSize = 20 * 1024 * 1024;
            const maxPerFileSize = 2 * 1024 * 1024;
            let totalSize = 0;
            for (const file of files) {
                if (file.size > maxPerFileSize) {
                    alert(`文件过大！${file.webkitRelativePath} ${file.size}KB 超过了 ${maxPerFileSize}KB`);
                    filesInput.value = '';
                    pathsInput.value = '[]';
                    break;
                }
                totalSize += file.size;
            }

            if (totalSize > maxTotalSize) {
                alert(`目录过大！ ${totalSize}KB 超过了 ${maxTotalSize}KB`);
                filesInput.value = '';
                pathsInput.value = '[]';
            }
        });
    </script>
</x-layout>
