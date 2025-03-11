<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
</head>

<body class="bg-gray-100 py-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow-lg">
        <h1 class="text-2xl font-bold mb-6">Send Email</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form novalidate action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="emails" class="block text-sm font-medium text-gray-700">Recipient Emails</label>
                <input type="text" name="emails[]" id="emails"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    placeholder="Add multiple emails separated by commas" required>
                @error('emails')                <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="cc" class="block text-sm font-medium text-gray-700">CC Emails (optional)</label>
                <input type="text" name="cc[]" id="cc" class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    placeholder="Add CC emails separated by commas">
                @error('cc')               <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" id="subject"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                @error('subject')                <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                <textarea name="body" id="body" rows="10" class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    required>{{ old('body') }}</textarea>
                @error('body')                <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                <input type="file" name="attachment" id="attachment"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded">
                @error('attachment')          <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Send
                    Email</button>
            </div>
        </form>
    </div>

    <script>
        ClassicEditor
            .create(document.querySelector('#body'))
            .then(editor => {
                document.querySelector('form').addEventListener('submit', function () {
                    document.querySelector('#body').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>

</html>