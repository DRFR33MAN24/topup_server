@extends('admin.layouts.app')
@section('title')
    @lang($user->username)
@endsection
@section('content')


    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-sm-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Profile')</h4>
                        <div class="form-group">
                            <div class="image-input">
                                <label for="image-upload" id="image-label"><i
                                        class="fas fa-upload"></i></label>
                                <img id="image_preview_container" class="preview-image"
                                        style="max-width: 200px"
                                        src="{{getFile(config('location.user.path').$user->image)}}"
                                        alt="@lang('User Image')">
                            </div>
                            @error('image')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <h3> @lang(ucfirst($user->username))</h3>
                        <p>@lang('Joined At') @lang($user->created_at->format('d M,Y h:i A')) </p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User information')</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Email')
                                <div>{{ $user->email }}</div></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">@lang('First Name')
                                <div>{{ $user->f_name }}</div></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Last Name')
                                <div>{{ $user->l_name }}</div></li>


                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Status')
                                <span
                                    class="badge badge-{{($user->status==1) ? 'success' :'danger'}} success badge-pill">{{($user->status==1) ? trans('Active') : trans('Inactive')}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Balance')
                                <span>{{ getAmount($user->balance, config('basic.fraction_number')) }} @lang(config('basic.currency')) </span>
                            </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Balance')
                                <span>{{ getAmount($user->lbalance, config('basic.fraction_number')) }} LBP</span>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User action')</h4>


                        <div class="btn-list">
                            <button class="btn btn-primary waves-effect waves-light" type="button" data-toggle="modal"
                                    data-target="#balance">
                                <span class="btn-label"><i class="fas fa-hand-holding-usd"></i></span>
                                @lang('Add/Subtract Balance')
                            </button>
                                                        <button class="btn btn-primary waves-effect waves-light" type="button" data-toggle="modal"
                                    data-target="#balancelbp">
                                <span class="btn-label"><i class="fas fa-hand-holding-usd"></i></span>
                                @lang('Add/Subtract LBP Balance')
                            </button>



                            <a href="{{ route('admin.user-order',$user->id) }}"
                               class="btn btn-info waves-effect waves-light">
                                <span class="btn-label"><i class="fas fa-history"></i></span> @lang('Order')
                            </a>


                            <a href="{{ route('admin.user.transaction',$user->id) }}"
                               class="btn btn-info waves-effect waves-light">
                                <span class="btn-label"><i class="fas fa-exchange-alt"></i></span> @lang('Transaction')
                            </a>



                            <!-- <a href="{{ route('admin.user.customRate',$user->id) }}"
                               class="btn btn-info waves-effect waves-light">
                                <span class="btn-label"><i class="fa fa-tags"></i></span>
                                @lang('Custom Rate')
                            </a> -->


                            <!-- <a href="{{ route('admin.send-email',$user->id) }}"
                               class="btn btn-info waves-effect waves-light">
                                <span class="btn-label"><i class="fas fa-envelope-open"></i></span> @lang('Send Email')
                            </a> -->

                            <!-- <button class="btn btn-primary  loginAccount" type="button" data-toggle="modal"
                                    data-target="#signIn"
                                    data-route="{{route('admin.user-loginAccount',$user->id)}}">
                                <span class="btn-label"><i class="fas fa-sign-in-alt"></i></span>
                                @lang('Login as User')
                            </button> -->

                            <!-- <a href="{{ route('admin.user.fundLog',$user->id) }}"
                               class="btn btn-info waves-effect waves-light">
                                <span class="btn-label"><i class="fas fa-money-bill-alt"></i></span> @lang('Fund Log')
                            </a> -->



                        </div>


                    </div>
                </div>

            </div>

            <div class="col-sm-12">
                <!-- <div class="card ">
                    <div class="card-body">

                        <h4 class="card-title text-white">@lang('API Key')
                            <button type="button"
                                    class="btn btn-primary btn-sm  text-white float-right  waves-effect generateBtn"><i
                                    class="fa fa-spinner"></i> @lang('Generate Key')</button>
                        </h4>

                        <div class="form-group ">
                            <h6 class="font-weight-bold">@lang('API KEY')</h6>
                            <div class="input-group">
                                <input type="text" value="{{$user->api_token}}"
                                       class="form-control form-control-lg api-token" id="referralURL"
                                       readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text copytext copyBoard" id="copyBoard">
                                            <i class="fa fa-copy"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucfirst($user->username) }} @lang('Information')</h4>
                        <form method="post" action="{{ route('admin.user-update', $user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('First Name')</label>
                                        <input class="form-control" type="text" name="firstname" value="{{ $user->f_name }}"
                                               required>
                                        @error('firstname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Last Name')</label>
                                        <input class="form-control" type="text" name="lastname" value="{{ $user->l_name }}"
                                               required>
                                        @error('lastname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Username')</label>
                                        <input class="form-control" type="text" name="username"
                                               value="{{ $user->username }}" required>
                                        @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> -->

                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Phone Number')</label>
                                        <input class="form-control" type="text" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

<!-- 
                                @if(0 < count($languages))
                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label>@lang('Preferred Language')</label>

                                            <select name="language_id" class="form-control">
                                                <option value="" disabled>@lang('Select Language')</option>
                                                @foreach($languages as $item)
                                                    <option value="{{$item->id}}" @if($item->id == $user->language_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                @endif -->
                                <!-- <div class="col-sm-12">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <textarea class="form-control" name="address"
                                                  rows="2">{{ $user->address }}</textarea>
                                        @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> -->
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>@lang('Status')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status" {{ $user->status == 0 ? 'checked' : '' }} >
                                                <label class="custom-switch-checkbox-label" for="status">
                                                    <span class="status custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <label>@lang('Is Reseller')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='is_reseller'>
                                                <input type="checkbox" name="is_reseller" class="custom-switch-checkbox"
                                                       id="is_reseller" {{ $user->is_reseller == 0 ? 'checked' : '' }} >
                                                <label class="custom-switch-checkbox-label" for="is_reseller">
                                                    <span class=" custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <!-- <div class="col-sm-3">
                                            <label>@lang('Email Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='email_verification'>
                                                <input type="checkbox" name="email_verification"
                                                       class="custom-switch-checkbox"
                                                       id="email_verification" {{ $user->email_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="email_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>@lang('SMS Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='sms_verification'>
                                                <input type="checkbox" name="sms_verification"
                                                       class="custom-switch-checkbox"
                                                       id="sms_verification" {{ $user->sms_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="sms_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <label>@lang('2FA Security')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='0' name='two_fa_verify'>
                                                <input type="checkbox" name="two_fa_verify"
                                                       class="custom-switch-checkbox"
                                                       id="two_fa_verify" {{ $user->two_fa_verify == 1 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div> -->

                                    </div>
                                </div>

                                <input type="file" name="image" placeholder="@lang('Choose image')" id="image" class="d-none">

                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update User')</span></button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- <div class="card card-primary ">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Password Change')</h4>

                        <form method="post" action="{{ route('admin.userPasswordUpdate',$user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group ">
                                        <label>@lang('New Password')</label>
                                        <input id="new_password" type="password" class="form-control" name="password"
                                               autocomplete="current-password">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group ">
                                        <label>@lang('Confirm Password')</label>
                                        <input id="confirm_password" type="password" name="password_confirmation"
                                               autocomplete="current-password" class="form-control">
                                        @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update Password')</span></button>
                            </div>
                        </form>
                    </div>
                </div> -->
            </div>
        </div>
    </div>




    <!-- The Modal -->
    <div class="modal fade" id="balancelbp">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.user-balance-lbp-update',$user->id) }}"
                      enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Add / Subtract Balance')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group ">
                            <label>@lang('Amount')</label>
                            <input class="form-control" type="text" name="lbalance" id="lbalance">
                        </div>

                        <div class="form-group">
                            <div class="custom-switch-btn w-md-100">
                               <input type='hidden' value='1' name='add_statuslbp'>
                                <input type="checkbox" name="add_statuslbp" class="custom-switch-checkbox" id="add_statuslbp"
                                       value="0">
                                <label class="custom-switch-checkbox-label" for="add_statuslbp">
                                    <span class="modal_status custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary balanceSavelbp"><span>@lang('Submit')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="balance">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.user-balance-update',$user->id) }}"
                      enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Add / Subtract Balance')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group ">
                            <label>@lang('Amount')</label>
                            <input class="form-control" type="text" name="balance" id="balance">
                        </div>

                        <div class="form-group">
                            <div class="custom-switch-btn w-md-100">
                               <input type='hidden' value='1' name='add_status'>
                                <input type="checkbox" name="add_status" class="custom-switch-checkbox" id="add_status"
                                       value="0">
                                <label class="custom-switch-checkbox-label" for="add_status">
                                    <span class="modal_status custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary balanceSave"><span>@lang('Submit')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="signIn">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="" class="loginAccountAction" enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Sing In Confirmation')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>@lang('Are you sure to sign in this account?')</p>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary "><span>@lang('Yes')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $(document).on('click', '#image-label', function () {
                $('#image').trigger('click');
            });
            $(document).on('change', '#image', function () {
                var _this = $(this);
                var newimage = new FileReader();
                newimage.readAsDataURL(this.files[0]);
                newimage.onload = function (e) {
                    $('#image_preview_container').attr('src', e.target.result);
                }
            });
            $(document).on('click', '.balanceSave', function () {
                var bala = $('#balance').text();
            });
                        $(document).on('click', '.balanceSavelbp', function () {
                var bala = $('#balancelbp').text();
            });
        });


        $('.copyBoard').on('click',function () {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success("Copied: " + copyText.value);
        });

        $('.generateBtn').on('click', function () {
            $.ajax({
                url: "{{route('admin.user.keyGenerate',[$user->id])}}",
                type: 'POST',
                success(data) {
                    $("#referralURL").val(data)

                    Notiflix.Notify.Success("KEY GENERATE: " + data);

                }
            });
        });

        $(document).on('click', '.loginAccount', function () {
            var route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });


    </script>
@endpush


