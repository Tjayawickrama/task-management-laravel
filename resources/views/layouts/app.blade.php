<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PawTasks')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                   
                    colors: {
                        indigo: {
                            50: '#effafa', 100: '#d5f2f1', 200: '#aee5e4', 300: '#7cd1d1',
                            400: '#46b5b8', 500: '#1f9aa0', 600: '#0e7c86', 700: '#0e666d',
                            800: '#10525a', 900: '#11444b'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background:
                radial-gradient(circle at 88% 8%, rgba(167, 160, 224, .55), transparent 45%),
                radial-gradient(circle at 8% 92%, rgba(255, 255, 255, .55), transparent 40%),
                linear-gradient(135deg, #a9d3d3 0%, #c8e0e0 50%, #cfcdea 100%);
            background-attachment: fixed;
        }
       
        main :is(div, form, a, section).bg-white {
            background-color: rgba(255, 255, 255, .62);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-color: rgba(255, 255, 255, .8);
        }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-800 antialiased">



<div class="mx-auto max-w-5xl p-3 sm:p-5 lg:py-8">
    <div class="overflow-hidden rounded-3xl border border-white/60 bg-white/40 shadow-xl backdrop-blur-xl">

        {{-- Top bar --}}
        <header class="flex items-center justify-between border-b border-white/60 bg-white/20 px-5 py-4 sm:px-8">
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#0e7c86] to-[#2aa6a0] text-white shadow">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="6" cy="10" r="2"/><circle cx="10" cy="5.5" r="2"/>
                        <circle cx="14" cy="5.5" r="2"/><circle cx="18" cy="10" r="2"/>
                        <path d="M12 11c-3 0-5.5 3-5.5 5.5 0 1.7 1.3 2.5 2.7 2.5 1 0 1.8-.5 2.8-.5s1.8.5 2.8.5c1.4 0 2.7-.8 2.7-2.5C17.5 14 15 11 12 11z"/>
                    </svg>
                </span>
                <span class="text-xl font-bold tracking-tight text-slate-900">Paw<span class="text-teal-700">Tasks</span></span>
            </a>
        </header>

        {{-- Content --}}
        <main class="min-w-0 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- Success toast --}}
@if (session('success'))
    <div id="toast"
         class="fixed right-4 top-4 z-50 flex max-w-sm items-start gap-3 rounded-xl border border-emerald-200 bg-white p-4 shadow-lg transition-opacity duration-500">
        <svg class="mt-0.5 h-5 w-5 flex-none text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm font-medium text-slate-700">{{ session('success') }}</p>
    </div>
@endif

{{-- Delete confirmation modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.3 3.4c-.9 1.5.2 3.35 1.9 3.35h14.8c1.7 0 2.8-1.85 1.9-3.35L13.9 4.4c-.85-1.45-2.95-1.45-3.8 0L2.7 16.15zM12 15.75h.01"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-900">Delete task?</h3>
        <p class="mt-2 text-sm text-slate-600">
            Are you sure you want to delete
            <span id="delete-title" class="font-semibold text-slate-900"></span>?
            This action cannot be undone.
        </p>
        <form id="delete-form" method="POST" class="mt-6 flex justify-end gap-2">
            @csrf
            @method('DELETE')
            <button type="button" id="delete-cancel"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Cancel
            </button>
            <button type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                Yes, delete
            </button>
        </form>
    </div>
</div>

<script>
    // Filter / search scroll position 
const SCROLL_KEY = 'keep-scroll-y';

function rememberScroll() {
    sessionStorage.setItem(SCROLL_KEY, window.scrollY);
}

document.addEventListener('click', (e) => {
    if (e.target.closest('a[data-keep-scroll], [data-keep-scroll] a')) rememberScroll();
});

document.addEventListener('submit', (e) => {
    if (e.target.matches('[data-keep-scroll]')) rememberScroll();
});

window.addEventListener('load', () => {
    const y = sessionStorage.getItem(SCROLL_KEY);
    if (y !== null) {
        sessionStorage.removeItem(SCROLL_KEY);
        requestAnimationFrame(() => window.scrollTo(0, parseInt(y, 10)));
    }
});

    // Toast auto-hide
    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => { toast.style.opacity = '0'; }, 3500);
        setTimeout(() => { toast.remove(); }, 4000);
    }

    // Delete modal
    const modal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-delete-url]');
        if (btn) {
            deleteForm.action = btn.dataset.deleteUrl;
            document.getElementById('delete-title').textContent = '"' + btn.dataset.deleteTitle + '"';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            return;
        }
        if (e.target === modal || e.target.closest('#delete-cancel')) closeModal();
    });

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
</script>
</body>
</html>