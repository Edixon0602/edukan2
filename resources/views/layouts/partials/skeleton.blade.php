<!-- Skeleton Loader Template -->
<div id="global-skeleton-loader" class="hidden w-full max-w-6xl mx-auto px-6 py-10 animate-pulse">
    <!-- Header skeleton -->
    <div class="h-8 bg-white/5 rounded-lg w-1/3 mb-4"></div>
    <div class="h-4 bg-white/5 rounded-lg w-1/2 mb-10"></div>

    <!-- Cards grid skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @for($i = 0; $i < 3; $i++)
            <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-5 flex flex-col gap-4">
                <div class="aspect-video w-full bg-white/5 rounded-xl"></div>
                <div class="h-5 bg-white/5 rounded-lg w-3/4"></div>
                <div class="h-4 bg-white/5 rounded-lg w-full"></div>
                <div class="h-4 bg-white/5 rounded-lg w-5/6"></div>
                <div class="h-9 bg-white/5 rounded-lg w-full mt-4"></div>
            </div>
        @endfor
    </div>
</div>
