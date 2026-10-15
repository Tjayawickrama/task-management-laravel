@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        Please fix the highlighted fields below.
    </div>
@endif

<div class="space-y-5">
    <div>
        <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" value="{{ old('title', $task->title ?? '') }}"
               placeholder="e.g. Prepare weekly report"
               class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-1
                      {{ $errors->has('title') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
        @error('title') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">
            Description <span class="font-normal text-slate-400">(optional)</span>
        </label>
        <textarea id="description" name="description" rows="5" placeholder="Add more details..."
                  class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-1
                         {{ $errors->has('description') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">{{ old('description', $task->description ?? '') }}</textarea>
        @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="due_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                Due date <span class="font-normal text-slate-400">(optional)</span>
            </label>
            <input type="date" id="due_date" name="due_date"
                   value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}"
                   class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-1
                          {{ $errors->has('due_date') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
            @error('due_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-slate-700">
                Status <span class="text-red-500">*</span>
            </label>
            <select id="status" name="status"
                    class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-1
                           {{ $errors->has('status') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}"
                        @selected(old('status', isset($task) ? $task->status->value : 'pending') === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @error('status') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>