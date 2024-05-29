<div class="modal fade" id="studentInquirie{{ $studentInquirie->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('studentInquirie.update', 'test') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        {{ $title . ' الطالب ' . $studentInquirie->student->full_name }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input id="id" type="hidden" name="id" class="form-control"
                            value="{{ $studentInquirie->id }}" required="الحقل مطلوب">
                        <div class="col-12">
                            <label for="subject">
                                عنوان ال{{ $title }}
                            </label>
                            <input id="subject" type="text" readonly class="form-control"
                                value="{{ $studentInquirie->subject }}">
                        </div>
                        <div class="col-12 mt-2">
                            <label for="message">
                                وصف ال{{ $title }}
                            </label>
                            <textarea id="message" class="form-control" readonly>{{ $studentInquirie->message }}</textarea>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="reply_message">
                                الرد على ال{{ $title }}
                                <span class="text-danger">*
                                    @error('reply_message')
                                        {{ $reply_message }}
                                    @enderror
                                </span>
                            </label>
                            <textarea id="reply_message" class="form-control" name="reply_message">{{ old('reply_message') ?? ($studentInquirie->reply_message ?? '') }}</textarea>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="status">
                                حالة ال{{ $title }}
                                <span class="text-danger">*
                                    @error('status')
                                        {{ $status }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" name="status" aria-placeholder="مطلوب" required>
                                <option value="" disabled selected>اختر نوع ال{{$title}}</option>
                                <option value='لم يتم الرد بعد' @if (old('status') =='لم يتم الرد بعد' || $studentInquirie->status == 'لم يتم الرد بعد') selected @endif>لم يتم الرد بعد
                                </option>
                                <option value="قيد المعالجة" @if (old('status') == "قيد المعالجة" || $studentInquirie->status == "قيد المعالجة") selected @endif>قيد المعالجة
                                </option>
                                <option value="مرفوض" @if (old('status') == "مرفوض" || $studentInquirie->status == "مرفوض") selected @endif>مرفوض
                                </option>
                                <option value="تم حلها" @if (old('status') == "تم حلها" || $studentInquirie->status == "تم حلها") selected @endif>تم حلها
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn btn-primary">ارسال</button>
                </div>
            </form>
        </div>
    </div>
</div>
