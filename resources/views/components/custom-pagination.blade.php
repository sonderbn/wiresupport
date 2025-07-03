@if ($paginator->hasPages())
    <style>
        .page-link.rounded-circle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            transition: background-color 0.2s ease;
        }

        .page-link.rounded-circle:hover {
            background-color: #d6d6d6;
        }
    </style>

    <nav>
        <ul class="pagination justify-content-center">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-circle mx-1 text-muted border-0">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-circle mx-1 text-dark border-0"
                       href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                </li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-circle mx-1 text-dark border-0"
                       href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-circle mx-1 text-muted border-0">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
