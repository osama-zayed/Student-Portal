<div class="modal fade" id="show_image{{ $CollegeNew['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <img src='{{ asset($CollegeNew['image']) }}' class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
</div>