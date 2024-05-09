<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('Department.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        اضافة قسم</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <label for="transfer_id">اسم القسم
                                <span class="text-danger">*
                                    @error('id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="transfer_id" type="text" name="name" class="form-control"
                                value="{{ old('id') }}" placeholder="أدخل اسم القسم" required="الحقل مطلوب">
                        </div>
                        <div class="col-12 mt-10">
                            <label for="transfer_id">رقم الهاتف
                                <span class="text-danger">*
                                    @error('id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="transfer_id" type="number" name="phone_number" class="form-control" pattern="[0-9]+(\.[0-9]+)?" title="يرجى إدخال أرقام فقط"
                                value="{{ old('id') }}" placeholder="أدخل رقم هاتف القسم" required="الحقل مطلوب">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn btn-success">اضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
