@props([
    'headers' => []
])

<div class="table-container">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    @foreach($headers as $header)
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
