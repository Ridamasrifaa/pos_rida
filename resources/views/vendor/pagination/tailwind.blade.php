@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 cursor-default rounded-xl">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">
                    Next
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-500 bg-white border border-slate-300 cursor-default rounded-xl">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-700">
                    Menampilkan
                    <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl gap-1">
                    {{-- Tombol Previous (<) --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-xl">
                            &lsaquo;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                            &lsaquo;
                        </a>
                    @endif

                    {{-- Tombol Next (>) --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                            &rsaquo;
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-xl">
                            &rsaquo;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif