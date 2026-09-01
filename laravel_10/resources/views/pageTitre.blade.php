<div class="row gx-3">
    <div class="col-12">
        <div class="mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @if(isset($parent))
                        <li class="breadcrumb-item">
                            {{ $parent }}
                        </li>
                    @endif

                    <li class="breadcrumb-item active" aria-current="page">
                        <h5 class="fw-bold" >{{ $title }}</h5>
                    </li>
                </ol>
            </nav>

            @if(isset($subtitle))
                <small class="text-muted">
                    {{ $subtitle }}
                </small>
            @endif
        </div>
    </div>
</div>