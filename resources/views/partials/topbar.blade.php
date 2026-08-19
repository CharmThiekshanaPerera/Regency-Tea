{{--
    Slim utility bar above the main sticky header — external partner link and
    social icons. Deliberately NOT sticky (scrolls away with the page) so it
    doesn't compete with the primary nav for permanent screen space, and kept
    to a single short row so it can't reintroduce the mobile header-overflow
    issue the main <header> already had to be fixed for.
--}}
<div class="border-b border-stone-800 bg-stone-950 text-stone-300">
    <div class="mx-auto flex max-w-7xl items-center justify-end gap-4 px-4 py-1.5 text-xs">
        <a href="https://hyleysteaonline.com/" target="_blank" rel="noopener"
           class="hidden font-medium text-stone-300 transition hover:text-white sm:inline">
            Shop Hyleys Online
        </a>

        <div class="flex items-center gap-3">
            <a href="https://www.facebook.com/RegencyTeas" target="_blank" rel="noopener" aria-label="Facebook"
               class="text-stone-400 transition hover:text-white">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.89h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94Z"/>
                </svg>
            </a>
            <a href="https://www.instagram.com/regency_teas/" target="_blank" rel="noopener" aria-label="Instagram"
               class="text-stone-400 transition hover:text-white">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2c2.72 0 3.06.01 4.12.06 1.06.05 1.79.22 2.43.47.66.26 1.21.6 1.76 1.15.5.5.9 1.1 1.15 1.76.25.64.42 1.37.47 2.43.05 1.06.06 1.4.06 4.12s-.01 3.06-.06 4.12c-.05 1.06-.22 1.79-.47 2.43a4.9 4.9 0 0 1-1.15 1.76c-.5.5-1.1.9-1.76 1.15-.64.25-1.37.42-2.43.47-1.06.05-1.4.06-4.12.06s-3.06-.01-4.12-.06c-1.06-.05-1.79-.22-2.43-.47a4.9 4.9 0 0 1-1.76-1.15 4.9 4.9 0 0 1-1.15-1.76c-.25-.64-.42-1.37-.47-2.43C2.01 15.06 2 14.72 2 12s.01-3.06.06-4.12c.05-1.06.22-1.79.47-2.43.26-.66.6-1.21 1.15-1.76A4.9 4.9 0 0 1 5.44.54C6.08.29 6.81.12 7.87.07 8.94.02 9.28 0 12 0Zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8.25a3.25 3.25 0 1 1 0-6.5 3.25 3.25 0 0 1 0 6.5ZM17.4 4.85a1.17 1.17 0 1 0 0 2.34 1.17 1.17 0 0 0 0-2.34Z"/>
                </svg>
            </a>
            <a href="http://www.youtube.com/@regencyteas47" target="_blank" rel="noopener" aria-label="YouTube"
               class="text-stone-400 transition hover:text-white">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.51 3.5 12 3.5 12 3.5s-7.51 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14C4.49 20.5 12 20.5 12 20.5s7.51 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81ZM9.6 15.6V8.4l6.27 3.6-6.27 3.6Z"/>
                </svg>
            </a>
            <a href="https://www.linkedin.com/company/regencyteas/about/" target="_blank" rel="noopener" aria-label="LinkedIn"
               class="text-stone-400 transition hover:text-white">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.56V9h3.56v11.45Z"/>
                </svg>
            </a>
        </div>
    </div>
</div>
