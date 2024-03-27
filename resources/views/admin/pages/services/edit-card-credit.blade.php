@extends('admin.layouts.app')
@section('title')
    @lang('Card')
@endsection
@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0">
        <div class="card-body">
            <form method="post" action="{{route('admin.card.update')}}" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-between">

                    <div class="col-sm-6 col-md-8">
                        <div class="form-group ">
                            <label>@lang('Card Title')</label>
                            <input type="text" name="card_title" value="{{old('card_title',$card->card_title)}}" required="required" class="form-control form-control-sm">
                            <div class="invalid-feedback">@lang('Please fill in the site title')</div>
                            @error('card_title')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="token">@lang('Card Token')</label>
                            <textarea class="form-control" id="token" rows="5" name="token">{{old('token',$card->token)}}</textarea>
                            <div class="invalid-feedback">@lang('Please fill in the card token')</div>

                            @error('token')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Select Provider')</label>
                            <select class="form-control" id="card_type" name="card_type">
                                <option value="{{old('card_type',$card->card_type)}}" selected
                                        hidden>@lang('Change Provider')</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id  }}"
                                            @if($card->card_type == $provider->id ) selected @endif>{{ $provider->name  }}</option>
                                @endforeach
                            </select>

                        </div>
                        @if($errors->has('card_type'))
                            <div class="error text-danger mt-2">@lang($errors->first('card_type')) </div>
                        @endif
                    </div>
                        <div class="form-group ">
                            <label class="d-block">@lang('Status')</label>
                            <div class="custom-switch-btn w-md-25">
                                <input type='hidden' value='1' name='status'>
                                <input type="checkbox" name="status" class="custom-switch-checkbox" id="status" value = "0" {{ $card->status == 0 ? 'checked': '' }} >
                                <label class="custom-switch-checkbox-label" for="status">
                                    <span class="custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{old('id',$card->id)}}" required="required" class="form-control form-control-sm">
                        <div class="submit-btn-wrapper mt-md-5 mt-0 text-center text-md-left">
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span>@lang('Update Card')</span> </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
     <script>
         "use strict";
        $(document).ready(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

            $('#image').on('change',function(){
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#upload_image_form').on('submit',function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('photo')}}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                    this.reset();
                    alert('Image has been uploaded successfully');
                    },
                    error: function(data){
                    console.log(data);
                    }
                });
            });

        });
     </script>
@endpush
