<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('SchoolYear.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        اضافة عام دراسي</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <label for="name">اسم العام دراسي
                                <span class="text-danger">*
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="name" type="text" name="name" class="form-control"
                                value="{{ old('name') }}" placeholder="أدخل اسم العام دراسي" required="الحقل مطلوب">
                        </div>
                        <div class="col-12">
                            <label for="start_date">تاريخ بداية الترم
                                <span class="text-danger">*
                                    @error('start_date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="start_date" type="month" name="start_date" class="form-control"
                             value="{{ old('start_date') }}"
                                placeholder="تاريخ بداية الترم " required="الحقل مطلوب">
                        </div>
                        <div class="col-12">
                            <label for="end_date">تاريخ نهاية الترم
                                <span class="text-danger">*
                                    @error('end_date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="end_date" type="month" name="end_date" class="form-control"
                             value="{{ old('end_date') }}"
                                placeholder="تاريخ نهاية الترم " required="الحقل مطلوب">
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
