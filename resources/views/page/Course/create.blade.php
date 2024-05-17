<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('Course.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        اضافة مقرر</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <label for="name">اسم المقرر
                                <span class="text-danger">*
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="name" type="text" name="name" class="form-control"
                                value="{{ old('name') }}" placeholder="أدخل اسم المقرر" required="الحقل مطلوب">
                        </div>
                        <div class="col-12">
                            <label for="hours">عدد الساعات
                                <span class="text-danger">*
                                    @error('hours')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="hours" type="number" name="hours" class="form-control" min="1" max="4"
                                value="{{ old('hours') }}" placeholder="أدخل عدد الساعات" required="الحقل مطلوب">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn btn-primary">اضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
