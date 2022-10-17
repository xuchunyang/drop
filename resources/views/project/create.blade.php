<x-layout title="Create Project">
    @if($errors->any())
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

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
