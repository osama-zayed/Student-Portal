<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('College-New.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        اضافة خبر </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <label for="title">اسم الخبر 
                                <span class="text-danger">*
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="title" type="text" name="title" class="form-control"
                                value="{{ old('title') }}" placeholder="أدخل اسم الخبر " required="الحقل مطلوب">
                        </div>
                        <div class="col-12">
                            <label for="description">وصف الخبر 
                                <span class="text-danger">*
                                    @error('description')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <textarea id="description" type="text" name="description" class="form-control"
                            value="{{ old('description') }}" placeholder="أدخل وصف الخبر " required="الحقل مطلوب" cols="30" rows="10"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="file">الخبر 
                                <span class="text-danger">*
                                    @error('file')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="file" type="file" name="file" class="form-control"
                                value="{{ old('file') }}" placeholder="أدخل الخبر " required="الحقل مطلوب">
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
