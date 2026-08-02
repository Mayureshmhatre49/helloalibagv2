{{--
    Validation error summary for the listing inquiry forms.

    Shared by _sidebar and the Eat / Real Estate category pages, which each
    render their own copy of the inquiry form — keeping the summary here means
    a fix lands on all of them at once.

    A failed submission reloads the page, and on a long listing page the form
    is usually well below the fold, so the visitor lands back at the top with
    no idea anything went wrong. The script scrolls the summary into view and
    focuses the first bad field.
--}}
@if($errors->any())
    <div id="inquiry-errors"
         class="mb-3 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-xs scroll-mt-24"
         role="alert" aria-live="assertive" tabindex="-1">
        <p class="font-bold mb-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">error</span>
            {{ $errors->count() === 1 ? 'Please fix this before sending:' : 'Please fix the following before sending:' }}
        </p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    @once
        @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                var box = document.getElementById('inquiry-errors');
                if (!box) return;

                // Land the visitor on the problem rather than the top of the page.
                box.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Focus the first field that actually failed, so they can just type.
                var firstBad = document.querySelector('.border-red-300, [aria-invalid="true"]');
                if (firstBad && typeof firstBad.focus === 'function') {
                    setTimeout(function () { firstBad.focus({ preventScroll: true }); }, 400);
                }
            });
            </script>
        @endpush
    @endonce
@endif
